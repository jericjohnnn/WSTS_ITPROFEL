<?php

include 'dbconnect.php';

if (!empty($_POST['swiped_id'])) {

    $cid = $_POST['swiped_id'];

    $get_agent = "SELECT * FROM agents WHERE cid=$cid";
    $result = mysqli_query($conn, $get_agent);
    $agent = mysqli_fetch_assoc($result);

    $aid = $agent['aid'];

    $update_status_info = "UPDATE stations SET status = 'Available' WHERE aid='$aid'";
    $result = mysqli_query($conn, $update_status_info);

}

$conn->close();

?>
