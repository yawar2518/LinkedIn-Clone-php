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
    $connection_id = $_POST['user_id']; // Get the user ID to connect from the form

    // Update the connection status to 'accepted'
    $stmt = $conn->prepare("UPDATE connections SET status = 'accepted' WHERE user_id = ? AND connection_id = ?");
    $stmt->bind_param("ii", $connection_id, $user_id);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the connections page after accepting the request
        header("Location: ../connections.php");
        exit();
    } else {
        echo "Error: " . $stmt->error; // Display error if the query fails
    }

    $stmt->close(); // Close the statement
} else {
    // If the request method is not POST, redirect to the connections page
    header("Location: ../connections.php");
    exit();
}
?>