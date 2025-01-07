<?php
include('./db.php');
include('../models/User.php');

$usernameOrEmail = $_POST['username'];
$password = trim($_POST['password']);

$user = User::findByUsernameOrEmail($usernameOrEmail);

if ($user && password_verify($password, $user->getPassword())) {
    session_start();
    $_SESSION['user_id'] = $user->getId();
    $_SESSION['username'] = $user->getUsername();
    $_SESSION['email'] = $user->getEmail();
    
    header("Location: ../public/index.php");
    exit;
} else {
    echo "Invalid username/email or password!";
}
?>
