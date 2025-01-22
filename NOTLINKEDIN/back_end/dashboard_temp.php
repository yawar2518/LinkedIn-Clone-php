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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - NotLinkedIn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('/NotlinkedIn/front_end/images/bg.png') no-repeat center center fixed;
            background-size: cover;
        }
        header {
            background: rgba(0, 115, 177, 0.8);
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        header h1 {
            margin: 0;
        }
        nav {
            margin: 1rem 0;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 1rem;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: calc(100vh - 80px);
            padding: 1rem;
        }
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
        }
        .card {
            background: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 300px;
            width: 100%;
            text-align: center;
        }
        footer {
            background: #222;
            color: #fff;
            text-align: center;
            padding: 0.01rem 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard</h1>
        <nav>
            <a href="../front_end/create_startup.html">Create Startup</a>
            <a href="../front_end/post_job.html">Post Job</a>
            <a href="../front_end/messages.html">Messages</a>
            <a href="../back_end/notifications.php">Notifications</a>
            <a href="../back_end/connections.php">Connections</a>
            <a href="../back_end/logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <div class="dashboard-container">
            <div class="card">
                <h3>Your Profile</h3>
                <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Bio: <?php echo htmlspecialchars($user['bio']); ?></p>
                <a href="../front_end/edit_profile.html">Edit Profile</a>
            </div>
            <div class="card">
                <h3>Your Startups</h3>
                <ul>
                    <?php foreach ($startups as $startup): ?>
                        <li><a href="startup_details.html?id=<?php echo $startup['id']; ?>"><?php echo htmlspecialchars($startup['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <a href="../front_end/create_startup.html">Create New Startup</a>
            </div>
            <div class="card">
                <h3>Your Jobs</h3>
                <ul>
                    <?php foreach ($jobs as $job): ?>
                        <li><a href="job_details.html?id=<?php echo $job['id']; ?>"><?php echo htmlspecialchars($job['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <a href="../front_end/post_job.html">Post New Job</a>
            </div>
            <div class="card">
                <h3>Your Messages</h3>
                <p>You have <?php echo count($messages); ?> new messages.</p>
                <a href="../front_end/messages.html">View Messages</a>
            </div>
            <div class="card">
                <h3>Your Notifications</h3>
                <p>You have <?php echo count($notifications); ?> new notifications.</p>
                <a href="../back_end/notifications.php">View Notifications</a>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2023 NotLinkedIn</p>
    </footer>
</body>
</html>
