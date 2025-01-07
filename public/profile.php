<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Profile</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
<?php
include('../includes/header.php');
require_once '../includes/db.php';
require_once '../models/User.php';
require_once '../models/Thread.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = User::getById($user_id);

if (!$user) {
    echo "<p>User not found.</p>";
    include('../includes/footer.php');
    exit;
}

$threads = Thread::getByUserId($user_id);
?>
<div class="profile-container">
    <section class="profile-card">
        <h1><?= htmlspecialchars($user->getUsername()) ?>'s Profile</h1>
        <div class="avatar-container">
            <svg version="1.0" xmlns="http://www.w3.org/2000/svg"
            width="120" height="120" viewBox="0 0 512.000000 512.000000"
            preserveAspectRatio="xMidYMid meet">

            <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
            fill="#76C7E8" stroke="none">
            <path d="M2355 5035 c-229 -51 -406 -185 -500 -380 -53 -109 -71 -176 -86
            -318 -29 -259 42 -557 182 -765 138 -206 282 -309 499 -358 l55 -12 -80 -1
            c-271 -4 -669 -69 -916 -151 -315 -104 -513 -267 -590 -486 -35 -100 -40 -221
            -37 -966 l3 -717 23 -23 c22 -22 30 -23 222 -26 198 -3 200 -3 200 -25 0 -12
            3 -39 6 -59 l7 -38 -608 -2 -607 -3 -24 -28 -24 -28 0 -264 0 -264 24 -28 24
            -28 2432 0 2432 0 24 28 24 28 0 264 0 264 -24 28 -24 28 -607 3 -608 2 7 38
            c3 20 6 47 6 60 l0 22 184 0 c102 0 196 4 211 10 57 22 57 10 52 854 -4 847
            -1 809 -66 941 -107 214 -343 370 -690 454 -242 60 -498 97 -726 108 l-140 7
            63 13 c197 40 366 162 492 354 140 212 210 509 182 766 -16 142 -34 209 -87
            318 -95 198 -272 331 -507 380 -86 19 -319 18 -403 0z m409 -165 c258 -66 409
            -268 432 -578 18 -242 -69 -534 -210 -704 -217 -261 -537 -293 -779 -78 -274
            246 -370 769 -200 1093 70 133 203 233 358 271 93 22 304 20 399 -4z m246
            -1855 c164 -22 392 -67 505 -100 259 -76 443 -204 517 -361 45 -96 49 -168 46
            -894 l-3 -665 -127 -3 c-127 -3 -128 -2 -128 20 0 13 29 255 65 538 36 283 65
            533 65 554 0 55 -41 121 -90 146 -39 20 -59 20 -1300 20 -1241 0 -1261 0
            -1300 -20 -49 -25 -90 -91 -90 -147 0 -22 29 -271 65 -554 36 -283 65 -524 65
            -537 0 -22 -1 -23 -127 -20 l-128 3 -3 665 c-1 407 1 698 8 750 22 191 136
            323 371 435 184 86 420 139 849 189 94 11 621 -2 740 -19z m780 -923 c0 -9
            -38 -321 -85 -691 -47 -371 -85 -678 -85 -683 0 -4 -477 -8 -1060 -8 -583 0
            -1060 4 -1060 8 0 5 -38 312 -85 683 -47 370 -85 682 -85 691 0 17 62 18 1230
            18 1168 0 1230 -1 1230 -18z m1090 -1707 l0 -165 -2320 0 -2320 0 0 165 0 165
            2320 0 2320 0 0 -165z"/>
            <path d="M2485 1711 c-88 -23 -168 -92 -207 -176 -17 -38 -23 -68 -23 -125 0
            -88 22 -145 80 -208 117 -131 333 -131 450 0 58 63 80 120 80 208 0 123 -60
            221 -168 277 -53 26 -158 39 -212 24z m145 -171 c43 -22 80 -80 80 -126 0 -51
            -38 -111 -84 -133 -53 -26 -79 -26 -131 -1 -106 52 -112 194 -9 256 48 29 92
            30 144 4z"/>
            </g>
            </svg>
        </div>
        <p><strong>Email:</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
        <p><strong>Member Since:</strong> <?= htmlspecialchars($user->getCreatedAt()) ?></p>
        <a href="edit_profile.php">Edit Profile</a>
    </section>
    <section class="user-threads">
        <h1>Your Threads</h1>

        <?php if (count($threads) > 0): ?>
            <?php foreach ($threads as $thread): ?>
                <a class="single-thread" href="thread.php?id=<?= htmlspecialchars($thread['id']) ?>">
                    <div class="main-info">
                        <p class="title"><?= htmlspecialchars($thread['title']) ?></p>
                        <p class="category"><?= htmlspecialchars($thread['category']) ?></p>
                    </div>
                    <p class="description"><?= htmlspecialchars($thread['description']) ?></p>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>You haven't created any threads yet.</p>
        <?php endif; ?>
    </section>
</div>

<?php include('../includes/footer.php'); ?>
