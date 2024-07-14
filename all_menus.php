<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Include database connection
include 'connect.php';

// Initialize variables
$message = '';

// Handle deletion of a menu item
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    $stmt = $conn->prepare("DELETE FROM menus WHERE menu_id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "Menu item deleted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve all menu items along with truck names from the database
$sql = "SELECT menus.*, food_trucks.name AS truck_name
        FROM menus
        LEFT JOIN food_trucks ON menus.truck_id = food_trucks.truck_id";
$result = $conn->query($sql);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f0f0;
            transition: margin-left 0.3s;
        }
        .header {
            background: #007bff;
            color: #fff;
            padding: 10px;
            display: flex;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            box-sizing: border-box;
            z-index: 3; /* Higher z-index than sidenav */
        }
        .header .menu-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            flex-grow: 1;
        }
        .header .logout-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
            margin-left: auto;
        }
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #333;
            color: #fff;
            overflow-x: hidden;
            transition: 0.3s;
            padding-top: 60px;
            z-index: 2; /* Lower z-index than header */
        }
        .sidenav .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            font-size: 18px;
            font-weight: bold;
            color: #fff;
        }
        .sidenav .closebtn {
            font-size: 36px;
            cursor: pointer;
            color: #fff;
            text-decoration: none;
        }
        .sidenav a {
            padding: 8px 16px;
            text-decoration: none;
            font-size: 18px;
            color: #f0f0f0;
            display: block;
            transition: 0.3s;
        }
        .sidenav a:hover {
            color: #007bff;
        }
        .sidenav.show {
            left: 0;
        }
        #main {
            transition: margin-left 0.3s;
            padding: 20px;
            margin-top: 60px;
        }
        #main.shift {
            margin-left: 250px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .message {
            padding: 10px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
            position: relative;
        }
        .message .close {
            position: absolute;
            top: 10px;
            right: 10px;
            color: #155724;
            text-decoration: none;
            font-size: 20px;
            font-weight: bold;
        }
        .edit-button, .delete-button {
            text-decoration: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
        }
        .edit-button {
            background-color: #007bff;
        }
        .edit-button:hover {
            background-color: #0056b3;
        }
        .delete-button {
            background-color: #dc3545;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
        .menu {
            background: #fff;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 0px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .delete-button {
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div id="mySidenav" class="sidenav">
        <div class="drawer-header">
            <label>Menu</label>
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        </div>
        <a href="index.php">Dashboard</a>
        <a href="food_trucks.php">Food Truck Management</a>
        <a href="all_menus.php"><b>All Menus</b></a>
    </div>

    <div id="main">
        <div class="header">
            <button class="menu-btn openbtn" onclick="toggleNav()">&#9776;</button>
            <h1>All Menus</h1>
            <a href="#" class="logout-btn" onclick="confirmLogout()">Logout</a>
        </div>

        <div class="menu">
            <h2>Menu List</h2>
        </div>

        <div class="container">
            <?php if (!empty($message)) { ?>
                <div class="message"><?php echo $message; ?><a href="#" class="close">&times;</a></div>
            <?php } ?>

            <table>
                <thead>
                    <tr>
                        <th>Menu ID</th>
                        <th>Menu Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Truck Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['menu_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['menu_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['menu_desc']); ?></td>
                                <td><?php echo htmlspecialchars($row['menu_price']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($row['menu_image']); ?>" alt="Menu Image" width="100"></td>
                                <td><?php echo htmlspecialchars($row['truck_name']); ?></td>
                                <td>
                                    <a href="edit_menu.php?menu_id=<?php echo htmlspecialchars($row['menu_id']); ?>" class="edit-button">Edit</a>
                                    <a href="?delete_id=<?php echo htmlspecialchars($row['menu_id']); ?>" onclick="return confirmDelete()" class="delete-button">Delete</a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="7">No menu items found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleNav() {
            var sidenav = document.getElementById("mySidenav");
            var main = document.getElementById("main");

            if (sidenav.classList.contains("show")) {
                sidenav.classList.remove("show");
                main.classList.remove("shift");
            } else {
                sidenav.classList.add("show");
                main.classList.add("shift");
            }
        }

        function closeNav() {
            var sidenav = document.getElementById("mySidenav");
            var main = document.getElementById("main");
            sidenav.classList.remove("show");
            main.classList.remove("shift");
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this menu item?");
        }

        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = 'logout.php';
            }
        }
    </script>
</body>
</html>
