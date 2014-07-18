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
# This example creates a campaign associated with a given advertiser. To create
# an advertiser, run create_advertiser.rb.
#
# Tags: campaign.saveCampaign

require_relative 'dfa_utils'

# Creates a landing page and returns its ID number.
def create_landing_page(campaign_service)
  landing_page = {
    :id => 0,
    :name => 'LandingPage #%d' % (Time.new.to_f * 1000).to_i,
    :url => 'http://www.example.com'
  }

  # Save the landing page.
  response = campaign_service.call(
    :save_landing_page,
    :message => {:landing_page => landing_page},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  return response[:id]
end

# Creates a campaign and prints its ID number.
def create_campaign(advertiser_id, auth_token)
  # Get the campaign service.
  campaign_service = Savon.client(
    :wsdl => DfaUtils.generate_wsdl_url('campaign'),
    # Set the WSSE authentication header.
    :wsse_auth => [DfaUtils.get_dfa_username(), auth_token])

  # Create a landing page
  landing_page_id = create_landing_page(campaign_service)

  # Create campaign structure.
  campaign = {
    :id => 0,
    :name => 'Campaign #%d' % (Time.new.to_f * 1000).to_i,
    :advertiser_id => advertiser_id,
    :start_date => Date.today.strftime('%FT%T'),
    :end_date => (Date.today >> 1).strftime('%FT%T'),
    :default_landing_page_id => landing_page_id,
    :landing_page_ids => {:landing_page_ids => landing_page_id}
 }

  # Save the campaign.
  response = campaign_service.call(
    :save_campaign,
    :message => {:campaign => campaign},
    :soap_header => DfaUtils.generate_request_header())

  response = response.to_hash[:multi_ref]

  # Display the ID of the newly created campaign.
  puts 'Campaign with ID %d was created.' % response[:id]
end

if __FILE__ == $0
  # Provide necessary information for creating a campaign.
  advertiser_id = 'ENTER_ADVERTISER_ID_HERE'.to_i

  # Generate a DFA authentication token.
  auth_token = DfaUtils.authenticate()

  create_campaign(advertiser_id, auth_token)
end
