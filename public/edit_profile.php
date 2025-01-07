<?php
session_start();
include('../includes/header.php');
require_once '../models/User.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = User::getById($user_id);

if (!$user) {
    echo "User not found.";
    include('../includes/footer.php');
    exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email)) {
        $errors[] = "Username and email are required.";
    }

    if (empty($errors)) {
        $user->setUsername($username);
        $user->setEmail($email);
    
        if (!empty($password)) {
            $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        }
    
        if ($user->update()) {
            header('Location: ../public/profile.php?success=1');
            exit;
        } else {
            $errors[] = "Failed to update the profile.";
        }
    }    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/edit_profile.css">
</head>
<section class="edit-profile-container">
    <h1>Edit Profile</h1>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user->getUsername()) ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Password (leave blank to keep current password)</label>
            <input type="password" id="password" name="password">
        </div>
        <button type="submit">Update Profile</button>
    </form>
</section>

<?php
include('../includes/footer.php');
?>
