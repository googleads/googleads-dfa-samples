<?php
/**
 * This example creates an image creative asset associated with a given
 * advertiser. To create an advertiser, run CreateAdvertiser.php.
 *
 * Tags: creative.saveCreativeAsset
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

// Provide information on the creative asset to be created.
$advertiserId = (float) 'INSERT_ADVERTISER_ID_HERE';

// Location of the image asset to be uploaded
$imageUrl = 'http://code.google.com/images/code_logo.gif';

// Set SOAP and XML settings.
$creativeWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
    'dfa-api/creative?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get CreativeService.
$creativeService = new SoapClient($creativeWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$creativeService->__setSoapHeaders($headers);

// Create creative asset structure.
$creativeAsset = array(
    'name' => 'Asset ' . uniqid() . '.gif',
    'advertiserId' => $advertiserId,
    'content' => file_get_contents($imageUrl),
    'forHTMLCreatives' => false);

try {
  // Save the creative asset.
  $result = $creativeService->saveCreativeAsset($creativeAsset);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display the filename of the newly created creative asset.
print 'Creative asset with filename of "' . $result->savedFilename . '"'
    . " was created.\n";
