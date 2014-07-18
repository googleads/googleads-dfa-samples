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
# This example displays advertiser group name, ID, and advertiser count for the
# given search criteria. Results are limited to the first 10 records.
#
# Tags: advertisergroup.getAdvertiserGroups

require_relative 'dfa_utils'

def get_advertiser_group(search_string, auth_token)
  # Get the advertiser group service.
  advertiser_group_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('advertisergroup'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create advertiser group search criteria structure.
  advertiser_group_search_criteria = {
    :page_number => 0,
    :page_size => 10,
    :search_string => search_string
  }

  # Get ad types.
  response = advertiser_group_service.call(
    :get_advertiser_groups,
    :message => {
      :advertiser_group_search_criteria => advertiser_group_search_criteria
    },
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display advertiser group names, IDs and advertiser count.
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
      advertiser_group = response[record_href]
      puts ('Advertiser Group with name "%s", ID %d, containing %d ' +
          'advertisers was found.') % [advertiser_group[:name],
          advertiser_group[:id], advertiser_group[:advertiser_count]]
    end
  else
    puts 'No advertiser groups found for your criteria'
  end
end

if __FILE__ == $0
  # Provide criteria to search upon.
  search_string = 'INSERT_SEARCH_STRING_CRITERIA_HERE'

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  get_advertiser_group(search_string, auth_token)
end
