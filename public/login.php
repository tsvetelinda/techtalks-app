<?php
include('../includes/header.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<section class="login-container">
    <h2>Login</h2>
    <form action="../includes/login_process.php" method="POST">
        <div class="form-group">
            <label for="username">Username or Email</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</section>

<?php
include('../includes/footer.php');
?>
