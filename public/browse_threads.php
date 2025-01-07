<?php
include('../includes/header.php');
require_once '../models/Thread.php';

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

$threads = Thread::getAll($searchQuery, $categoryFilter);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Threads</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/browse_threads.css">
</head>
<section class="browse-container">
<h1>Browse Threads</h1>

<form method="GET">
    <label>Title</label>
    <input type="text" name="search" placeholder="Search by title..." value="<?= htmlspecialchars($searchQuery) ?>">
    <label>Category</label>
    <select name="category">
        <option value="">All Categories</option>
        <option value="General" <?= ($categoryFilter == 'General') ? 'selected' : '' ?>>General</option>
        <option value="Web Development" <?= ($categoryFilter == 'Web Development') ? 'selected' : '' ?>>Web Development</option>
        <option value="Mobile Development" <?= ($categoryFilter == 'Mobile Development') ? 'selected' : '' ?>>Mobile Development</option>
        <option value="Cloud Computing" <?= ($categoryFilter == 'Cloud Computing') ? 'selected' : '' ?>>Cloud Computing</option>
        <option value="DevOps" <?= ($categoryFilter == 'DevOps') ? 'selected' : '' ?>>DevOps</option>
        <option value="Machine Learning" <?= ($categoryFilter == 'Machine Learning') ? 'selected' : '' ?>>Machine Learning</option>
    </select>
    <button type="submit">Search</button>
    </form>
    <section class="user-threads">
        <?php if (!empty($threads)): ?>
                <?php foreach ($threads as $thread): ?>
                    <a class="single-thread" href="thread.php?id=<?= htmlspecialchars($thread->id) ?>">
                    <div class="main-info">
                        <p class="title"><?= htmlspecialchars($thread->title) ?></p>
                        <p class="category"><?= htmlspecialchars($thread->category) ?></p>
                    </div>
                    <p class="description"><?= htmlspecialchars($thread->description) ?></p>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No threads available. Be the first to start a discussion!</p>
            <?php endif; ?>
    </section>
</section>

<?php
include('../includes/footer.php');
