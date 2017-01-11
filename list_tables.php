<?php

// composer autoload, loads the google api lib
require_once('vendor/autoload.php');

putenv('GOOGLE_APPLICATION_CREDENTIALS=./credentials.json');

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes('https://www.googleapis.com/auth/fusiontables');

$service = new Google_Service_Fusiontables($client);

$tables = $service->table->listTable();
foreach ($tables as $table) {
    print "Table ID: " . $table->tableId . ", Name: " . $table->name . "\n";
}
