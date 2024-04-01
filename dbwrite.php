<?php

include 'dbconnect.php'; // connect to database

$station_name = 'station1'; // you can change this to any station. SINCE WE ONLY HAVE 1 SCANNER, STATION 1 WAS THE ONLY ONE ASSIGNED

$get_stationinfo = "SELECT * FROM stations WHERE name = '$station_name'";
$result = mysqli_query($conn, $get_stationinfo);
$stationinfo = mysqli_fetch_assoc($result);

$stationaid = $stationinfo['aid'];

// selects cid of agent who is assigned to station 1: the only station available for now
$get_agent = "SELECT cid FROM agents WHERE aid = '$stationaid'";
$result = mysqli_query($conn, $get_agent);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cid = $row['cid'];
    echo $cid; // Output the UID for the NodeMCU to receive
} else {
    echo "No CID found";
}

$conn->close();

?>
