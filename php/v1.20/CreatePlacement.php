<?php
/**
 * This example creates a placement in a given campaign. Requires the DFA site
 * ID and campaign ID in which the placement will be created into. To create a
 * campaign, run CreateCampaign.php. To get a size ID, run GetSize.php. To get
 * placement types, run GetPlacementTypes.php.
 *
 * Tags: placement.savePlacement
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

// Provide information on placement to be created.
$dfaSiteId = (float) 'INSERT_DFA_SITE_ID_HERE';
$campaignId = (float) 'INSERT_CAMPAIGN_ID_HERE';
$pricingType = (int) 'INSERT_PRICING_TYPE_ID_HERE';
$placementType = (int) 'INSERT_PLACEMENT_TYPE_ID_HERE';
$sizeId = (float) 'INSERT_SIZE_ID_HERE';

// Set SOAP and XML settings.
$placementWsdl = 'https://advertisersapi.doubleclick.net/v1.20/api/' .
    'dfa-api/placement?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrive auth token
$token = DfaUtils::authenticate();

// Get PlacementService.
$placementService = new SoapClient($placementWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$placementService->__setSoapHeaders($headers);

// Create placement structure.
$placement = array(
    'id' => 0,
    'name' => 'Placement ' . uniqid(),
    'campaignId' => $campaignId,
    'placementType' => $placementType,
    'dfaSiteId' => $dfaSiteId,
    'sizeId' => $sizeId,
    'pricingSchedule' => array(
        'startDate' => strtotime('now'),
        'endDate' => strtotime('+1 month'),
        'pricingType' => $pricingType,
        'capCostOption' => 0,
        'flighted' => false),
    'archived' => false,
    'contentCategoryId' => 0,
    'placementGroupId' => 0,
    'placementStrategyId' => 0,
    'siteId' => 0,
    'paymentAccepted' => true,
    'spotlightActivityId' => 0);

// Set the placement tag settings by retrieving all of the regular placement tag
// options and using them.
try {
  // Fetch the tag options.
  $placementTagOptions = $placementService->getRegularPlacementTagOptions();
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Place the tag options in a tag settings configuration and add it to the
// placement.
$tagSettings = array(
    'includeClickTrackingStringInTags' => false,
    'keywordHandlingOption' => 0);
$tagTypes = array();
foreach($placementTagOptions as $tag) {
  $tagTypes[] = $tag->id;
}
$tagSettings['tagTypes'] = $tagTypes;
$placement['tagSettings'] = $tagSettings;

try {
  // Save the placement.
  $result = $placementService->savePlacement($placement);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display the ID of the newly created placement.
print 'Placement with ID ' . $result->id . " was created.\n";
