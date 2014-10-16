<?php
/**
 * This example assigns a list of advertisers to an advertiser group.
 *
 * CAUTION: An advertiser that has campaigns associated with it cannot be
 * removed from an advertiser group once assigned.
 *
 * Tags: advertisergroup.assignAdvertisersToAdvertiserGroup
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

// Provide which advertisers to assign to which group.
$advertiserIds = array((float) 'INSERT_FIRST_ADVERTISER_ID_HERE',
    (float) 'INSERT_SECOND_ADVERTISER_ID_HERE');
$advertiserGroupId = (float) 'INSERT_ADVERTISER_GROUP_ID_HERE';

// Set SOAP and XML settings.
$advertiserGroupWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
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

try {
  // Assign advertisers to the advertiser group.
  $advertiserGroupService->assignAdvertisersToAdvertiserGroup(
      $advertiserGroupId, $advertiserIds);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

print "The advertisers have been assigned to the advertiser group.\n";
