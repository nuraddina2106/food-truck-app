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

// Handle form submission for adding a new menu item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_name = $_POST['menu_name'];
    $menu_desc = $_POST['menu_desc'];
    $menu_price = $_POST['menu_price'];
    $truck_id = $_POST['truck_id'];

    // Handle image upload
    $menu_image = ''; // Default to empty
    if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] == UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['menu_image']['tmp_name'];
        $image_name = basename($_FILES['menu_image']['name']);
        $image_dir = 'uploads/'; // Directory where images will be stored
        $menu_image = $image_dir . $image_name;

        // Move the uploaded file to the uploads directory
        if (!move_uploaded_file($image_tmp_name, $menu_image)) {
            $message = "Error: Unable to upload image.";
        }
    }

    // Validate input
    if (empty($menu_name) || empty($menu_desc) || empty($menu_price) || empty($truck_id)) {
        $message = "All fields are required.";
    } else {
        // Prepare an SQL statement for insertion
        $stmt = $conn->prepare("INSERT INTO menus (menu_name, menu_desc, menu_price, menu_image, truck_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $menu_name, $menu_desc, $menu_price, $menu_image, $truck_id);

        if ($stmt->execute()) {
            // Redirect with success message
            header("Location: menus.php?truck_id=$truck_id&message=Menu item added successfully.");
            exit();
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Retrieve truck_id from GET request
$truck_id = isset($_GET['truck_id']) ? intval($_GET['truck_id']) : 0;

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu Item</title>
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
        .main-container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-container {
            flex-basis: 30%;
            text-align: center;
        }
        .profile-container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd;
        }
        .profile-container h2 {
            font-size: 20px;
            margin-top: 10px;
        }
        .form-container {
            flex-basis: 70%;
        }
        .form-row {
            margin-bottom: 15px;
        }
        .form-row label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-row input, .form-row textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .form-row input[type="number"] {
            -moz-appearance: textfield;
        }
        .form-row input[type="number"]::-webkit-inner-spin-button,
        .form-row input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .form-row button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        .form-row button:hover {
            background-color: #0056b3;
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
        });
    </script>
</head>
<body>
    <div class="header">
        <h1>Add Menu Item</h1>
        <a href="menus.php?truck_id=<?php echo htmlspecialchars($truck_id); ?>">Back to Menu List</a>
    </div>

    <div class="main-container">
        <div class="profile-container">
            <img src="default_image.png" alt="Menu Item Image">
            <h2>Menu Name</h2>
        </div>

        <div class="form-container">
            <?php if (!empty($message)) { ?>
                <div class="message"><?php echo $message; ?><a href="#" class="close">&times;</a></div>
            <?php } ?>

            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="truck_id" value="<?php echo htmlspecialchars($truck_id); ?>">
                
                <div class="form-row">
                    <label for="menu_name">Menu Name</label>
                    <input type="text" id="menu_name" name="menu_name" required>
                </div>

                <div class="form-row">
                    <label for="menu_desc">Description</label>
                    <input type="text" id="menu_desc" name="menu_desc" required>
                </div>

                <div class="form-row">
                    <label for="menu_price">Price</label>
                    <input type="number" id="menu_price" name="menu_price" step="0.01" required>
                </div>

                <div class="form-row">
                    <label for="menu_image">Upload Image</label>
                    <input type="file" id="menu_image" name="menu_image" required>
                </div>

                <div class="form-row">
                    <button type="submit">Add Menu Item</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
