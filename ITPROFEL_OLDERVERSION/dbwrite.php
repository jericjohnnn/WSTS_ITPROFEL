<?php

include 'dbconnect.php'; // connect to database

$sql = "SELECT uid FROM test WHERE id = 1"; // Adjust the WHERE clause as needed
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $uid = $row['uid'];
    echo $uid; // Output the UID for the NodeMCU to receive
} else {
    echo "No UID found";
}

$conn->close();

?>
