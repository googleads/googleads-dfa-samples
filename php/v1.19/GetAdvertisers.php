<?php
/**
 * This example displays advertiser name, ID and spotlight configuration ID for
 * the given search criteria. Results are limited to first 10 records.
 *
 * Tags: advertiser.getAdvertisers
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

// Provide criteria to search upon.
$searchString = 'INSERT_SEARCH_STRING_CRITERIA_HERE';

// Set SOAP and XML settings.
$advertiserWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
    'dfa-api/advertiser?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get AdvertiserService.
$advertiserService = new SoapClient($advertiserWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$advertiserService->__setSoapHeaders($headers);

// Create advertiser search criteria structure.
$advertiserSearchCriteria = array(
    'pageNumber' => 0,
    'pageSize' => 10,
    'searchString' => $searchString,
    'includeAdvertisersWithOutGroupOnly' => false,
    'includeInventoryAdvertisersOnly' => false,
    'subnetworkId' => 0);

try {
  // Fetch the advertisers.
  $result = $advertiserService->getAdvertisers($advertiserSearchCriteria);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display advertiser names, IDs and spotlight configuration IDs.
if (isset($result->records)) {
  foreach($result->records as $advertiser) {
    print 'Advertiser with name "' . $advertiser->name . '", ID '
        . $advertiser->id . ', and spotlight configuration ID '
        . $advertiser->spotId . " was found.\n";
  }
} else {
  print "No advertisers found for your criteria.\n";
}
