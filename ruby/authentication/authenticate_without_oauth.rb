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
# This example authenticates using your DFA user name and password, and
# displays the user profile token, DFA account name and ID. The user profile
# token along with the user name will be used in all other examples to
# construct the SOAP headers.
#
# Tags: login.authenticate

require 'savon'

def authenticate_without_oauth(username, password, application_name)
  # Get the login service.
  login_url = 'https://advertisersapi.doubleclick.net/v1.20/api/dfa-api/' +
      'login?wsdl'

  login_service = Savon.client(:wsdl => login_url)

  # Authenticate.
  response = login_service.call(
    :authenticate,
    :message => {
      :username => username,
      :password => password
    },
    :soap_header => {
      :request_header => {
        :application_name => application_name
    }
  })

  response = response.to_hash[:multi_ref]

  # Display user profile token, DFA account name and network ID.
  puts 'User profile token is "%s"' % response[:token]
  puts 'DFA account name is "%s"' % response[:network_name]
  puts 'DFA account ID is %d' % response[:network_id]
end

if __FILE__ == $0
  # Provide DFA login information.
  username = 'INSERT_DFA_USERNAME_HERE'
  password = 'INSERT_DFA_PASSWORD_HERE'
  application_name = 'INSERT_APPLICATION_NAME_HERE'

  authenticate_without_oauth(username, password, application_name)
end
