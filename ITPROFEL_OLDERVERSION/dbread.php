<!-- // Code Written by Rishi Tiwari
// Website:- https://tricksumo.com
// Reference:- https://www.w3schools.com/php/php_mysql_select.asp
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
    echo "Connected to MySQL database. <br>";
}

// Select values from MySQL database table

$sql = "SELECT id, uid, date, time FROM itprofel_nodemcu2";  // Update your tablename here to itprofel_nodemcu2

$result = $conn->query($sql);

echo "<center>";

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<strong> Id:</strong> " . $row["id"] . " &nbsp <strong>UID:</strong> " . $row["uid"] . " &nbsp <strong>Date:</strong> " . $row["date"] . " &nbsp <strong>Time:</strong>" . $row["time"] . "<p>";
    }
} else {
    echo "0 results";
}

echo "</center>";

$conn->close();

?>
