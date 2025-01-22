<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html"); // Redirect to login if not logged in
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $connection_id = $_POST['connection_id']; // Get the user ID to connect from the form

    // Check if the connection request already exists
    $checkStmt = $conn->prepare("SELECT * FROM connections WHERE user_id = ? AND connection_id = ?");
    $checkStmt->bind_param("ii", $user_id, $connection_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        echo "Connection request already sent.";
    } else {
        // Prepare the SQL statement to insert the connection request
        $stmt = $conn->prepare("INSERT INTO connections (user_id, connection_id, status) VALUES (?, ?, 'pending')");
        $stmt->bind_param("ii", $user_id, $connection_id);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // Redirect back to the connections page after sending the request
            header("Location: ../connections.php");
            exit();
        } else {
            echo "Error: " . $stmt->error; // Display error if the query fails
        }

        $stmt->close(); // Close the statement
    }

    $checkStmt->close(); // Close the check statement
} else {
    // If the request method is not POST, redirect to the connections page
    header("Location: ../connections.php");
    exit();
}
?>