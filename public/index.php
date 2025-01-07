<?php
session_start();

$prompts = [
    "Ready to debug your thoughts? Join the conversation and share your brilliant ideas!",
    "Get connected, get inspired! Join the best forum for tech enthusiasts and share your thoughts.",
    "The best code isn't the only thing that's open source - your thoughts are too! Join the conversation!"
];

$randomPrompt = $prompts[array_rand($prompts)];

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechTalks</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/home.css">
</head>
<section class="home-container">
</section>
<body>
    <div class="welcome-msg">
        <h1>TechTalks</h1>
        <p><strong><?= htmlspecialchars($randomPrompt) ?></strong></p>  
    </div>
    <div class="btns-container">
        <a href="login.php" id="log-in-btn">Log In</a>
        <a href="register.php">Register</a>
        <a href="browse_threads.php">Browse Threads</a>
    </div>
</body>
</html>