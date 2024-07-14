<?php
// food_trucks.php

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: login.php'); // Redirect to login page
    exit();
}

// Include database connection
include 'connect.php';

// Initialize message
$message = isset($_GET['message']) ? $_GET['message'] : '';

// Handle delete operation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'Delete') {
    $truck_id = intval($_POST['truck_id']); // Sanitize truck_id

    // Use prepared statement for deletion
    $stmt = $conn->prepare("DELETE FROM food_trucks WHERE truck_id = ?");
    $stmt->bind_param("i", $truck_id);

    if ($stmt->execute()) {
        $message = "Food truck deleted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch food trucks from the database
$sql = "SELECT * FROM food_trucks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Truck Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f0f0;
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
            z-index: 1001; /* Ensure header is above sidenav */
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
        .menu {
            background: #fff;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .menu h2 {
            margin: 0;
        }
        .menu .button {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            background: #007bff;
            border-radius: 5px;
            text-align: center;
            font-size: 16px;
        }
        .menu .button:hover {
            background: #0056b3;
        }

        .food-truck-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .food-truck-table th, .food-truck-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .food-truck-table th {
            background: #007bff;
            color: #fff;
        }
        .button-edit, .button-delete, .button-edit-menu {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            text-align: center;
        }
        .button-edit:hover {
            background-color: #0056b3;
        }
        .button-delete {
            background-color: #dc3545;
        }
        .button-delete:hover {
            background-color: #c82333;
        }
        .button-edit-menu {
            background-color: #ffc107;
        }
        .button-edit-menu:hover {
            background-color: #e0a800;
        }
        form {
            display: inline;
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
            z-index: 1000; /* Ensure sidenav is below header */
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
        .sidenav.show {
            left: 0;
        }
        #main {
            transition: margin-left 0.3s;
            padding: 20px;
        }
        #main.shift {
            margin-left: 250px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('.message .close');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.style.display = 'none';
                });
            });

            const deleteForms = document.querySelectorAll('form[method="post"]');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    const confirmation = confirm('Are you sure you want to delete this food truck?');
                    if (!confirmation) {
                        event.preventDefault(); // Prevent form submission if not confirmed
                    }
                });
            });
        });

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
                document.querySelector('.logout-btn').closest('form').submit();
            }
        }
    </script>
</head>
<body>

    <!-- Side Navigation -->
    <div id="mySidenav" class="sidenav">
        <div class="drawer-header">
            <span>Menu</span>
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        </div>
        <a href="index.php">Dashboard</a>
        <a href="food_trucks.php"><b>Food Truck Management</b></a>
        <a href="all_menus.php">All Menus</a>
    </div>

    <!-- Main Content -->
    <div id="main">
        <div class="header">
            <button class="menu-btn openbtn" onclick="toggleNav()">&#9776;</button>
            <h1>Food Truck Management</h1>
            <a href="#" class="logout-btn" onclick="confirmLogout()">Logout</a>
        </div>

        <div class="menu">
            <h2>Food Trucks List</h2>
            <a href="add_food_truck.php" class="button">+ New Food Truck</a>
        </div>

        <div class="container">
            <?php if (!empty($message)) { ?>
                <div class="message"><?php echo $message; ?><a href="#" class="close">&times;</a></div>
            <?php } ?>

            <table class="food-truck-table">
                <thead>
                    <tr>
                        <th>Truck ID</th>
                        <th>Business Type</th>
                        <th>Name</th>
                        <th>Operator Name</th>
                        <th>Address</th>
                        <th>Business Hours</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['truck_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['business_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['operator_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['business_hours']); ?></td>
                                <td><?php echo htmlspecialchars($row['latitude']); ?></td>
                                <td><?php echo htmlspecialchars($row['longitude']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Image" width="100"></td>
                                <td>
                                    <a href="edit_food_truck.php?truck_id=<?php echo $row['truck_id']; ?>" class="button button-edit">Edit</a>
                                    <form method="post" action="" style="display:inline;">
                                        <input type="hidden" name="action" value="Delete">
                                        <input type="hidden" name="truck_id" value="<?php echo $row['truck_id']; ?>">
                                        <button type="submit" class="button button-delete">Delete</button>
                                    </form>
                                    <a href="menus.php?truck_id=<?php echo $row['truck_id']; ?>" class="button button-edit-menu">View Menu</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">No food trucks found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
