ExaVault PHP SDK
============

evapi-php is an API client written in PHP for connecting to the
ExaVault API. The ExaVault API is a REST-like API providing operations
for file and user management, and supports both ``POST`` and ``GET``
requests.

To get started using ExaVault's API, you first must have an ExaVault
account and obtain an API key. For more information, please refer to
our [Developer page](https://www.exavault.com/developer/) or contact
support@exavault.com for details.

## Prerequisites 

PHP 5.4.0 and later

## Installation

```sh
$ composer require commercialpeople/exavault-php-sdk:"dev-master"
```

## Getting started 

You will need to obtain an API key for your application from the [Client Area](https://clients.exavault.com/clientarea.php?action=products) of your account.  To
obtain an API key, please follow the instructions below.

 + Login to the [Accounts](https://clients.exavault.com/clientarea.php?action=products) section of the Client Area.
 + Use the drop down next to your desired account, and select *Manage API Keys*.
 + You will be brought to the API Key management screen. Fill out the form and save to generate a new key for your app.

Once you obtain your API you can use the following snippet. It will allow you to authenticate into API, create folder, get activity logs and log out user from the API.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

// Authenticate User
$authApi = new ExaVault\Sdk\Api\AuthenticationApi();
$apiKey = '<api-key>';
$username = '<username>';
$password = '<password>'; 
$accessToken = '<token>';

try {

  $response = $authApi->authenticateUser($apiKey, $username, $password);
  $loginSuccess = $response['success'];

  if ($loginSuccess) {
    $accessToken = $response['results']['access_token'];
  } else {
    // something went wrong check $response['error'] for more details
    throw new Exception($response['error']['message']);
  }

} catch (Exception $e) {
    // server error occurred
    echo 'Exception when calling AuthenticationApi->authenticateUser: ', $e->getMessage(), PHP_EOL;
    exit;
}

// Create Folder
$fsApi = new ExaVault\Sdk\Api\FilesAndFoldersApi();
$folderName = 'api_test_folder'.rand();
$path = '/';

try{

  $response = $fsApi->createFolder($apiKey, $accessToken, $folderName, $path);
  $createSuccess = $response['success'];

  if ($createSuccess) {
    // Folder created successfully
    echo ('Folder created successfully');
  }
  else{
    // something went wrong check $response['error'] for more details
    throw new Exception($response['error']['message']);
  }

} catch (Exception $e) {
    // server error occurred
    echo 'Exception when calling FilesAndFoldersApi->createFolder: ', $e->getMessage(), PHP_EOL;
    exit;
}

// Get Activity Logs
$activityApi = new ExaVault\Sdk\Api\ActivityApi();
$offset = 0;
$sort_by = 'sort_logs_date'; 
$sort_order = 'desc'; 

try {

  $response = $activityApi->getFileActivityLogs($apiKey, $accessToken, $offset, $sort_by, $sort_order);
  $getActivitySuccess = $response['success'];


  if ($getActivitySuccess) {
    // Geat array with log entries
    $activityLogs = $response['results'];
    print_r($activityLogs);
  }
  else{
    // something went wrong check $response['error'] for more details
    throw new Exception($response['error']['message']);
  }

} catch (Exception $e) {
    echo 'Exception when calling ActivityApi->getFileActivityLogs: ', $e->getMessage(), PHP_EOL;
    exit;
}

// To logout the current user, simply check the $loginSuccess flag
// that was stored earlier and then call the `logoutUser` method
if ($loginSuccess) {
  $authApi->logoutUser($apiKey, $accessToken);
}

```

You can find list of all API requets here - [ExaVault API Docs](https://www.exavault.com/developer/api-docs/)

