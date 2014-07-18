#!/usr/bin/env ruby
# Encoding: utf-8
#
# Author:: api.jimper@gmail.com (Jonathon Imperiosi)
#
# Copyright:: Copyright 2014, Google Inc. All Rights Reserved.
#
# License:: Licensed under the Apache License, Version 2.0 (the "License");
#           you may not use this file except in compliance with the License.
#           You may obtain a copy of the License at
#
#           http://www.apache.org/licenses/LICENSE-2.0
#
#           Unless required by applicable law or agreed to in writing, software
#           distributed under the License is distributed on an "AS IS" BASIS,
#           WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
#           implied.
#           See the License for the specific language governing permissions and
#           limitations under the License.
#
# This example retrieves available creative groups for a given advertiser and
# displays the name, ID, advertiser ID, and group number. To get an advertiser
# ID, run get_advertisers.rb. Results are limited to the first 10.
#
# Tags: creativegroup.getCreativeGroups

require_relative 'dfa_utils'

def get_creative_groups(advertiser_id, auth_token)
  # Get the creative group service.
  creative_group_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('creativegroup'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Set up creative group search criteria structure.
  creative_group_search_criteria = {
    :advertiser_ids => {:advertiser_ids => [advertiser_id]}
  }

  # Get creative groups.
  response = creative_group_service.call(
    :get_creative_groups,
    :message => {
      :creative_group_search_criteria => creative_group_search_criteria
    },
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display creative group names, IDs, advertiser IDs, and group numbers.
  if response[0] && response[0][:records]
    record_set = response[0][:records][:records]
    # Ensure records are wrapped in an array, even if there is only one
    # record.
    record_set = [record_set] unless record_set.is_a?(Array)

    record_set.each do |record|
      # Extract the href from the record. The href will specify which element
      # of the response multi_ref array contains the object this record
      # refers to.
      record_href = record[:@href][/#id(.*)/,1].to_i
      creative_group = response[record_href]
      puts ('Creative group with name "%s", ID %d, advertiser ID %d and ' +
          'group number %d was found.') % [creative_group[:name],
          creative_group[:id], creative_group[:advertiser_id],
          creative_group[:group_number]]
    end
  else
    puts 'No creative groups found for your criteria'
  end
end

if __FILE__ == $0
  # Provide criteria to search upon.
  advertiser_id = 'INSERT_ADVERTISER_ID_HERE'.to_i

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  get_creative_groups(advertiser_id, auth_token)
end
