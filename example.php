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
