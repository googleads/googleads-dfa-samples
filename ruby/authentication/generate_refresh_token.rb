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
# This example illustrates how to generate a refresh token

require 'signet/oauth_2/client'

def generate_refresh_token(client_id, client_secret)
  # The DFA API OAuth 2.0 scope.
  scope = 'https://www.googleapis.com/auth/dfatrafficking'

  # This redirect URI will allow you to copy the token from the success screen.
  redirect_uri = 'urn:ietf:wg:oauth:2.0:oob'

  # The web address for generating new OAuth 2.0 credentials at Google.
  google_oauth2_auth_endpoint = 'https://accounts.google.com/o/oauth2/auth'
  google_oauth2_gen_endpoint = 'https://accounts.google.com/o/oauth2/token'

  # Create the OAuth 2.0 client
  client = Signet::OAuth2::Client.new(
    :authorization_uri => google_oauth2_auth_endpoint,
    :token_credential_uri => google_oauth2_gen_endpoint,
    :client_id => CLIENT_ID,
    :client_secret => CLIENT_SECRET,
    :scope => scope,
    :redirect_uri => redirect_uri
  )

  # Output the authorization URL
  puts 'Log in to your DFA account and open the following URL:'
  puts
  puts client.authorization_uri
  puts
  puts 'After approving the token, enter the verification code (if specified).'

  # Read in the access code
  print 'Code: '
  code = gets.chomp

  # Generate and display the refresh token
  client.code = code
  puts 'Refresh token: %s' % [client.fetch_access_token["refresh_token"]]
end

if __FILE__ == $0
  # Your OAuth 2.0 Client ID and Secret. If you do not have an ID and Secret
  # yet, please go to https://console.developers.google.com and create a set.
  client_id = 'INSERT_CLIENT_ID_HERE'
  client_secret = 'INSERT_CLIENT_SECRET_HERE'

  generate_refresh_token(client_id, client_secret)
end
