<?php
include 'connect.php';

// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Initialize message
$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

// Retrieve food truck ID
$truck_id = isset($_GET['truck_id']) ? intval($_GET['truck_id']) : 0;

// Fetch menu items for the selected food truck
$stmt = $conn->prepare("SELECT * FROM menus WHERE truck_id = ?");
$stmt->bind_param("i", $truck_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch food truck details for the header
$food_truck_stmt = $conn->prepare("SELECT name FROM food_trucks WHERE truck_id = ?");
$food_truck_stmt->bind_param("i", $truck_id);
$food_truck_stmt->execute();
$food_truck_result = $food_truck_stmt->get_result();
$food_truck = $food_truck_result->fetch_assoc();
$food_truck_stmt->close();

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management</title>
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
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }
        .menu {
            background: #fff;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
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
        .container {
            padding: 20px;
        }
        .menu-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .menu-table th, .menu-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .menu-table th {
            background: #007bff;
            color: #fff;
        }
        .button-edit, .button-delete {
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
                    const confirmation = confirm('Are you sure you want to delete this menu item?');
                    if (!confirmation) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="header">
        <h1>Menu Management</h1>
        <a href="food_trucks.php">Back to Food Trucks</a>
    </div>

    <div class="menu">
        <h2>Menu for <?php echo htmlspecialchars($food_truck['name']); ?></h2>
        <a href="add_menu.php?truck_id=<?php echo $truck_id; ?>" class="button">+ New Menu Item</a>
    </div>

    <div class="container">
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?><a href="#" class="close">&times;</a></div>
        <?php } ?>

        <table class="menu-table">
            <thead>
                <tr>
                    <th>Menu ID</th>
                    <th>Menu Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['menu_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['menu_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['menu_desc']); ?></td>
                            <td><?php echo htmlspecialchars($row['menu_price']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($row['menu_image']); ?>" alt="Image" width="100"></td>
                            <td>
                                <a href="edit_menu.php?menu_id=<?php echo $row['menu_id']; ?>" class="button button-edit">Edit</a>
                                <form method="post" action="delete_menu.php" style="display:inline;">
                                    <input type="hidden" name="menu_id" value="<?php echo $row['menu_id']; ?>">
                                    <button type="submit" class="button button-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No menu items found for this food truck.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
