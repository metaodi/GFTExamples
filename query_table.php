<?php

// composer autoload, loads the google api lib
require_once('vendor/autoload.php');

putenv('GOOGLE_APPLICATION_CREDENTIALS=./credentials.json');

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes('https://www.googleapis.com/auth/fusiontables');

function combineColumnsAndRows($result) {
    // use column names to create associative arrays in $rows
    $columns = $result->getColumns();
    $rows = $result->getRows();
    array_walk($rows, function(&$row) use ($columns) {
        $row = array_combine($columns, $row);
    });
    return $rows;
}

$service = new Google_Service_Fusiontables($client);

$insertQuery = "insert into 1KY8mVr3zSNvj8-MnLN9o34aORtuKYRv_dn_o0HR9 (columnName, otherName) values ('hello', 1)";
echo "*SQL*: ".$insertQuery."\n";
$result = $service->query->sql($insertQuery);
$selectQuery = "select count() from 1KY8mVr3zSNvj8-MnLN9o34aORtuKYRv_dn_o0HR9";
$result = $service->query->sql($selectQuery);
$rows = $result->getRows();
echo "Count of records in table: " . $rows[0][0] . "\n\n";

$selectQuery = "select * from 1KY8mVr3zSNvj8-MnLN9o34aORtuKYRv_dn_o0HR9 where rowid = '1'";
echo "BEFORE UPDATE: ".$selectQuery."\n";
$result = $service->query->sql($selectQuery);
print_r(combineColumnsAndRows($result));

$number = rand(1,100);
$updateQuery = "update 1KY8mVr3zSNvj8-MnLN9o34aORtuKYRv_dn_o0HR9 set columnName = '".$number."' where rowid = '1'";
echo "*SQL*: ".$updateQuery."\n";
$result = $service->query->sql($updateQuery);

$selectQuery = "select * from 1KY8mVr3zSNvj8-MnLN9o34aORtuKYRv_dn_o0HR9 where rowid = '1'";
echo "AFTER UPDATE: ".$selectQuery."\n";
$result = $service->query->sql($selectQuery);
print_r(combineColumnsAndRows($result));
