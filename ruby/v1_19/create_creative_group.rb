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
# This example creates a creative group associated with a given advertiser. To
# get an advertiser ID, run get_advertisers.rb. Valid group numbers are
# limited to 1 or 2.
#
# Tags: creativegroup.saveCreativeGroup

require_relative 'dfa_utils'

def create_creative_group(advertiser_id, group_number, auth_token)
  # Get the creative group service.
  creative_group_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('creativegroup'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create creative group structure. Note that to create a new creative group,
  # you must set the ID number to -1.
  creative_group = {
    :id => -1,
    :advertiser_id => advertiser_id,
    :group_number => group_number,
    :name => 'Creative Group #%d' % (Time.new.to_f * 1000).to_i,
  }

  # Save the creative group.
  response = creative_group_service.call(
    :save_creative_group,
    :message => {:creative_group => creative_group},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display the ID of the newly created creative group.
  puts 'Creative group with ID %d was created.' % response[:id]
end

if __FILE__ == $0
  # Provide necessary information for creating a campaign.
  advertiser_id = 'INSERT_ADVERTISER_ID_HERE'.to_i
  group_number = 'INSERT_GROUP_NUMBER_HERE'.to_i

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  create_creative_group(advertiser_id, group_number, auth_token)
end
