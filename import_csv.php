<?php

// composer autoload
require_once('vendor/autoload.php');

putenv('GOOGLE_APPLICATION_CREDENTIALS=./credentials.json');

$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes('https://www.googleapis.com/auth/fusiontables');

$service = new Google_Service_Fusiontables($client);

//delete all previously created tables called "waste_collection_zurich_2017"
$tables = $service->table->listTable();
foreach ($tables as $table) {
    if ($table->name === "waste_collection_zurich_2017") {
        $service->table->delete($table->tableId);
        echo "Successfully deleted the old table " . $table->tableId . "\n\n";
    }
}

$table = new Google_Service_Fusiontables_Table();
$table->setName('waste_collection_zurich_2017');

//set required option isExportable
$table->setIsExportable(true);

//set column(s)
$table->setColumns(array(
        new Google_Service_Fusiontables_Column(array('type'=>'NUMBER',
            'name'=>'zip')),
        new Google_Service_Fusiontables_Column(array('type'=>'DATETIME',
            'name'=>'pickup_date'))
    )
);

$new_table = $service->table->insert($table);

// the importTable and importRows calls of the Google API Client Library for PHP seem to be broken, at least the upload functionality is not correctly implemented
// therefore I will simply parse the CSV in PHP and use SQL to insert the data in Google Fusion Tables.

// read the CSV file
$rows = array_map('str_getcsv', file('data/waste_collection_zurich_2017.csv'));

array_walk($rows, function(&$row) use ($rows) {
    $row = array_combine($rows[0], $row);
});
array_shift($rows); // remove column header


/*
 * From the Google Fusion Tables docs:
 * - Google enforces a rate limit to 30 write requests per minute per table (see https://developers.google.com/fusiontables/docs/v1/using#quota)
 * - You can send up to 500 INSERT statements together in one requests, as long as the requests as less then 1MB and it updates fewer than 10,000 cells (see https://developers.google.com/fusiontables/docs/v1/using#insertRow)
 */
$sqlBatch = "";
$sqlNum = 0;
foreach ($rows as $row) {
    $insertQuery = "INSERT INTO " . $new_table->tableId . " (zip, pickup_date) VALUES (" . $row["zip"] . ", '" . $row['pickup_date'] . "'); ";
    echo "*SQL*: ".$insertQuery."\n";
    $sqlBatch .= $insertQuery;
    $sqlNum++;
    if ($sqlNum >= 50) {
        $service->query->sql($sqlBatch);
        $sqlBatch = "";
        $sqlNum = 0;
    }
}
if (!empty($sqlBatch)) {
    $service->query->sql($sqlBatch);
}

$selectQuery = "select count() from " . $new_table->tableId;
$result = $service->query->sql($selectQuery);
print_r($result->getRows());
echo "Rows in CSV file: " . count($rows) . "\n";


