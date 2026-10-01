<?php
    $servername = "mysql_db";
    $username = "root";
    $password = "root";
    $db = "db_food_festive";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>