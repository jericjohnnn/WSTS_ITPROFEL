<?php
session_start();

include 'dbconnect.php';


$aid = $_POST['aid']; // sample would be: 1 jja
$name = $_POST['name']; // station 1, station you want to go
$prevstation1 = $_POST['prevstation']; // station 2, previous station or no station at all
// $status = $_POST['status'];

$get_status = "SELECT * FROM stations WHERE name='$name'";
$result = mysqli_query($conn, $get_status);
$status = mysqli_fetch_assoc($result);


        if ($prevstation1 != "you dont have a station") {
            if ($status['status'] == "Available") {
                $update_station_uid = "UPDATE stations SET aid = $aid WHERE name='$name'"; // Enclose $name in quotes
                $result = mysqli_query($conn, $update_station_uid);

                $update_station_uidz = "UPDATE stations SET aid = '7' WHERE name='$prevstation1'"; // Enclose $name in quotes
                $result = mysqli_query($conn, $update_station_uidz);
            } else {
                header('Location: accountpage.php');
            // echo "NOT AVAILABLE PLEASE GO BACK";
            }
            
        }else{
            if ($status['status'] == "Available") {
                $update_station_uid = "UPDATE stations SET aid = $aid WHERE name='$name'"; // Enclose $name in quotes
                $result = mysqli_query($conn, $update_station_uid);
            } else {
                header('Location: accountpage.php');
            // echo "NOT AVAILABLE PLEASE GO BACK";
            }
           
        }

        // GOTO ACCOUNT PAGE AFTER CODE IS COMPLE EXECUTING
        header("Location: accountpage.php");



?>