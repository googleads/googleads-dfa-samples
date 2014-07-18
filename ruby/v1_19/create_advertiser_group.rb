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
# This example creates an advertiser group.
#
# Tags: advertisergroup.saveAdvertiserGroup

require_relative 'dfa_utils'

def create_advertiser_group(auth_token)
  # Get the advertiser group service.
  advertiser_group_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('advertisergroup'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create advertiser group structure.
  advertiser_group = {
    :id => 0,
    :name => 'Advertiser Group #%d' % (Time.new.to_f * 1000).to_i,
  }

  # Save the advertiser group.
  response = advertiser_group_service.call(
    :save_advertiser_group,
    :message => {:advertiser_group => advertiser_group},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display the ID of the newly created advertiser group.
  puts 'Advertiser group with ID %d was created.' % response[:id]
end

if __FILE__ == $0
  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  create_advertiser_group(auth_token)
end
