<?php
/**
 * This example creates a campaign associated with a given advertiser. To create
 * an advertiser, run CreateAdvertiser.php.
 *
 * Tags: campaign.saveCampaign
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

// Provide information on campaign to be created.
$advertiserId = (float) 'INSERT_ADVERTISER_ID_HERE';

// Set SOAP and XML settings.
$campaignWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
    'dfa-api/campaign?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrive auth token
$token = DfaUtils::authenticate();

// Get CampaignService.
$campaignService = new SoapClient($campaignWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$campaignService->__setSoapHeaders($headers);

// Create campaign structure.
$campaign = array(
    'id' => 0,
    'name' => 'Campaign ' . uniqid(),
    'advertiserId' => $advertiserId,
    'startDate' => strtotime('now'),
    'endDate' => strtotime('+1 month'),
    'archived' => false,
    'advancedAdServingEnabled' => false);

// Create the default landing page for the campaign.
$landingPage = array(
    'id' => 0,
    'name' => 'LandingPage ' . uniqid(),
    'url' => 'http://www.example.com');

try {
  // Save the landing page.
  $landingPageResult = $campaignService->saveLandingPage($landingPage);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Add landing page to the campaign.
$campaign['defaultLandingPageId'] = $landingPageResult->id;
$campaign['landingPageIds'] = array($landingPageResult->id);

try {
  // Save the campaign.
  $result = $campaignService->saveCampaign($campaign);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display the ID of the newly created campaign.
print 'Campaign with ID ' . $result->id . " was created.\n";
