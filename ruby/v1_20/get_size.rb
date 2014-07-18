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
# This example gets the size ID for a given width and height.
#
# Tags: size.getSizes

require_relative 'dfa_utils'

def get_size(auth_token)
  # Get the size service.
  size_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('size'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create size search criteria structure.
  size_search_criteria = {
    :width => WIDTH,
    :height => HEIGHT
  }

  # Fetch the size.
  response = size_service.call(
    :get_sizes,
    :message => {:size_search_criteria => size_search_criteria,},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display size ID.
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
      size = response[record_href]
      puts 'Size ID for %dx%d is %d.' % [size[:width], size[:height],
          size[:id]]
    end
  else
    puts 'No size found for your criteria.'
  end
end

if __FILE__ == $0
  # Provide criteria to search upon.
  WIDTH = 'INSERT_WIDTH_HERE'.to_i
  HEIGHT = 'INSERT_HEIGHT_HERE'.to_i

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  get_size(auth_token)
end
