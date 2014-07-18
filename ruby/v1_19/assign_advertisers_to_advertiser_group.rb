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
# This example assigns a list of advertisers to an advertiser group.
#
# CAUTION: An advertiser that has campaigns associated with it cannot be
# removed from an advertiser group once assigned.
#
# Tags: advertisergroup.assignAdvertisersToAdvertiserGroup

require_relative 'dfa_utils'

def assign_advertisers_to_advertiser_group(
    advertiser_ids, advertiser_group_id, auth_token)
  # Get the advertiser group service.
  advertiser_group_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('advertisergroup'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Assign the advertisers to the advertiser group.
  advertiser_group_service.call(
    :assign_advertisers_to_advertiser_group,
    :message => {
      :advertiser_group_id => advertiser_group_id,
      :advertiser_ids => {:advertiser_ids => advertiser_ids}
    },
    :soap_header => DfaUtils.generate_request_header())

  # Display the ID of the newly created placement.
  puts 'Successfully assigned advertisers to advertiser group with ID %d' %
      advertiser_group_id
end

if __FILE__ == $0
  # Provide which advertisers to assign to which advertiser group.
  advertiser_ids = [
      'INSERT_FIRST_ADVERTISER_ID_HERE'.to_i,
      'INSERT_SECOND_ADVERTISER_ID_HERE'.to_i]
  advertiser_group_id = 'INSERT_ADVERTISER_GROUP_ID_HERE'.to_i

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  assign_advertisers_to_advertiser_group(
      advertiser_ids, advertiser_group_id, auth_token)
end
