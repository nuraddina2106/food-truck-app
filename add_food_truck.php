<?php
// add_food_truck.php

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
$message_type = ''; // To store the type of message (success or error)
$uploadDir = 'uploads/';

// Create the uploads directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); // Create directory with proper permissions
}

// Handle add operation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] === 'Add') {
    $business_type = $_POST['business_type'];
    $name = $_POST['name'];
    $operator_name = $_POST['operator_name'];
    $address = $_POST['address'];
    $business_hours = $_POST['business_hours'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Check if address or coordinates already exist
    $checkSql = "SELECT * FROM food_trucks WHERE address = ? OR (latitude = ? AND longitude = ?)";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("sdd", $address, $latitude, $longitude);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "A food truck with the same address or coordinates already exists.";
        $message_type = 'error';
    } else {
        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileSize = $_FILES['image']['size'];
            $fileType = $_FILES['image']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Define allowed file extensions and upload directory
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = $uploadDir . $newFileName;

            if (in_array($fileExtension, $allowedExtensions)) {
                if (move_uploaded_file($fileTmpPath, $uploadFileDir)) {
                    $image = $uploadFileDir;
                } else {
                    $message = "Error moving the uploaded image.";
                    $message_type = 'error';
                }
            } else {
                $message = "Unsupported file type.";
                $message_type = 'error';
            }
        } else {
            $message = "No image uploaded or there was an error.";
            $message_type = 'error';
        }

        if ($message === '') {
            // Insert the food truck details into the database
            $sql = "INSERT INTO food_trucks (business_type, name, operator_name, address, business_hours, latitude, longitude, image)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssddds", $business_type, $name, $operator_name, $address, $business_hours, $latitude, $longitude, $image);

            if ($stmt->execute()) {
                $message = "Food truck added successfully.";
                $message_type = 'success';
            } else {
                $message = "Error: " . $conn->error;
                $message_type = 'error';
            }
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Truck</title>
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
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-container .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .form-container .form-row label {
            flex-basis: 48%;
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-container .form-row input, .form-container .form-row textarea {
            flex-basis: 48%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        .form-container button {
            display: block;
            width: 100%;
            padding: 12px 20px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            background-color: #007bff;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Add New Food Truck</h1>
        <a href="food_trucks.php">Back to Food Trucks List</a>
    </div>

    <div class="form-container">
        <?php if (isset($message)) { ?>
            <div class="message <?php echo $message_type; ?>">
                <p><?php echo $message; ?></p>
            </div>
        <?php } ?>

        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="Add">

            <div class="form-row">
                <label for="business_type">Business Type</label>
                <input type="text" id="business_type" name="business_type" required>
            </div>

            <div class="form-row">
                <label for="name">Food Truck Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-row">
                <label for="operator_name">Operator Name</label>
                <input type="text" id="operator_name" name="operator_name" required>
            </div>

            <div class="form-row">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            </div>

            <div class="form-row">
                <label for="business_hours">Business Hours</label>
                <input type="text" id="business_hours" name="business_hours" required>
            </div>

            <div class="form-row">
                <label for="latitude">Latitude</label>
                <input type="text" id="latitude" name="latitude" placeholder="e.g., 30.200000" required>
            </div>

            <div class="form-row">
                <label for="longitude">Longitude</label>
                <input type="text" id="longitude" name="longitude" placeholder="e.g., 100.199777" required>
            </div>

            <div class="form-row">
                <label for="image">Image (JPG, JPEG, PNG, GIF)</label>
                <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png, .gif" required>
            </div>

            <button type="submit">Add Food Truck</button>
        </form>
    </div>
</body>
</html>
