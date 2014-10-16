<?php
/**
 * This example gets the size ID for a given width and height.
 *
 * Tags: size.getSizes
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
$width = (int) 'INSERT_WIDTH_HERE';
$height = (int) 'INSERT_HEIGHT_HERE';

// Set SOAP and XML settings.
$sizeWsdl = 'https://advertisersapi.doubleclick.net/v1.20/api/' .
    'dfa-api/size?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get SizeService.
$sizeService = new SoapClient($sizeWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$sizeService->__setSoapHeaders($headers);

// Create size search criteria structure.
$sizeSearchCriteria = array(
    'width' => $width,
    'height' => $height);

try {
  // Fetch the size.
  $result = $sizeService->getSizes($sizeSearchCriteria);
} catch (Exception $e) {
  print_r($e);
  exit(1);
}

// Display size ID.
if (isset($result->records)) {
  foreach($result->records as $size) {
    print 'Size ID for ' . $size->width . 'x' . $size->height . ' is '
        . $size->id . ".\n";
  }
} else {
  print "No size found for your criteria.\n";
}
