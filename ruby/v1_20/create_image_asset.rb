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
# This example creates an image creative asset associated with a given
# advertiser. To create an advertiser, run create_advertiser.rb.
#
# Tags: creative.saveCreativeAsset

require_relative 'dfa_utils'
require 'open-uri'
require 'base64'
require 'net/https'

def create_image_asset(advertiser_id, image_url, auth_token)
  # Get the creative service.
  creative_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('creative'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create creative asset structure.
  creative_asset = {
    :name => 'Asset %d.gif' % (Time.new.to_f * 1000).to_i,
    :advertiser_id => advertiser_id,
    :content => Base64.encode64(open(image_url, 'rb') {|f| f.read}),
    :for_HTML_creatives => false
  }

  # Save the creative asset.
  response = creative_service.call(
    :save_creative_asset,
    :message => {:creative_asset => creative_asset},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display the filename of the newly created creative asset.
  puts 'Creative asset with filename of "%s" was created.' %
      response[0][:saved_filename]
end

if __FILE__ == $0
  # Provide information on the creative asset to be created.
  advertiser_id = 'INSERT_ADVERTISER_ID_HERE'.to_i

  # Location of the image asset to use
  image_url = 'http://code.google.com/images/code_logo.gif'

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  create_image_asset(advertiser_id, image_url, auth_token)
end
