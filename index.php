<?php
// index.php

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Database connection
require 'connect.php'; // Make sure to include your database connection file

// Fetch food truck data
$sql = "SELECT truck_id, name, address FROM food_trucks";
$result = $conn->query($sql);
$foodTrucks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foodTrucks[] = $row;
    }
}
$numFoodTrucks = count($foodTrucks);

// Fetch menu data with truck names
$sql = "
    SELECT m.menu_id, m.menu_name, m.menu_price, m.menu_image, f.name AS truck_name
    FROM menus m
    LEFT JOIN food_trucks f ON m.truck_id = f.truck_id
";
$result = $conn->query($sql);
$menus = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menus[] = $row;
    }
}
$numMenus = count($menus);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            z-index: 3; /* Ensure header is above the sidenav */
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            flex-grow: 1;
        }
        .header .menu-btn {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
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
            z-index: 2; /* Ensure sidenav is below the header */
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

        #main.shift {
            margin-left: 250px;
        }
        #main {
            transition: margin-left 0.3s;
            padding: 0px;
            margin-top: 0px; /* Adjust as needed */
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 40px); /* Adjust to fit new margin-top */
            text-align: center;
        }

        .row {
            display: flex;
            gap: 20px; /* Space between card and table */
            justify-content: center; /* Center the content horizontally */
        }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 200px; /* Make the card square */
            height: 200px; /* Make the card square */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }
        .card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px; /* Space between icon and heading */
        }
        .food-truck-icon {
            font-size: 48px; /* Adjust icon size */
            color: #007bff;
        }
        .text-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .text-content h3 {
            margin: 0;
            font-size: 18px;
        }
        .text-content p {
            font-size: 16px;
        }
        .food-truck-list,
        .menu-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            width: 100%;
            max-width: 800px; /* Maximum width for table */
            margin: 0 auto; /* Center the table */
        }
        .food-truck-list table,
        .menu-list table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .food-truck-list table th,
        .menu-list table th,
        .food-truck-list table td,
        .menu-list table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .food-truck-list table th,
        .menu-list table th {
            background-color: #007bff;
            color: white;
        }
        .food-truck-list table tr:nth-child(even),
        .menu-list table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .food-truck-list .edit-btn,
        .menu-list .edit-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .food-truck-list .edit-btn:hover,
        .menu-list .edit-btn:hover {
            background-color: #0056b3;
        }
        .menu-list img {
            max-width: 100px; /* Adjust image size */
            height: auto;
        }
    </style>
</head>
<body>
    <div id="mySidenav" class="sidenav">
        <div class="drawer-header">
            <label>Menu</label>
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        </div>
        <a href="index.php"><b>Dashboard</b></a>
        <a href="food_trucks.php">Food Truck Management</a>
        <a href="all_menus.php">All Menus</a>
    </div>

    <div id="main">
        <div class="header">
            <button class="menu-btn openbtn" onclick="toggleNav()">&#9776;</button>
            <h1>Admin Dashboard</h1>
            <a href="#" class="logout-btn" onclick="confirmLogout()">Logout</a>
        </div>

        <div class="container">
            <br><br><br><br><h2>Welcome to the Admin Dashboard</h2>
            <p>Effortlessly manage and update food trucks and menus at your fingertips.</p>
            <br><br><br>
            <div class="row">
                <!-- Food Trucks Card and Table -->
                <div class="card">
                    <div class="card-content">
                        <div class="icon-container">
                            <i class="fas fa-truck food-truck-icon"></i>
                        </div>
                        <div class="text-content">
                            <h3>Food Trucks Overview</h3>
                            <p>Total Food Trucks: <?php echo $numFoodTrucks; ?></p>
                        </div>
                    </div>
                </div>

                <div class="food-truck-list">
                    <h3>Food Trucks List</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($foodTrucks as $truck): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($truck['truck_id']); ?></td>
                                    <td><?php echo htmlspecialchars($truck['name']); ?></td>
                                    <td><?php echo htmlspecialchars($truck['address']); ?></td>
                                    <td><a href="edit_food_truck.php?truck_id=<?php echo htmlspecialchars($truck['truck_id']); ?>" class="edit-btn">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br><br><br>
            <div class="row">
                <!-- Menus Card and Table -->
                <div class="card">
                    <div class="card-content">
                        <div class="icon-container">
                            <i class="fas fa-utensils food-truck-icon"></i>
                        </div>
                        <div class="text-content">
                            <h3>Menu Overview</h3>
                            <p>Total Menus: <?php echo $numMenus; ?></p>
                        </div>
                    </div>
                </div>

                <div class="menu-list">
                    <h3>Menu List</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Truck Name</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menus as $menu): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($menu['menu_id']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['menu_name']); ?></td>
                                    <td><?php echo htmlspecialchars($menu['menu_price']); ?></td>
                                    <td><img src="<?php echo htmlspecialchars($menu['menu_image']); ?>" alt="<?php echo htmlspecialchars($menu['menu_name']); ?>"></td>
                                    <td><?php echo htmlspecialchars($menu['truck_name']); ?></td>
                                    <td><a href="edit_menu.php?menu_id=<?php echo htmlspecialchars($menu['menu_id']); ?>" class="edit-btn">Edit</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
    <br><br>                      
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
            document.getElementById("mySidenav").classList.remove("show");
            document.getElementById("main").classList.remove("shift");
        }

        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '?logout=true';
            }
        }
    </script>
</body>
</html>
