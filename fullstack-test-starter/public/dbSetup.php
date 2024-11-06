<?php
// Database credentials
$host = 'junction.proxy.rlwy.net';
$dbname = 'railway';
$username = 'root';
$password = 'lHdglQmIYwWFBDpNvtLqABFfbBcUHVmU';
$port = '49510';

// Create a new mysqli instance
$mysqli = new mysqli($host, $username, $password, $dbname, $port);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Path to the SQL file
$filePath = 'C:\\Users\\Administrator\\Downloads\\scandiwebDb.sql';

// Read the SQL file
$sql = file_get_contents($filePath);
if ($sql === false) {
    die("Unable to read SQL file.");
}

// Execute the SQL commands
if ($mysqli->multi_query($sql)) {
    do {
        // Store result to clear any stored results in case of multiple queries
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->next_result());
    echo "SQL file imported successfully!";
} else {
    echo "Error executing SQL: " . $mysqli->error;
}

// Close the connection
$mysqli->close();
