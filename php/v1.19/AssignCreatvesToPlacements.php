<?php
/**
 * This example assigns creatives to placements and creates a unique ad for each
 * assignment. To get creatives, run the GetCreatives.php example. To get
 * placements, run GetPlacements.php.
 *
 * Tags: creative.assignCreativesToPlacements
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

// Provide which creatives to assign to which placements.
$creativeIds = array((float) 'INSERT_FIRST_CREATIVE_ID_HERE',
    (float) 'INSERT_SECOND_CREATIVE_ID_HERE');
$placementIds = array((float) 'INSERT_FIRST_PLACEMENT_ID_HERE',
    (float) 'INSERT_SECOND_PLACEMENT_ID_HERE');

// Set SOAP and XML settings.
$creativeWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
    'dfa-api/creative?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrive auth token
$token = DfaUtils::authenticate();

// Get CreativeService.
$creativeService = new SoapClient($creativeWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$creativeService->__setSoapHeaders($headers);

// Create creative placement assignment array.
$creativePlacementAssignments = array();

$count = count($creativeIds);
for($i = 0; $i < $count; $i++) {
  $creativePlacementAssignment = array(
      'creativeId' => $creativeIds[$i],
      'placementId' => $placementIds[0],
      'placementIds' => $placementIds);

  $creativePlacementAssignments[] = $creativePlacementAssignment;
}

try {
  // Assign creatives to placements.
  $results = $creativeService->assignCreativesToPlacements(
      $creativePlacementAssignments);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display new ads that resulted from the assignment.
foreach($results as $ad) {
  print 'Ad with name "' . $ad->adName . '" and ID ' . $ad->adId .
      " was created.\n";
}
