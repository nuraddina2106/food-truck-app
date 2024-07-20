<?php
// Connect to the database
include 'connect.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM menus";
$result = $conn->query($sql);

$menu_items = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
} else {
    echo "No menu items found";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($menu_items);
?>
