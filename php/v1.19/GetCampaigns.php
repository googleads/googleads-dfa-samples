<?php
/**
 * This example gets existing campaigns based on a given search criteria.
 * Results are limited to the first 10.
 *
 * Tags: campaign.getCampaignsByCriteria
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

// Create campaign search criteria structure.
$campaignSearchCriteria = array(
    'pageNumber' => 0,
    'pageSize' => 10,
    'searchString' => $searchString);

try {
  // Fetch the campaigns.
  $result = $campaignService->getCampaignsByCriteria($campaignSearchCriteria);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display campaign names and IDs.
if (isset($result->records)) {
  foreach($result->records as $campaign) {
    print 'Campaign with name "' . $campaign->name . '" and ID '
        . $campaign->id . " was found.\n";
  }
} else {
  print "No campaigns found for your criteria.\n";
}
