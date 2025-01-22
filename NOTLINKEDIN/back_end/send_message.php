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
    $sender_id = $_SESSION['user_id']; // Get the logged-in user's ID
    $receiver_id = $_POST['receiver_id']; // Get the receiver's user ID from the form
    $message = $_POST['message']; // Get the message content from the form

    // Check if the receiver ID exists in the users table
    $checkReceiverStmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
    $checkReceiverStmt->bind_param("i", $receiver_id);
    $checkReceiverStmt->execute();
    $checkReceiverStmt->store_result();

    if ($checkReceiverStmt->num_rows > 0) {
        // Receiver exists, proceed to insert the message
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            // Fetch the receiver's username for the notification
            $checkReceiverStmt->bind_result($receiver_id, $receiver_username);
            $checkReceiverStmt->fetch();

            // Insert a notification for the receiver
            $notification_message = "You have received a new message from " . htmlspecialchars($receiver_username) . ".";
            $notification_stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $notification_stmt->bind_param("is", $receiver_id, $notification_message);
            $notification_stmt->execute();
            $notification_stmt->close();

            // Redirect back to the messages page after sending the message
            header("Location: ../front_end/messages.html");
            exit();
        } else {
            echo "Error: " . $stmt->error; // Display error if the query fails
        }

        $stmt->close(); // Close the statement
    } else {
        echo "Error: Receiver ID does not exist."; // Handle the case where the receiver does not exist
    }

    $checkReceiverStmt->close(); // Close the check statement
} else {
    // If the request method is not POST, redirect to the messages page
    header("Location: ../front_end/messages.html");
    exit();
}
