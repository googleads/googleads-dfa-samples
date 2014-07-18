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
# This example assigns creatives to placements and creates a unique ad for each
# assignment. To get creatives, run the get_creatives.rb example. To get
# placements, run get_placements.rb.
#
# Tags: creative.assignCreativesToPlacements

require_relative 'dfa_utils'

# Get the creative service.
CREATIVE_URL = 'https://advertisersapi.doubleclick.net/v1.20/api/dfa-api/' +
    'creative?wsdl'

def assign_creatives_to_placements(creative_ids, placement_ids, auth_token)
  # Get the creative service.
  creative_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('creative'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create creative placement assignment array.
  creative_placement_assignments = Array.new

  creative_ids.length.times do |count|
    creative_placement_assignments << {
      :creative_id => creative_ids[count],
      :placement_id => placement_ids[0],
      :placement_ids => {:placement_ids => placement_ids}
    }
  end

  # Assign creatives to placements.
  response = creative_service.call(
    :assign_creatives_to_placements,
    :message => {
      :creative_placement_assignments => {
        'creative_placement_assignments' => creative_placement_assignments
      }
    },
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Ensure responses are wrapped in an array, even if there is only one.
  response = [response] unless response.is_a?(Array)

  # Display new ads that resulted from the assignment.
  response.each do |result|
    puts 'Ad with name "%s" and ID %d was created.' %
        [result[:ad_name], result[:ad_id]]
  end
end

if __FILE__ == $0
  # Provide which creatives to assign to which placements.
  creative_ids = [
      'INSERT_FIRST_CREATIVE_ID_HERE'.to_i,
      'INSERT_SECOND_CREATIVE_ID_HERE'.to_i]
  placement_ids = [
      'INSERT_FIRST_PLACEMENT_ID_HERE '.to_i,
      'INSERT_SECOND_PLACEMENT_ID_HERE '.to_i]

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  assign_creatives_to_placements(creative_ids, placement_ids, auth_token)
end
