<?php
/**
 * This example creates a content category.
 *
 * Tags: contentcategory.saveContentCategory
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
$contentCategoryWsdl = 'https://advertisersapi.doubleclick.net/v1.19/api/' .
    'dfa-api/contentcategory?wsdl';
$options = array('encoding' => 'utf-8');

// Authenticate with the API and retrive auth token
$token = DfaUtils::authenticate();

// Get ContentCategoryService.
$contentCategoryService = new SoapClient($contentCategoryWsdl, $options);

// Set headers.
$headers = array(DfaUtils::generateWsseHeader($token),
    DfaUtils::generateRequestHeader());
$contentCategoryService->__setSoapHeaders($headers);

// Create content category structure.
$contentCategory = array(
    'id' => 0,
    'name' => 'Content Category ' . uniqid());

try {
  // Save the content category.
  $result = $contentCategoryService->saveContentCategory($contentCategory);
} catch (Exception $e) {
  print $e->getMessage();
  exit(1);
}

// Display the ID of the newly created content category.
print 'Content category with ID ' . $result->id . " was created.\n";
