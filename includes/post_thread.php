<?php
session_start();
require_once '../models/Post.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content']) && isset($_SESSION['user_id'])) {
    $content = $_POST['content'];
    $thread_id = $_GET['id'] ?? 0;
    $user_id = $_SESSION['user_id'];

    if ($thread_id && $content) {
        Post::create($thread_id, $user_id, $content);
    }

    header("Location: ../public/thread.php?id=" . $thread_id);
    exit;
}
?>
