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
# This example creates an advertiser in a given DFA network. To get the network
# ID, run authenticate.rb.
#
# Tags: advertiser.saveAdvertiser

require_relative 'dfa_utils'

def create_advertiser(auth_token)
  # Get the advertiser service.
  advertiser_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('advertiser'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create advertiser structure.
  advertiser = {
    :id => 0,
    :name => 'Advertiser #%d' % (Time.new.to_f * 1000).to_i,
    :approved => true
  }

  # Save the advertiser.
  response = advertiser_service.call(
    :save_advertiser,
    :message => {:advertiser => advertiser},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display the ID of the newly created advertiser.
  puts 'Advertiser with ID %d was created.' % response[:id]
end

if __FILE__ == $0
  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  create_advertiser(auth_token)
end
