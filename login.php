<?php
// login.php

// Start session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    header('Location: index.php'); // Redirect to dashboard if already logged in
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection
    include 'connect.php';

    // Retrieve and sanitize form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials (example: hardcoded for admin)
    if ($username == 'admin' && $password == 'admin1') {
        $_SESSION['loggedin'] = true;
        header('Location: index.php'); // Redirect to dashboard
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f0f0f0;
            margin: 0;
            flex-direction: row;
        }
        .logo {
            margin-right: 20px; /* Space between logo and container */
            flex: 0 0 auto; /* Ensure logo doesn't grow */
            display: flex;
            align-items: center;
        }
        .logo img {
            height: 100%; /* Make logo height match container height */
            width: auto;  /* Maintain aspect ratio */
            max-height: 300px; /* Ensure logo doesn't exceed a maximum height */
        }
        .container {
            display: flex;
            align-items: center;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px; /* Adjust as needed */
            width: 100%;
        }
        .login-container {
            width: 100%;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #007bff; /* Default blue */
            text-align: center;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            text-align: left; /* Align labels to the left */
        }
        .login-container input[type="text"], 
        .login-container input[type="password"] {
            width: calc(100% - 22px); /* Adjust width for padding */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s; /* Smooth transition */
        }
        .login-container input[type="text"]:focus, 
        .login-container input[type="password"]:focus {
            border-color: #007bff; /* Highlight border on focus */
            outline: none; /* Remove default outline */
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #007bff; /* Default blue */
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s; /* Smooth transition */
        }
        .login-container input[type="submit"]:hover {
            background: #0056b3; /* Darker blue for hover effect */
        }
        .error {
            color: #ff0000; /* Red for errors */
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="icon_truck.jpg" alt="Food Truck Logo">
    </div>
    <div class="container">
        <div class="login-container">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>
</html>
