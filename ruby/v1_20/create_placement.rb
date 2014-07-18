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
# This example creates a placement in a given campaign. Requires the DFA site
# ID and campaign ID in which the placement will be created into. To create a
# campaign, run create_campaign.rb. To get a size ID, run get_size.rb. To get
# placement types, run get_placement_types.rb.
#
# Tags: placement.savePlacement

require_relative 'dfa_utils'

def create_placement(
    campaign_id, dfa_site_id, pricing_type, placement_type, size_id, auth_token)
  # Get the placement service.
  placement_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('placement'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create placement structure.
  placement = {
    :id => 0,
    :name => 'Placement #%d' % (Time.new.to_f * 1000).to_i,
    :campaign_id => campaign_id,
    :placement_type => placement_type,
    :dfa_site_id => dfa_site_id,
    :size_id => size_id,
    :pricing_schedule => {
      :start_date => Date.today.strftime('%FT%T'),
      :end_date => (Date.today >> 1).strftime('%FT%T'),
      :pricing_type => pricing_type,
      :cap_cost_option => 0,
      :flighted => false
    },
    :archived => false,
    :content_category_id => 0,
    :placement_group_id => 0,
    :placement_strategy_id => 0,
    :siteId => 0
  }

  # Set the placement tag settings by retrieving all of the regular placement
  # tag options and using them.
  response = placement_service.call(
    :get_regular_placement_tag_options,
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Place the tag options in a tag settings configuration and add it to the
  # placement.
  tag_types = Array.new

  response.each do |tag|
    tag_types << tag[:id]
  end

  tag_settings = {
    :include_click_tracking_string_in_tags => false,
    :keyword_handling_option => 0,
    :tag_types => {
      :tag_types => tag_types
    }
  }

  placement[:tag_settings] = tag_settings;

  # Save the placement.
  response = placement_service.call(
    :save_placement,
    :message => {:placement => placement},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display the ID of the newly created placement.
  puts 'Placement with ID %d was created.' % response[:id]
end

if __FILE__ == $0
  # Provide information on placement to be created.
  dfa_site_id = 'INSERT_DFA_SITE_ID_HERE'.to_i
  campaign_id = 'INSERT_CAMPAIGN_ID_HERE'.to_i
  pricing_type = 'INSERT_PRICING_TYPE_ID_HERE'.to_i
  placement_type = 'INSERT_PLACEMENT_TYPE_ID_HERE'.to_i
  size_id = 'INSERT_SIZE_ID_HERE'.to_i

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  create_placement(campaign_id, dfa_site_id, pricing_type, placement_type,
      size_id, auth_token)
end
