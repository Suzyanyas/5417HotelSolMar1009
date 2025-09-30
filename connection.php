<?php
    $HOSTNAME = "localhost";
    $USERNAME = "root";
    $PASSWORD = "Suzy@2601";
    $DBNAME = "HotelDB";

    mysqli_report(MYSQLI_REPORT_STRICT);
    //Connect to database
    $conn = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DBNAME);

    //test connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>