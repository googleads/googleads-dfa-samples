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
# This example creates an image creative associated with a given advertiser or
# campaign. If no campaign is specified then the creative is created at the
# advertiser level. To create image assets, run create_image_asset.rb. To get a
# size ID, run get_size.rb.
#
# Tags: creative.saveCreative

require_relative 'dfa_utils'

def create_image_creative(
    advertiser_id, campaign_id, asset_filename, size_id, auth_token)
  # Get the creative service.
  creative_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('creative'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create creative structure.
  creative = {
    :id => 0,
    :name => 'Creative #%d' % (Time.new.to_f * 1000).to_i,
    :advertiser_id => advertiser_id,
    :asset_filename => asset_filename,
    :size_id => size_id,
    :type_id => 1, # Hard-coded to type 'Image Creative'.
    :active => true
  }

  # Save the creative.
  response = creative_service.call(
    :save_creative,
    :message => {
      :creative => creative,
      :campaign => campaign_id,
      # Creatives implement an abstract type, CreativeBase. Because of this,
      # an xsi:type is required in the SOAP message to specify which
      # implementation is being sent. Savon automatically places the correct
      # namespace for this version of DFA under the "impl" namespace.
      :attributes! => {:creative => {'xsi:type' => 'impl:ImageCreative'}},
    },
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display the ID of the newly created creative.
  puts 'Creative with ID %d was created.' % response[:id]
end

if __FILE__ == $0
  # Provide information on the creative to be created.
  asset_filename = 'INSERT_CREATIVE_ASSET_FILENAME_HERE'
  advertiser_id = 'INSERT_ADVERTISER_ID_HERE'.to_i
  campaign_id = 'INSERT_CAMPAIGN_ID_HERE'.to_i
  size_id = 'INSERT_SIZE_ID_HERE'.to_i

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  create_image_creative(advertiser_id, campaign_id, asset_filename, size_id,
      auth_token)
end
