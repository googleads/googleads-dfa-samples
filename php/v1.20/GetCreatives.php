<?php
/**
 * This example retrieves available creatives for a given advertiser and
 * displays the name and ID. To find an advertiser, run GetAdvertisers.php.
 * Results are limited to the first 10.
 *
 * Tags: creative.getCreatives
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
$advertiserId = (float) 'INSERT_ADVERTISER_ID_HERE';

// Set SOAP and XML settings.
$creativeWsdl = 'https://advertisersapi.doubleclick.net/v1.20/api/' .
    'dfa-api/creative?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get CreativeService.
$creativeService = new SoapClient($creativeWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$creativeService->__setSoapHeaders($headers);

// Create creative search criteria structure.
$creativeSearchCriteria = array(
    'pageNumber' => 0,
    'pageSize' => 10,
    'advertiserId' => $advertiserId,
    'searchString' => $searchString,
    'archiveStatusFilter' => 0,
    'campaignId' => 0,
    'studioCreative' => false);

try {
  // Fetch the creatives.
  $result = $creativeService->getCreatives($creativeSearchCriteria);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display creative names and IDs.
if (isset($result->records)) {
  foreach($result->records as $creative) {
    print 'Creative with name "' . $creative->name . '" and ID '
        . $creative->id . " was found.\n";
  }
} else {
  print "No creatives found for your criteria.\n";
}
