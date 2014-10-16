<?php
/**
 * This example creates a creative group associated with a given advertiser. To
 * get an advertiser ID, run GetAdvertisers.php. Valid group numbers are
 * limited to 1 or 2.
 *
 * Tags: creativegroup.saveCreativeGroup
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

// Provide information on the creative group to be created.
$advertiserId = (float) 'INSERT_ADVERTISER_ID_HERE';

// Set SOAP and XML settings.
$creativeGroupWsdl = 'https://advertisersapi.doubleclick.net/v1.20/api/' .
    'dfa-api/creativegroup?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get CreativeGroupService.
$creativeGroupService = new SoapClient($creativeGroupWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$creativeGroupService->__setSoapHeaders($headers);

// Create creative group structure.
$creativeGroup = array(
    'id' => -1,
    'advertiserId' => $advertiserId,
    'name' => 'Creative Group ' . uniqid(),
    'groupNumber' => 1);

try {
  // Save the creative group.
  $result = $creativeGroupService->saveCreativeGroup($creativeGroup);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display the ID of the newly created creative group.
print 'Creative group with ID ' . $result->id . " was created.\n";
