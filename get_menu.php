<?php
// Connect to the database
include 'connect.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$truck_id = $_GET['truck_id'];
$sql = "SELECT menu_name, menu_desc, menu_price, menu_image FROM menu WHERE truck_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $truck_id);
$stmt->execute();
$result = $stmt->get_result();

$menu_items = array();
while ($row = $result->fetch_assoc()) {
    $menu_items[] = $row;
}

echo json_encode($menu_items);
$stmt->close();
$conn->close();
?>