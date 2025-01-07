<?php
include('../includes/header.php');
require_once '../includes/db.php';
require_once '../models/Thread.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$errors = [];
$title = '';
$description = '';
$category = 'General';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $created_by = $_SESSION['user_id'];

    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    if (empty($errors)) {
        $thread = new Thread();
        $newThreadId = $thread->create($title, $description, $category, $created_by);
        if ($newThreadId) {
            header("Location: thread.php?id=$newThreadId");
            exit;
        } else {
            $errors[] = "Failed to create the thread. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Thread</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/create_thread.css">
</head>
<section class="create-thread-container">
    <h1>Create a New Thread</h1>
    <p class="prompt-msg">The best ideas come from collaboration. Start a thread and let the brainstorming begin!</p>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="../public/create_thread.php" method="POST">
        <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div class="form-group">
            <label for="category">Category *</label>
            <select id="category" name="category" required>
                <option value="General" selected>General</option>
                <option value="Web Development">Web Development</option>
                <option value="Mobile Development">Mobile Development</option>
                <option value="Cloud Computing">Cloud Computing</option>
                <option value="DevOps">DevOps</option>
                <option value="Machine Learning">Machine Learning</option>
            </select>
        </div>
        <button type="submit">Create Thread</button>
    </form>
</section>

<?php include('../includes/footer.php'); ?>
