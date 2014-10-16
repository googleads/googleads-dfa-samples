<?php
/**
 * This example retrieves available ad types and displays the name and ID for
 * each type.
 *
 * Tags: ad.getAdTypes
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

// Set SOAP and XML settings.
$adWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
    'dfa-api/ad?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get AdService.
$adService = new SoapClient($adWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$adService->__setSoapHeaders($headers);

try {
  // Fetch ad types.
  $result = $adService->getAdTypes();
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display ad type and its ID.
foreach($result as $adType) {
  print 'Ad type with name "' . $adType->name . '" and ID ' . $adType->id
      . " was found.\n";
}
