<?php
/**
 * This example creates a sequential rotation group ad in a given campaign.
 * Start and end time for the ad must be within campaign start and end dates.
 * To create a creative, run CreateImageCreative.php. To get available
 * placements, run GetPlacements.php. To get a size ID, run GetSize.php.
 *
 * Tags: ad.saveAd
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

// Provide information on the rotation group ad to be created.
$campaignId = (float) 'INSERT_CAMPAIGN_ID_HERE';
$creativeId = (float) 'INSERT_CREATIVE_ID_HERE';
$placementId = (float) 'INSERT_PLACEMENT_ID_HERE';
$sizeId = (float) 'INSERT_SIZE_ID_HERE';

// Set SOAP and XML settings.
$adWsdl = 'https://advertisersapi.doubleclick.net/v1.20/api/dfa-api/ad?wsdl';
$namespace = 'http://www.doubleclick.net/dfa-api/v1.20';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get AdService.
$adService = new SoapClient($adWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$adService->__setSoapHeaders($headers);

// Create creative assignment structure.
$creativeAssignment = array(
    'active' => true,
    'clickThroughUrl' => array(
        'defaultLandingPageUsed' => true,
        'landingPageId' => 0),
    'creativeId' => $creativeId,
    'sequence' => 1,
    'weight' => 0);

// Create placement assignment structure.
$placementAssignment = array(
    'active' => true,
    'placementId' => $placementId);

// Create rotation group ad structure.
$ad = array(
    'active' => true,
    'archived' => false,
    'audienceSegmentId' => 0,
    'campaignId' => $campaignId,
    'costType' => 0,
    'creativeAssignments' => array($creativeAssignment),
    'creativeOptimizationEnabled' => false,
    'deliveryLimit' => 0,
    'deliveryLimitEnabled' => false,
    'endTime' => strtotime('+1 month'),
    'frequencyCap' => 0,
    'frequencyCapPeriod' => 0,
    'hardCutOff' => false,
    'id' => 0,
    'name' => 'Ad ' . uniqid(),
    'placementAssignments' => array($placementAssignment),
    'priority' => 12,
    'ratio' => 1,
    'rotationType' => 1, // Sequential rotation type
    'startTime' => strtotime('now'),
    'typeId' => 1, // Standard ad type
    'sizeId' => $sizeId,
    'userLocalTime' => true);

// Ads implement an abstract type, AdBase. Because of this, an xsi:type is
// required in the SOAP message to specify which implementation is being sent.
// This SoapVar wrapper will say that this is a RotationGroup.
$ad = new SoapVar($ad, SOAP_ENC_OBJECT, 'RotationGroup', $namespace);

try {
  // Save the ad.
  $result = $adService->saveAd($ad);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display the ID of the newly created ad.
printf("Rotation group ad with ID %d was created.\n", $result->id);
