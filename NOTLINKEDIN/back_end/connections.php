<?php
session_start();
include 'db.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/login.html"); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch connections
$stmt = $conn->prepare("SELECT u.id, u.username, c.status FROM connections c JOIN users u ON c.connection_id = u.id WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$connections_result = $stmt->get_result();
$connections = $connections_result->fetch_all(MYSQLI_ASSOC);

// Fetch pending connection requests
$stmt = $conn->prepare("SELECT u.id, u.username FROM connections c JOIN users u ON c.user_id = u.id WHERE c.connection_id = ? AND c.status = 'pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pending_requests_result = $stmt->get_result();
$pending_requests = $pending_requests_result->fetch_all(MYSQLI_ASSOC);

$stmt->close(); // Close the statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Connections - NotLinkedIn</title>
    <link rel="stylesheet" href="../front_end/style.css">
</head>
<body>
    <header>
        <h1>Your Connections</h1>
        <nav>
            <a href="../front_end/dashboard.html">Dashboard</a>
            <a href="../front_end/messages.html">Messages</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Connections</h2>
        <div class="connections-container">
            <h3>Your Connections</h3>
            <ul>
                <?php if (count($connections) > 0): ?>
                    <?php foreach ($connections as $connection): ?>
                        <li>
                            <?php echo htmlspecialchars($connection['username']); ?> 
                            (Status: <?php echo htmlspecialchars($connection['status']); ?>)
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No connections yet.</li>
                <?php endif; ?>
            </ul>

            <h3>Pending Connection Requests</h3>
            <ul>
                <?php if (count($pending_requests) > 0): ?>
                    <?php foreach ($pending_requests as $request): ?>
                        <li>
                            <?php echo htmlspecialchars($request['username']); ?> 
                            <form action="accept_connection.php" method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $request['id']; ?>">
                                <button type="submit">Accept</button>
                            </form>
                            <form action="decline_connection.php" method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $request['id']; ?>">
                                <button type="submit">Decline</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No pending requests.</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="send-connection">
            <h3>Send Connection Request</h3>
            <form action="../back_end/send_connection.php" method="POST">
                <input type="text" name="connection_id" placeholder="User  ID to connect" required>
                <button type="submit">Send Request</button>
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2023 NotLinkedIn</p>
    </footer>
</body>
</html>