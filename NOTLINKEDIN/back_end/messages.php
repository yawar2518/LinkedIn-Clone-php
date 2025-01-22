<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/login.html"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch messages for the logged-in user
$stmt = $conn->prepare("SELECT m.*, u.username AS sender_username FROM messages m JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = ? ORDER BY m.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$messages_result = $stmt->get_result();
$messages = $messages_result->fetch_all(MYSQLI_ASSOC);

$stmt->close(); // Close the statement
?>