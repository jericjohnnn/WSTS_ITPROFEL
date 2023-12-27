
<?php
session_start();

include 'dbconnect.php';

$aid = $_POST['aid'];
$name = $_POST['name'];
$prevstation = $_POST['prevstation'];

// Sanitize and validate aid to prevent SQL injection
$aid = mysqli_real_escape_string($conn, $aid);

$get_prevstation = "SELECT * FROM stations WHERE aid='$aid' && name='$prevstation'";
$result = mysqli_query($conn, $get_prevstation);
$prevstation = mysqli_fetch_assoc($result);

$prevstationinfo = $prevstation["name"];



$update_station_uid = "UPDATE stations SET aid = '7' WHERE name='$prevstationinfo'"; // Enclose $name in quotes
$result = mysqli_query($conn, $update_station_uid);


$update_station_uid = "UPDATE stations SET aid = $aid WHERE name='$name'"; // Enclose $name in quotes
$result = mysqli_query($conn, $update_station_uid);


if ($result) {
    header("Location: accountpage.php");
} else {
    echo"Error updating station status.";
}
?>
