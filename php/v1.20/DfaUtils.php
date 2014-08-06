<?php
/**
 * Handles common tasks across all DFA Reporting API samples.
 *
 * PHP version 5.3
 * PHP extensions: google/apiclient, SoapClient.
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

class DfaUtils {
  private static $loginService =
    'https://advertisersapi.doubleclick.net/v1.20/api/dfa-api/login?wsdl';
  private static $dfaNamespace =
    'https://advertisersapi.doubleclick.net/dfa-api/v1.20';
  private static $wsseNamespace =
    'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
  private static $scope = 'https://www.googleapis.com/auth/dfatrafficking';

  # Authenticates with the DFA API using credentials stored in dfa_api.yaml
  # Returns an access token, to be used for subsequent API requests
  public static function authenticate() {
    $credentials = self::loadCredentials();

    $accessToken =
      self::fetchAccessToken($credentials['client_id'],
        $credentials['client_secret'], $credentials['refresh_token']);

    return self::fetchDfaAuthToken($credentials['username'], $accessToken);
  }

  # Creates a DFA request header object
  public static function generateRequestHeader() {
    $credentials = self::loadCredentials();
    $applicationName = $credentials['application_name'] .
      ' (DFA API PHP Samples)';

    $applicationNameVar = new SoapVar($applicationName, XSD_STRING, null,
      self::$dfaNamespace, null, self::$dfaNamespace);
    $headerBody = array('applicationName' => $applicationNameVar);
    $headerVar = new SoapVar($headerBody, SOAP_ENC_OBJECT, null,
      self::$dfaNamespace, null, self::$dfaNamespace);

    return new SoapHeader(self::$dfaNamespace, 'RequestHeader', $headerVar);
  }

  # Creates a WSSE header object, used for authenticating with the DFA API
  public static function generateWsseHeader($authToken) {
    $credentials = self::loadCredentials();

    $usernameVar = new SoapVar($credentials['username'], XSD_STRING, null,
        self::$wsseNamespace, null, self::$wsseNamespace);
    $passwordVar = new SoapVar($authToken, XSD_STRING, null,
        self::$wsseNamespace, null, self::$wsseNamespace);
    $tokenBody = array('Username' => $usernameVar, 'Password' => $passwordVar);
    $tokenVar = new SoapVar($tokenBody, SOAP_ENC_OBJECT, null,
        self::$wsseNamespace, 'UsernameToken', self::$wsseNamespace);
    $securityBody = array('UsernameToken' => $tokenVar);
    $securityVar = new SoapVar($securityBody, SOAP_ENC_OBJECT, null,
        self::$wsseNamespace, 'Security', self::$wsseNamespace);

    return new SoapHeader(self::$wsseNamespace, 'Security',
        $securityVar);
  }

  private static function loadCredentials() {
    return parse_ini_file('dfa_api.ini');
  }

  private static function fetchAccessToken($clientId, $clientSecret,
      $refreshToken) {
    $client = new Google_Client();
    $client->setClientId($clientId);
    $client->setClientSecret($clientSecret);
    $client->setScopes(self::$scope);
    $client->refreshToken($refreshToken);

    return json_decode($client->getAccessToken())->access_token;
  }

  private static function fetchDfaAuthToken($username, $accessToken) {
    $client = new SoapClient(
      self::$loginService,
      array('stream_context' => stream_context_create(
          array('http' =>
            array('header' => 'Authorization: Bearer ' . $accessToken))))
    );
    $client->__setSoapHeaders(self::generateRequestHeader());

    try {
      $result = $client->authenticate($username, null);
    } catch (Exception $e) {
      print $e->getMessage();
      exit(1);
    }

    return $result->token;
  }
}
