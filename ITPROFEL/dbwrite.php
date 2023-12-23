<!-- // Code Written by Rishi Tiwari
// Website:- https://tricksumo.com
// Reference:- https://www.w3schools.com/php/php_mysql_insert.asp
//
// -->

<?php

$host = "localhost";                  // host = localhost because the database is hosted on the same server where PHP files are hosted
$dbname = "id21695599_projdb";        // Database name
$username = "id21695599_projuser";    // Database username
$password = "Itprofelproject2023.";   // Database password

// Establish connection to MySQL database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection established successfully
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected to the MySQL database. ";
}

// Get date and time variables
date_default_timezone_set('Asia/Kolkata');  // for other timezones, refer:- https://www.php.net/manual/en/timezones.asia.php
$d = date("Y-m-d");
$t = date("H:i:s");

// If the UID sent by NodeMCU is not empty, then insert it into the MySQL database table
if (!empty($_POST['uid'])) {
    $uid = $_POST['uid'];

    // Update the table name here to itprofel_nodemcu2
    $sql = "INSERT INTO itprofel_nodemcu2 (uid, Date, Time) VALUES ('" . $uid . "', '" . $d . "', '" . $t . "')";

    if ($conn->query($sql) === TRUE) {
        echo "UID inserted in MySQL database table itprofel_nodemcu2.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close MySQL connection
$conn->close();

?>
