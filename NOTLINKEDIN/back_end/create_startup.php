<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/login.html"); // Redirect to login if not logged in
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $name = $_POST['name']; // Get the startup name from the form
    $description = $_POST['description']; // Get the startup description from the form
    $logo = $_POST['logo']; // Get the logo URL from the form

    // Prepare the SQL statement to insert the startup
    $stmt = $conn->prepare("INSERT INTO startups (user_id, name, description, logo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $name, $description, $logo);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Set the startup_id in the session
        $_SESSION['startup_id'] = $conn->insert_id; // Get the last inserted ID
        header("Location: ../front_end/dashboard.html");
        exit();
    } else {
        echo "Error: " . $stmt->error; // Display error if the query fails
    }

    $stmt->close(); // Close the statement
} else {
    // If the request method is not POST, redirect to the create startup page
    header("Location: ../front_end/create_startup.html");
    exit();
}
?>