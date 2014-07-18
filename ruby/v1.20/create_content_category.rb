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
# This example creates a content category.
#
# Tags: contentcategory.saveContentCategory

require_relative 'dfa_utils'

def create_content_category(auth_token)
  # Get the content category service.
  content_category_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('contentcategory'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create content category structure.
  content_category = {
    :id => 0,
    :name => 'Content Category #%d' % (Time.new.to_f * 1000).to_i
  }

  # Save the content category.
  response = content_category_service.call(
    :save_content_category,
    :message => {:content_category => content_category},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display the ID of the newly created content category.
  puts 'Content category with ID %d was created.' % response[:id]
end

if __FILE__ == $0
  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  create_content_category(auth_token)
end
