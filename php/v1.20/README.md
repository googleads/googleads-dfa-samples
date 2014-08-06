# DFA API v1.20 PHP Samples

This is a collection of samples written in PHP which provide a starting place
for your experimentation into the DFA API v1.20.

## Prerequisites

  - PHP 5.3+
  - SoapClient
  - Composer

From the example directory, run `composer install` to install all dependecies

## Setup Authentication

These samples use OAuth 2.0 for authentication. Credential information is loaded
from a `dfa_api.ini` file, which needs to be populated with the following:

 - `username`: your DFA user profile name
 - `client_id`: your OAuth 2.0 client ID
 - `client_secret`: your OAuth 2.0 client secret
 - `refresh_token`: your OAuth 2.0 refresh token
 - `application_name`: a name for your sample application

Learn more about Google APIs and OAuth 2.0 here:

https://developers.google.com/accounts/docs/OAuth2

Or, if you'd like to dive right in, follow these steps.
 - Visit https://console.developers.google.com to register your application.
 - Click on "Credentials" in the left navigation menu
 - Click the button labeled "Create an OAuth2 client ID"
 - Give your application a name and click "Next"
 - Select "Installed Application" as the "Application type"
 - Under "Installed application type" select "Other"
 - Click "Create client ID"
 - Copy the client ID and client secret that were generated into `dfa_api.ini`
 - Run the `generateRefreshToken.php` sample, and copy the refresh token into
   `dfa_api.ini`

## Running the Examples

I'm assuming you've checked out the code and are reading this from a local
directory. If not check out the code to a local directory.

1. Open a sample and fill in any prerequisite values. Required values will be
declared as constants near the top of the file.

2. Execute the sample, e.g.

        $ php create_campaign.php

3. Examine your shell output, be inspired and start hacking an amazing new app!
