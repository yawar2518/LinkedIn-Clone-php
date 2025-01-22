<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch user's notifications
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notifications_result = $stmt->get_result();
$notifications = $notifications_result->fetch_all(MYSQLI_ASSOC);

$stmt->close(); // Close the statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Notifications - NotLinkedIn</title>
    <link rel="stylesheet" href="../front_end/style.css">
</head>
<body>
    <header>
        <h1>Your Notifications</h1>
        <nav>
            <a href="../front_end/dashboard.html">Dashboard</a>
            <a href="../front_end/messages.html">Messages</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Notifications</h2>
        <ul>
            <?php if (count($notifications) > 0): ?>
                <?php foreach ($notifications as $notification): ?>
                    <li>
                        <p><?php echo htmlspecialchars($notification['message']); ?></p>
                        <p><small>Received: <?php echo htmlspecialchars($notification['created_at']); ?></small></p>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No notifications available.</li>
            <?php endif; ?>
        </ul>
    </main>
    <footer>
        <p>&copy; 2023 NotLinkedIn</p>
    </footer>
</body>
</html>