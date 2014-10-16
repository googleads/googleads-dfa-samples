<?php
/**
 * This example retrieves available creative groups for a given advertiser and
 * displays the name, ID, advertiser ID, and group number. To get an advertiser
 * ID, run GetAdvertisers.php. Results are limited to the first 10.
 *
 * Tags: creativegroup.getCreativeGroups
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

// Create creative group search criteria structure.
$creativeGroupSearchCriteria = array(
    'pageNumber' => 0,
    'pageSize' => 10,
    'advertiserIds' => array($advertiserId),
    'groupNumber' => 0);

try {
  // Fetch the creative groups.
  $result = $creativeGroupService->getCreativeGroups(
      $creativeGroupSearchCriteria);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display creative group names, IDs, advertiser IDs, and group numbers.
if (isset($result->records)) {
  foreach($result->records as $creativeGroup) {
    print 'Advertiser group with name "' . $creativeGroup->name . '", ID '
        . $creativeGroup->id . ', advertiser ID '
        . $creativeGroup->advertiserId . ', and group number '
        . $creativeGroup->groupNumber . " was found.\n";
  }
} else {
  print "No creative groups found for your criteria.\n";
}
