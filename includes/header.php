<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTalks</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/header.css">
</head>
<body>
    <nav>
        <ul class="nav-bar">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a id="profile-link" class="nav-link" href="../public/profile.php">Profile</a></li>
                <li><a class="nav-link" href="../public/browse_threads.php">Browse Threads</a></li>
                <li><a class="logo" href="../public/index.php">TechTalks</a></li>
                <li><a class="nav-link" href="../public/create_thread.php">Create a Thread</a></li>
                <li><a id="logout-link" class="nav-link" href="../includes/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a class="nav-link" href="../public/index.php">Home</a></li>
                <li><a class="nav-link" href="../public/browse_threads.php">Browse Threads</a></li>
                <li><a class="logo" href="../public/index.php">TechTalks</a></li>
                <li><a class="nav-link" href="../public/login.php">Login</a></li>
                <li><a class="nav-link" href="../public/register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
