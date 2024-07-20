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
$image_path = '';

// Handle form submission for editing menu item
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu_id = $_POST['menu_id'];
    $menu_name = $_POST['menu_name'];
    $menu_desc = $_POST['menu_desc'];
    $menu_price = $_POST['menu_price'];
    $truck_id = $_POST['truck_id'];

    // Handle image upload
    if (isset($_FILES['menu_image']) && $_FILES['menu_image']['error'] == UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['menu_image']['tmp_name'];
        $image_name = basename($_FILES['menu_image']['name']);
        $image_dir = 'uploads/'; // Directory where images will be stored
        $image_path = $image_dir . $image_name;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // File uploaded successfully
            $message = "Image uploaded successfully.";
        } else {
            $message = "Error: Unable to upload image.";
            $image_path = ''; // Clear the image path in case of upload failure
        }
    } else {
        // No new image uploaded, keep the old image path
        $stmt = $conn->prepare("SELECT menu_image FROM menus WHERE menu_id=?");
        $stmt->bind_param("i", $menu_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $menu = $result->fetch_assoc();
        $image_path = $menu['menu_image'];
        $stmt->close();
    }

    // Debugging: Print variables
    echo "<pre>";
    echo "Menu ID: " . htmlspecialchars($menu_id) . "<br>";
    echo "Menu Name: " . htmlspecialchars($menu_name) . "<br>";
    echo "Menu Description: " . htmlspecialchars($menu_desc) . "<br>";
    echo "Menu Price: " . htmlspecialchars($menu_price) . "<br>";
    echo "Truck ID: " . htmlspecialchars($truck_id) . "<br>";
    echo "Image Path: " . htmlspecialchars($image_path) . "<br>";
    echo "</pre>";

    // Prepare an SQL statement for updating
    $stmt = $conn->prepare("UPDATE menus SET menu_name=?, menu_desc=?, menu_price=?, menu_image=?, truck_id=? WHERE menu_id=?");
    if ($stmt) {
        $stmt->bind_param("sssiii", $menu_name, $menu_desc, $menu_price, $image_path, $truck_id, $menu_id);

        if ($stmt->execute()) {
            // Redirect with success message
            header("Location: menus.php?truck_id=$truck_id&message=Menu item updated successfully.");
            exit();
        } else {
            $message = "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error preparing statement: " . $conn->error;
    }
}

// Fetch the menu item data to be edited
$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;

// Prepare an SQL statement for selecting
$stmt = $conn->prepare("SELECT * FROM menus WHERE menu_id=?");
$stmt->bind_param("i", $menu_id);
$stmt->execute();
$result = $stmt->get_result();
$menu = $result->fetch_assoc();
$stmt->close();

if (!$menu) {
    header('Location: menus.php'); // Redirect if the menu_id is invalid
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Item</title>
    <style>
        /* Your existing CSS styles */
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
        <h1>Edit Menu Item</h1>
        <a href="menus.php?truck_id=<?php echo htmlspecialchars($menu['truck_id']); ?>">Back to Menu List</a>
    </div>

    <div class="main-container">
        <div class="profile-container">
            <?php if ($menu['menu_image']): ?>
                <img src="<?php echo htmlspecialchars($menu['menu_image']); ?>" alt="Menu Item Image">
            <?php else: ?>
                <img src="default_image.png" alt="Default Image">
            <?php endif; ?>
            <h2><?php echo htmlspecialchars($menu['menu_name']); ?></h2>
        </div>

        <div class="form-container">
            <?php if (!empty($message)) { ?>
                <div class="message"><?php echo htmlspecialchars($message); ?><a href="#" class="close">&times;</a></div>
            <?php } ?>

            <form method="post" action="" enctype="multipart/form-data">
                <input type="hidden" name="menu_id" value="<?php echo htmlspecialchars($menu['menu_id']); ?>">
                <input type="hidden" name="truck_id" value="<?php echo htmlspecialchars($menu['truck_id']); ?>">

                <div class="form-row">
                    <label for="menu_name">Menu Name</label>
                    <input type="text" id="menu_name" name="menu_name" value="<?php echo htmlspecialchars($menu['menu_name']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="menu_desc">Menu Description</label>
                    <textarea id="menu_desc" name="menu_desc" rows="4" required><?php echo htmlspecialchars($menu['menu_desc']); ?></textarea>
                </div>

                <div class="form-row">
                    <label for="menu_price">Menu Price</label>
                    <input type="text" id="menu_price" name="menu_price" value="<?php echo htmlspecialchars($menu['menu_price']); ?>" required>
                </div>

                <div class="form-row">
                    <label for="menu_image">Upload New Image</label>
                    <input type="file" id="menu_image" name="menu_image">
                </div>

                <button type="submit">Update Menu Item</button>
            </form>
        </div>
    </div>
</body>
</html>
