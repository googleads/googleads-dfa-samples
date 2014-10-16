<?php
/**
 * This example creates an advertiser group.
 *
 * Tags: advertisergroup.saveAdvertiserGroup
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

require_once 'DfaUtils.php';

// Set SOAP and XML settings.
$advertiserGroupWsdl = 'https://advertisersapi.doubleclick.net/v1.20/api/' .
    'dfa-api/advertisergroup?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get AdvertiserGroupService.
$advertiserGroupService = new SoapClient($advertiserGroupWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$advertiserGroupService->__setSoapHeaders($headers);

// Create advertiser group structure.
$advertiserGroup = array(
    'id' => 0,
    'name' => 'Advertiser Group ' . uniqid(),
    'advertiserCount' => 0);

try {
  // Save the advertiser group.
  $result = $advertiserGroupService->saveAdvertiserGroup($advertiserGroup);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display the ID of the newly created advertiser group.
print 'Advertiser group with ID ' . $result->id . " was created.\n";
