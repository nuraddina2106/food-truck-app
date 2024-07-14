<?php
// Connect to the database
include 'connect.php';

// Handle search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM food_trucks";
if ($search) {
    $sql .= " WHERE name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($sql);

$food_trucks = array();
while ($row = $result->fetch_assoc()) {
    $food_trucks[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($food_trucks);
?>
