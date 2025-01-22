<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/login.html"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch startups created by the user
$stmt = $conn->prepare("SELECT * FROM startups WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$startups_result = $stmt->get_result();
$startups = $startups_result->fetch_all(MYSQLI_ASSOC);

$stmt->close(); // Close the statement

// Return the startups as a JSON response
header('Content-Type: application/json');
echo json_encode($startups);
?>