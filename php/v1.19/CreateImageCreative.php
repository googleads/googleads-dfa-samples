<?php
/**
 * This example creates an image creative associated with a given advertiser or
 * campaign. If no campaign is specified then the creative is created at the
 * advertiser level. To create image assets, run CreateImageAsset.php. To get a
 * size ID, run GetSize.php.
 *
 * Tags: creative.saveCreative
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

// Provide information on the creative to be created.
$assetFilename = 'INSERT_CREATIVE_ASSET_FILENAME_HERE';
$advertiserId = (float) 'INSERT_ADVERTISER_ID_HERE';
$campaignId = (float) 'INSERT_CAMPAIGN_ID_HERE';
$sizeId = (float) 'INSERT_SIZE_ID_HERE';

// Set SOAP and XML settings.
$creativeWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
    'dfa-api/creative?wsdl';
$namespace = 'http://www.doubleclick.net/dfa-api/v1.19';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrieve auth token.
$token = DfaUtils::authenticate();

// Get CreativeService.
$creativeService = new SoapClient($creativeWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$creativeService->__setSoapHeaders($headers);

// Create creative structure.
$creative = array(
    'id' => 0,
    'name' => 'Creative ' . uniqid(),
    'advertiserId' => $advertiserId,
    'assetFilename' => $assetFilename,
    'sizeId' => $sizeId,
    'typeId' => 1, // Hard-coded to type 'Image Creative'.
    'renderingId' => 0,
    'active' => true,
    'archived' => false,
    'version' => 1);

// Creatives implement an abstract type, CreativeBase. Because of this, an
// xsi:type is required in the SOAP message to specify which implementation is
// being sent. This SoapVar wrapper will say that this is an ImageCreative.
$creative = new SoapVar($creative, SOAP_ENC_OBJECT, 'ImageCreative',
    $namespace);

try {
  // Save the creative.
  $result = $creativeService->saveCreative($creative, $campaignId);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display the ID of the newly created creative.
print 'Creative with ID ' . $result->id . " was created.\n";
