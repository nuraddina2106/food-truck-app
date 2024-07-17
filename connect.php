<?php

header('content-type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "food_truck_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
