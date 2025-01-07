<?php
include('./db.php');
include('../models/User.php');

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

if (User::userExists($username, $email)) {
    header("Location: ../public/index.php");
    exit;
}

$user = new User($username, $email, $password);

if ($user->register()) {
    header("Location: ../public/index.php");
    exit;
} else {
    header("Location: ../public/index.php");
    exit;
}
?>
