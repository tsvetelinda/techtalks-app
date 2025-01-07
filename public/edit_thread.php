<?php
include('../includes/header.php');
require_once '../includes/db.php';
require_once '../models/Thread.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Thread not found.";
    include('../includes/footer.php');
    exit;
}

$thread = Thread::getById($_GET['id']);
if (!$thread) {
    echo "Thread not found.";
    include('../includes/footer.php');
    exit;
}

$isOwner = isset($_SESSION['user_id']) && $thread->created_by == $_SESSION['user_id'];

if (!$isOwner) {
    echo "You don't have permission to edit this thread.";
    include('../includes/footer.php');
    exit;
}

$errors = [];
$title = $thread->title;
$description = $thread->description;
$category = $thread->category;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];

    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    if (empty($errors)) {
        if ($thread->update($title, $description, $category)) {
            header("Location: ../public/thread.php?id=" . $thread->id);
            exit;
        } else {
            $errors[] = "Failed to update the thread. Please try again.";
        }
    }
} else {
    $title = $thread->title;
    $description = $thread->description;
    $category = $thread->category;
}

$categories = ['General', 'Web Development', 'Mobile Development', 'Cloud Computing', 'DevOps', 'Machine Learning'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Thread</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/edit_thread.css">
</head>
<section class="edit-thread-container">
    <h1>Edit Thread: <?= htmlspecialchars($thread->title) ?></h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="../public/edit_thread.php?id=<?= $thread->id ?>" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div class="form-group">
        <label for="category">Category</label>
            <select id="category" name="category" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= $cat === $category ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <button type="submit">Save Changes</button>
    </form>
</section>

<?php include('../includes/footer.php'); ?>
