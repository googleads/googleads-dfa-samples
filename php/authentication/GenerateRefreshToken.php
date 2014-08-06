<?php
/**
 * This example illustrates how to generate a refresh token
 *
 * PHP version 5
 * PHP extensions: google/apiclient.
 *
 * Copyright 2014, Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package    GoogleApiAdsDfa
 * @category   WebServices
 * @copyright  2014, Google Inc. All Rights Reserved.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License,
 *             Version 2.0
 * @author     Jonathon Imperiosi <api.jimper@gmail.com>
 */

require_once 'vendor/autoload.php';

// Your OAuth 2.0 Client ID and Secret. If you do not have an ID and Secret yet,
// please go to https://console.developers.google.com and create a set.
$client_id = 'INSERT_CLIENT_ID_HERE';
$client_secret = 'INSERT_CLIENT_SECRET_HERE';

// The DFA API OAuth 2.0 scope.
$scope = 'https://www.googleapis.com/auth/dfatrafficking';

// This redirect URI will allow you to copy the token from the success screen.
$redirect_uri = 'urn:ietf:wg:oauth:2.0:oob';

# Create the OAuth 2.0 client
$client = new Google_Client();
$client->setAccessType("offline");
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->setScopes($scope);

# Output the authorization URL
print "Log in to your DFA account and open the following URL:\n\n";
print $client->createAuthUrl();
print "\n\nAfter approving the token, enter the verification code.";
print "\n\nCode: ";

# Read in the access code
$code = trim(fgets(STDIN));
$token = $client->authenticate($code);

# Generate and display the refresh token
print "\n\nRefresh token: " . json_decode($token)->refresh_token . "\n";
