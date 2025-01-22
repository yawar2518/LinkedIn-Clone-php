<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/login.html"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user profile information
$stmt = $conn->prepare("SELECT username, email, bio FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Fetch user's startups
$stmt = $conn->prepare("SELECT * FROM startups WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$startups_result = $stmt->get_result();
$startups = $startups_result->fetch_all(MYSQLI_ASSOC);

// Fetch user's jobs (if applicable)
$stmt = $conn->prepare("SELECT * FROM jobs WHERE startup_id IN (SELECT id FROM startups WHERE user_id = ?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$jobs_result = $stmt->get_result();
$jobs = $jobs_result->fetch_all(MYSQLI_ASSOC);

// Fetch user's messages
$stmt = $conn->prepare("SELECT * FROM messages WHERE receiver_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$messages_result = $stmt->get_result();
$messages = $messages_result->fetch_all(MYSQLI_ASSOC);

// Fetch user's notifications
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notifications_result = $stmt->get_result();
$notifications = $notifications_result->fetch_all(MYSQLI_ASSOC);

$stmt->close(); // Close the statement
?>
