<?php
session_start();

include 'dbconnect.php';

$uid = $_SESSION['user_id'];

$aid = $_POST['aid']; // sample would be: 1 jja
$name = $_POST['name']; // station 1, station you want to go
$prevstation1 = $_POST['prevstation']; // station 2, previous station or no station at all

$get_agent = "SELECT * FROM agents WHERE uid=$uid";
$result = mysqli_query($conn, $get_agent);
$agent = mysqli_fetch_assoc($result);

$agentaid = $agent['aid'];

$get_stationinfo = "SELECT * FROM stations JOIN agents ON stations.aid = agents.aid WHERE agents.aid = '$agentaid' ";
$result = mysqli_query($conn, $get_stationinfo);
$stationinfo = mysqli_fetch_assoc($result);

$get_station = "SELECT * FROM stations WHERE aid=" . $agent['aid'];
$hasStation = mysqli_query($conn, $get_station);

if (mysqli_num_rows($hasStation) === 0) {
    $stationinfogodz = "";
} else {
    $stationinfogodz = $stationinfo['status'];
}


$get_status = "SELECT * FROM stations WHERE name='$name'";
$result = mysqli_query($conn, $get_status);
$status = mysqli_fetch_assoc($result);

if ($prevstation1 != "you dont have a station") {
    if ($status['status'] == "Available" && $stationinfogodz != 'Unavailable') {
        $update_station_uid = "UPDATE stations SET aid = $aid WHERE name='$name'"; // Enclose $name in quotes
        $result = mysqli_query($conn, $update_station_uid);

        $update_station_uidz = "UPDATE stations SET aid = '7', status = 'Available' WHERE name='$prevstation1'";
        $result = mysqli_query($conn, $update_station_uidz);

    } else {
        header('Location: accountpage.php');
        // echo "NOT AVAILABLE PLEASE GO BACK";
    }

} else {
    if ($status['status'] == "Available" && $stationinfogodz != 'Unavailable') {
        $update_station_uid = "UPDATE stations SET aid = $aid WHERE name='$name'"; // Enclose $name in quotes
        $result = mysqli_query($conn, $update_station_uid);
    } else {
        header('Location: accountpage.php');
        // echo "NOT AVAILABLE PLEASE GO BACK";
    }

}

header('Location: accountpage.php');
?>