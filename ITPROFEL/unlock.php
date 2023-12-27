
<?php
session_start();

include 'dbconnect.php';

$aid = $_POST['aid'];

// Sanitize and validate aid to prevent SQL injection
$aid = mysqli_real_escape_string($conn, $aid);

$update_status_info = "UPDATE stations SET status = 'Available' WHERE aid=$aid";
$result = mysqli_query($conn, $update_status_info);

if ($result) {
    header("Location: accountpage.php");
} else {
    echo"E rror updating station status.";
}
?>
