<?php
/**
 * This example gets existing DFA sites based on a given search criteria.
 * Results are limited to the first 10.
 *
 * Tags: sites.getDfaSites
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
$networkId = (float) 'INSERT_NETWORK_ID_HERE';
$subnetworkId = (float) 'INSERT_SUBNETWORK_ID_HERE';

// Set SOAP and XML settings.
$siteWsdl = 'https://advertisersapi.doubleclick.net/v1.20/api/' .
    'dfa-api/site?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrive auth token
$token = DfaUtils::authenticate();

// Get SiteService.
$siteService = new SoapClient($siteWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$siteService->__setSoapHeaders($headers);

// Create site search criteria structure.
$siteSearchCriteria = array(
    'pageNumber' => 0,
    'pageSize' => 10,
    'searchString' => $searchString,
    'excludeSitesMappedToSiteDirectory' => false,
    'networkId' => $networkId,
    'subnetworkId' => $subnetworkId);

try {
  // Fetch the DFA sites.
  $result = $siteService->getDfaSites($siteSearchCriteria);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display DFA site names and IDs.
if (isset($result->records)) {
  foreach($result->records as $dfaSite) {
    print 'DFA site with name "' . $dfaSite->name . '" and ID '
        . $dfaSite->id . " was found.\n";
  }
} else {
  print "No DFA sites found for your criteria.\n";
}
