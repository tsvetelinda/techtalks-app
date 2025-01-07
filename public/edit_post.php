<?php
include('../includes/header.php');
require_once '../includes/db.php';
require_once '../models/Post.php';
require_once '../models/Thread.php';

if (!isset($_GET['id']) || !isset($_GET['thread_id'])) {
    echo "Post or thread not found.";
    include('../includes/footer.php');
    exit;
}

$post = Post::getById($_GET['id']);
if (!$post) {
    echo "Post not found.";
    include('../includes/footer.php');
    exit;
}

$isPostOwner = isset($_SESSION['user_id']) && $post->user_id == $_SESSION['user_id'];
$thread = Thread::getById($_GET['thread_id']);
$isThreadOwner = isset($_SESSION['user_id']) && $thread->created_by == $_SESSION['user_id'];

if (!$isPostOwner && !$isThreadOwner) {
    echo "You do not have permission to edit this post.";
    include('../includes/footer.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    if ($post->update($_POST['content'])) {
        header('Location: ../public/thread.php?id=' . $thread->id);
        exit;
    } else {
        echo "Failed to update the post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/edit_post.css">
</head>
<section class="edit-form-container">
    <h1>Edit Post</h1>
    <form action="../public/edit_post.php?id=<?= $post->id ?>&thread_id=<?= $thread->id ?>" method="POST">
        <label>Post Content</label>
        <textarea name="content" required><?= htmlspecialchars($post->content) ?></textarea>
        <button type="submit">Save Changes</button>
    </form>
</section>

<?php include('../includes/footer.php'); ?>
