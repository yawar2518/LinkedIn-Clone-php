<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $startup_id = $_SESSION['startup_id'] ?? 0;

    if (empty($startup_id) || !is_numeric($startup_id)) {
        error_log("Invalid startup_id: " . $startup_id, 3, "logs/error.log");
        $_SESSION['error_message'] = "Startup ID is invalid. Please try again.";
        header("Location: ../front_end/post_job.html");
        exit();
    }

    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];

    $stmt = $conn->prepare("INSERT INTO jobs (startup_id, title, description, location, salary) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isssd", $startup_id, $title, $description, $location, $salary);

    if ($stmt->execute()) {
        header("Location: ../back_end/dashboard_temp.php");
        exit();
    } else {
        error_log("SQL Error: " . $stmt->error, 3, "logs/error.log");
        echo "An error occurred. Please try again later.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../front_end/post_job.html");
    exit();
}
?>