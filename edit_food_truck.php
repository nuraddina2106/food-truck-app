<?php
// edit_food_truck.php

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

// Handle form submission for editing food truck
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $truck_id = $_POST['truck_id'];
    $business_type = $_POST['business_type'];
    $name = $_POST['name'];
    $operator_name = $_POST['operator_name'];
    $address = $_POST['address'];
    $business_hours = $_POST['business_hours'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Handle image upload
    $image_path = $food_truck['image']; // Default to current image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_dir = 'uploads/'; // Directory where images will be stored
        $image_path = $image_dir . $image_name;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // File uploaded successfully
        } else {
            $message = "Error: Unable to upload image.";
        }
    }

    // Prepare an SQL statement for updating
    $stmt = $conn->prepare("UPDATE food_trucks SET business_type=?, name=?, operator_name=?, address=?, 
            business_hours=?, latitude=?, longitude=?, image=? WHERE truck_id=?");
    $stmt->bind_param("ssssssssi", $business_type, $name, $operator_name, $address, $business_hours, $latitude, $longitude, $image_path, $truck_id);

    if ($stmt->execute()) {
        // Pass success message via query string
        header("Location: food_trucks.php?message=Food truck updated successfully."); // Redirect with message
        exit();
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch the food truck data to be edited
$truck_id = isset($_GET['truck_id']) ? intval($_GET['truck_id']) : 0;

// Prepare an SQL statement for selecting
$stmt = $conn->prepare("SELECT * FROM food_trucks WHERE truck_id=?");
$stmt->bind_param("i", $truck_id);
$stmt->execute();
$result = $stmt->get_result();
$food_truck = $result->fetch_assoc();
$stmt->close();

if (!$food_truck) {
    header('Location: food_trucks.php'); // Redirect if the truck_id is invalid
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Food Truck</title>
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
            display: flex;
            max-width: 1000px;
            margin: 20px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .profile-container {
            flex-basis: 30%;
            padding: 20px;
            border-right: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .profile-container img {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
        }
        .profile-container h2 {
            font-size: 20px;
            margin: 15px 0 0 0;
            text-align: center;
        }
        .form-container {
            flex-basis: 70%;
            padding: 20px;
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
        .image-preview {
            margin: 10px 0;
        }
        .image-preview img {
            max-width: 200px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Edit Food Truck</h1>
        <a href="food_trucks.php">Back to Food Trucks List</a>
    </div>

    <div class="main-container">
        <div class="profile-container">
            <?php if ($food_truck['image']): ?>
                <img src="<?php echo htmlspecialchars($food_truck['image']); ?>" alt="Food Truck Image">
            <?php else: ?>
                <img src="default_image.png" alt="Default Image">
            <?php endif; ?>
            <h2><?php echo htmlspecialchars($food_truck['name']); ?></h2>
        </div>

        <div class="form-container">
            <?php if ($message): ?>
                <div class="message">
                    <?php echo $message; ?>
                    <a href="#" class="close">&times;</a>
                </div>
            <?php endif; ?>

            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="truck_id" value="<?php echo htmlspecialchars($food_truck['truck_id']); ?>">

                <div class="form-row">
                    <label for="business_type">Business Type</label>
                    <input type="text" id="business_type" name="business_type" value="<?php echo htmlspecialchars($food_truck['business_type']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="name">Food Truck Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($food_truck['name']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="operator_name">Operator Name</label>
                    <input type="text" id="operator_name" name="operator_name" value="<?php echo htmlspecialchars($food_truck['operator_name']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($food_truck['address']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="business_hours">Business Hours</label>
                    <input type="text" id="business_hours" name="business_hours" value="<?php echo htmlspecialchars($food_truck['business_hours']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="latitude">Latitude</label>
                    <input type="text" id="latitude" name="latitude" value="<?php echo htmlspecialchars($food_truck['latitude']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="longitude">Longitude</label>
                    <input type="text" id="longitude" name="longitude" value="<?php echo htmlspecialchars($food_truck['longitude']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="image">Image</label>
                    <input type="file" id="image" name="image">
                </div>

                <button type="submit">Update Food Truck</button>
            </form>
        </div>
    </div>
</body>
</html>
