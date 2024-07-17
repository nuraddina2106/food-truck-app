<?php
// Connect to the database
include 'connect.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM food_trucks";
$result = $conn->query($sql);

$food_trucks = array();
while ($row = $result->fetch_assoc()) {
    $food_trucks[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($food_trucks);
?>
