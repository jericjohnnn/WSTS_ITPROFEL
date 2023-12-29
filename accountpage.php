<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>

</head>

<body>
    <?php

    include 'dbconnect.php';

    $uid = $_SESSION['user_id'];

    $get_agent = "SELECT * FROM agents WHERE uid=$uid";
    $result = mysqli_query($conn, $get_agent);
    $agent = mysqli_fetch_assoc($result);

    $get_station = "SELECT * FROM stations WHERE aid=" . $agent['aid'];
    $result = mysqli_query($conn, $get_station);
    $station = mysqli_fetch_assoc($result);

    $hasStation = mysqli_query($conn, $get_station);

    $get_stationinfo = "SELECT * FROM stations JOIN agents ON stations.aid = agents.aid";
    $result = mysqli_query($conn, $get_stationinfo);
    $stationinfo = mysqli_fetch_assoc($result);

    ?>

    <h1>Welcome back,
        <?php echo $agent['firstname'] . " " . $agent['lastname']; ?>
    </h1>
    <div class="account-info">
        <h2>Your Station</h2>
        <p>
            <?php

            if (mysqli_num_rows($hasStation) === 0) {
                $stationname1 = array("you dont have a station");
                $stationname = $stationname1[0];
                echo $stationname;
            } else {
                $stationname = $station['name'];
                echo $stationname;
            }

            ?>
        </p>
    </div>
    <a href="logout.php">Logout</a>

                                                    <!-- LOCK BUTTON -->
    <!-- <br>
    <form method="post" action="lock.php">
        <input type="hidden" name="aid" value="<?php echo $agent['aid']; ?>"> <button type="submit">Lock</button>
    </form>
    <br> -->

                                                    <!-- UNLOCK BUTTON -->
    <!-- <form method="post" action="unlock.php">
        <input type="hidden" name="aid" value="<?php echo $agent['aid']; ?>"> <button type="submit">Unlock</button>
    </form> -->


    <div class="stations">

        <?php


        $get_stationinfo = "SELECT * FROM stations JOIN agents ON stations.aid = agents.aid";
        $result = mysqli_query($conn, $get_stationinfo);

        while ($row = mysqli_fetch_assoc($result)) {
            // Access and display information from both tables:
            echo "<br>";
            
            $station_name = $row["name"];
            $stationaid = $row['aid'];
            echo "Station Name: " . $row['name'] . "<br>";
            echo "Agent status: " . $row['status'] . "<br>";
            echo "Agent name: " . $row['firstname'] . "<br>";

            if ($row['status'] == "Available") {
                // BUTTON FOR USE STATION
                if ($agent["aid"] == $stationaid && $station['name'] == $station_name) {
                    echo "";
                } else {
                    echo '<form method="post" action="usestation.php">';
                    echo '<input type="hidden" name="aid" value="' . $agent["aid"] . '"> ';
                    echo '<input type="hidden" name="name" value="' . $station_name . '"> ';
                    echo '<input type="hidden" name="prevstation" value="' . $stationname . '"> ';
                    echo '<button type="submit">Use Station</button>';
                    echo '</form>';
                }

            } else {
                echo "";
            }

        }
        ?>

    </div>

    </body>

</html>