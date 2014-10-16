<?php
/**
 * This example displays available placements for a given search string. Results
 * are limited to 10.
 *
 * Tags: placement.getPlacementsByCriteria
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
$placementWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
    'dfa-api/placement?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get PlacementService.
$placementService = new SoapClient($placementWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$placementService->__setSoapHeaders($headers);

// Create placement search criteria structure.
$placementSearchCriteria = array(
    'pageNumber' => 0,
    'pageSize' => 10,
    'searchString' => $searchString,
    'placementFilter' => 0);

try {
  // Fetch the placements.
  $result =
      $placementService->getPlacementsByCriteria($placementSearchCriteria);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display placement names and IDs.
if (isset($result->records)) {
  foreach($result->records as $placement) {
    print 'Placement with name "' . $placement->name . '" and ID '
        . $placement->id . " was found.\n";
  }
} else {
  print "No placements found for your criteria.\n";
}
