<?php
// $host = "localhost";                  // host = localhost because the database is hosted on the same server where PHP files are hosted
// $dbname = "id21695599_projdb";        // Database name
// $username = "root";    // Database username
// $password = "";   // Database password

$host = "localhost";                  // host = localhost because the database is hosted on the same server where PHP files are hosted
$dbname = "id21695599_projdb";        // Database name
$username = "";    // Database username
$password = "";   // Database password

// Establish connection to MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection established successfully
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "";
}

?>
