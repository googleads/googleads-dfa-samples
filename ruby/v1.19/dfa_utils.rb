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
# Handles common tasks across all DFA Reporting API samples.

require 'savon'
require 'signet/oauth_2/client'
require 'yaml'

class DfaUtils
  # The base URL for all DFA API service WSDLs
  WSDL_BASE_URL = 'https://advertisersapi.doubleclick.net/v1.19/api/dfa-api/'

  # Authenticates with the DFA API using credentials stored in dfa_api.yaml
  # Returns an access token, to be used for subsequent API requests
  def self.authenticate()
    credentials = DfaUtils.load_credentials()

    access_token =
      DfaUtils.fetch_access_token(credentials['client_id'],
        credentials['client_secret'], credentials['refresh_token'])

    return DfaUtils.fetch_dfa_auth_token(credentials['username'], access_token)
  end

  # Creates a DFA request header object
  def self.generate_request_header()
    application_name = '%s (DFA API Ruby Samples)' %
      DfaUtils.load_credentials()['application_name']

    return {:request_header => {:application_name => application_name}}
  end

  def self.generate_wsdl_url(service)
    return WSDL_BASE_URL + service + '?wsdl'
  end

  # Returns the DFA Username stored in dfa_api.yaml
  def self.get_dfa_username()
    return DfaUtils.load_credentials()['username']
  end

  private

  def self.load_credentials()
    return YAML::load_file('dfa_api.yaml')['dfa']
  end

  def self.fetch_access_token(client_id, client_secret, refresh_token)
    client = Signet::OAuth2::Client.new(
      :authorization_endpoint_uri =>
        'https://accounts.google.com/o/oauth2/auth',
      :client_id => client_id,
      :client_secret => client_secret,
      :refresh_token => refresh_token,
      :scope => 'https://www.googleapis.com/auth/dfatrafficking',
      :token_credential_uri => 'https://accounts.google.com/o/oauth2/token')

    return client.fetch_access_token['access_token']
  end

  def self.fetch_dfa_auth_token(username, access_token)
    client = Savon.client(
      :wsdl => DfaUtils.generate_wsdl_url('login'),
      :headers => {'Authorization' => 'Bearer ' + access_token})

    response = client.call(
      :authenticate,
      :message => {:username => username},
      :soap_header => {
        :request_header => {
          :application_name => 'dfa ruby samples'
      }
    })

    response = response.to_hash[:multi_ref]
    return response[:token]
  end
end
