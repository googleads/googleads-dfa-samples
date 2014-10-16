<?php
/**
 * This example displays advertiser group name, ID, and advertiser count for the
 * given search criteria. Results are limited to the first 10 records.
 *
 * Tags: advertisergroup.getAdvertiserGroups
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

// Create advertiser group search criteria structure.
$advertiserGroupSearchCriteria = array(
    'pageNumber' => 0,
    'pageSize' => 10,
    'searchString' => $searchString);

try {
  // Fetch the advertiser groups.
  $result = $advertiserGroupService->getAdvertiserGroups(
      $advertiserGroupSearchCriteria);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display advertiser group names, IDs and number of associated advertisers.
if (isset($result->records)) {
  foreach($result->records as $advertiserGroup) {
    print 'Advertiser group with name "' . $advertiserGroup->name . '", ID '
        . $advertiserGroup->id . ', containing '
        . $advertiserGroup->advertiserCount . " advertisers was found.\n";
  }
} else {
  print "No advertiser groups found for your criteria.\n";
}
