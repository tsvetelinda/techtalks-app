<?php
include('../includes/header.php');
require_once '../includes/db.php';
require_once '../models/Thread.php';
require_once '../models/Post.php';
require_once '../models/User.php';

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

$threadOwner = User::getById($thread->created_by);

$isThreadOwner = isset($_SESSION['user_id']) && $thread->created_by == $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post'])) {
    if (isset($_GET['post_id'])) {
        $post = Post::getById($_GET['post_id']);
        if ($post && ($post->user_id == $_SESSION['user_id'] || $isThreadOwner)) {
            if ($post->delete()) {
                header('Location: ../public/thread.php?id=' . $thread->id);
                exit;
            } else {
                echo "Failed to delete the post.";
            }
        } else {
            echo "You do not have permission to delete this post.";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    if ($isThreadOwner) {
        if ($thread->delete()) {
            header('Location: ../public/index.php');
            exit;
        } else {
            echo "Failed to delete the thread.";
        }
    } else {
        echo "You do not have permission to delete this thread.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post'])) {
    $content = trim($_POST['content']);
    
    if (!empty($content)) {
        $post = new Post();
        if ($post->create($thread->id, $_SESSION['user_id'], $content)) {
            header('Location: ../public/thread.php?id=' . $thread->id);
            exit;
        } else {
            echo "Failed to add the post.";
        }
    } else {
        echo "Content cannot be empty.";
    }
}

$posts = Post::getByThread($thread->id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($thread->title) ?></title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/thread.css">
</head>
<section class="thread-container">
    <h1><?= htmlspecialchars($thread->title) ?></h1>
    <p class="category"><strong>Category:</strong> <?= htmlspecialchars($thread->category) ?></p>
    <p class="created-by"><strong>Created by: </strong><?= htmlspecialchars($threadOwner->getUsername()) ?></p>
    <p class="description"><?= htmlspecialchars($thread->description) ?></p>
    <p class="created-at"><strong>Created at: </strong><?= htmlspecialchars($thread->created_at) ?></p>
    

    <?php if ($isThreadOwner): ?>
        <div class="btns-container">
            <a href="../public/edit_thread.php?id=<?= $thread->id ?>">
                    <button>Edit Thread</button>
            </a>
            <form action="../public/thread.php?id=<?= $thread->id ?>" method="POST">
                <button type="submit" name="delete">Delete Thread</button>
            </form>
        </div>
    <?php endif; ?>

    <section class="posts-container">
        <h2>Posts</h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <h3 class="post-prompt">Don't just cache your ideas — upload them or download others' insights!</h3>
            <form class="add-post" action="../public/thread.php?id=<?= $thread->id ?>" method="POST">
                <textarea name="content" required></textarea>
                <button type="submit" name="post">Add Post</button>
            </form>
        <?php else: ?>
            <p>You must be logged in to post.</p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="author">
                <div class="icon-container">
                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
        <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
        fill="#76C7E8" stroke="none">
        <path d="M3755 4900 c-255 -27 -507 -120 -686 -253 -78 -57 -185 -165 -215
        -216 l-18 -31 -1236 -2 c-1229 -3 -1235 -3 -1280 -24 -25 -11 -62 -39 -82 -62
        -72 -80 -68 25 -68 -1654 l0 -1508 -29 0 c-41 0 -93 -39 -118 -90 -22 -42 -23
        -56 -23 -250 0 -150 4 -221 15 -263 39 -152 137 -258 283 -308 53 -18 121 -19
        2262 -19 2141 0 2209 1 2262 19 146 50 244 156 283 308 11 42 15 113 15 263 0
        194 -1 208 -22 250 -26 51 -78 90 -119 90 l-29 0 0 1235 0 1235 36 47 c72 95
        121 226 131 353 22 291 -193 589 -542 750 -103 48 -253 95 -370 115 -108 19
        -339 27 -450 15z m475 -194 c316 -67 560 -225 661 -430 65 -133 73 -235 28
        -374 -39 -117 -139 -237 -281 -333 -114 -78 -301 -146 -476 -175 -128 -21
        -369 -20 -482 1 -158 30 -143 32 -455 -54 -159 -43 -291 -78 -292 -77 -1 1 36
        60 83 131 48 70 90 141 95 157 13 48 0 85 -57 148 -192 215 -201 464 -25 683
        88 108 284 234 450 287 230 73 513 87 751 36z m-1484 -518 c-4 -24 -10 -62
        -13 -85 l-6 -43 -1021 0 c-577 0 -1038 -4 -1060 -10 -54 -12 -87 -38 -113 -90
        l-23 -44 0 -1378 0 -1378 -85 0 -85 0 2 1509 c3 1453 4 1510 22 1529 10 12 28
        23 40 25 11 2 545 5 1185 6 l1164 1 -7 -42z m16 -325 c40 -99 71 -156 116
        -216 29 -37 52 -70 52 -72 0 -3 -49 -79 -109 -169 -60 -90 -112 -178 -116
        -195 -10 -46 24 -113 68 -134 19 -9 42 -17 52 -17 9 0 173 43 365 95 l348 94
        70 -14 c229 -48 533 -36 756 30 27 8 56 15 63 15 11 0 13 -189 13 -1060 l0
        -1060 -1880 0 -1880 0 0 1358 c0 747 3 1362 7 1365 3 4 469 7 1035 7 l1028 0
        12 -27z m2018 -1553 l0 -1150 -85 0 -85 0 0 1100 0 1100 48 24 c26 14 63 36
        82 50 19 14 36 25 38 26 1 0 2 -517 2 -1150z m-3072 -1435 c61 -124 113 -185
        180 -211 44 -18 83 -19 672 -19 589 0 628 1 672 19 67 26 119 87 180 211 l53
        105 742 0 743 0 0 -85 0 -85 -487 0 c-486 0 -487 0 -510 -22 -33 -31 -31 -87
        3 -119 l26 -24 485 -3 485 -3 -7 -36 c-14 -76 -70 -153 -139 -191 -29 -16
        -170 -17 -2246 -17 -2076 0 -2217 1 -2246 17 -69 38 -125 115 -139 191 l-7 36
        485 3 485 3 26 24 c33 31 35 85 4 118 l-21 23 -489 0 -488 0 0 85 0 85 743 0
        742 0 53 -105z m1562 100 c0 -16 -61 -123 -80 -140 -22 -20 -36 -20 -630 -20
        -594 0 -608 0 -630 20 -19 17 -80 124 -80 140 0 3 320 5 710 5 391 0 710 -2
        710 -5z"/>
        <path d="M3244 4297 c-228 -86 -223 -413 7 -481 89 -27 156 -15 230 39 121 88
        133 284 23 387 -65 61 -180 85 -260 55z m141 -182 c59 -58 5 -158 -75 -140
        -66 15 -88 97 -36 141 36 31 80 31 111 -1z"/>
        <path d="M3846 4300 c-71 -22 -137 -91 -164 -169 -38 -111 20 -244 133 -304
        56 -30 164 -30 220 0 183 96 193 347 18 450 -49 28 -154 40 -207 23z m138
        -186 c33 -33 34 -74 2 -110 -62 -73 -179 5 -134 89 28 54 89 63 132 21z"/>
        <path d="M4415 4287 c-93 -45 -144 -127 -145 -230 0 -178 175 -300 342 -238
        173 66 220 287 89 418 -44 44 -98 67 -171 70 -53 3 -74 -1 -115 -20z m165
        -172 c46 -49 24 -126 -40 -140 -80 -18 -134 82 -75 140 33 34 83 34 115 0z"/>
        <path d="M1855 3709 c-203 -27 -361 -120 -416 -244 -11 -24 -22 -70 -25 -104
        -6 -56 -9 -60 -35 -66 -87 -19 -127 -172 -74 -276 22 -42 74 -98 106 -115 19
        -9 29 -22 29 -37 0 -13 7 -48 16 -77 12 -42 29 -67 75 -112 32 -32 59 -60 59
        -63 0 -3 -22 -45 -50 -94 l-49 -88 -73 -12 c-152 -27 -326 -116 -415 -214 -94
        -103 -147 -253 -147 -422 0 -102 2 -115 28 -169 114 -232 508 -368 1071 -370
        473 -1 833 93 1003 261 93 92 107 129 107 288 0 144 -10 188 -71 305 -47 91
        -81 128 -167 188 -88 61 -198 107 -311 130 l-81 17 -54 94 -55 94 25 16 c72
        46 129 141 129 216 0 23 7 35 30 48 86 51 130 127 131 227 1 97 -28 142 -113
        172 -4 2 -12 34 -18 73 -20 126 -79 202 -205 264 -123 61 -305 89 -450 70z
        m320 -200 c51 -17 82 -35 116 -69 46 -44 47 -47 54 -120 7 -74 -5 -202 -25
        -271 -6 -19 -10 -70 -9 -115 1 -44 -3 -90 -9 -101 -7 -11 -35 -40 -64 -64 -29
        -23 -78 -69 -108 -101 l-56 -58 -110 0 -110 0 -59 61 c-33 33 -82 77 -108 97
        -66 50 -77 75 -77 175 0 47 -7 120 -16 163 -11 52 -15 111 -12 179 3 93 6 105
        31 138 29 39 102 78 187 102 85 23 284 15 375 -16z m-382 -1054 c26 -11 72
        -15 170 -15 123 0 138 2 183 25 27 14 52 25 56 25 7 0 70 -112 65 -116 -16
        -13 -137 -66 -191 -83 -82 -25 -128 -26 -210 -5 -58 15 -206 81 -206 92 0 2
        14 31 32 63 29 52 34 57 48 44 9 -8 32 -21 53 -30z m-298 -272 c64 -147 233
        -355 383 -472 60 -46 91 -51 138 -22 54 33 232 211 296 295 57 76 138 224 138
        253 0 50 241 -44 328 -127 44 -43 95 -138 111 -210 17 -75 14 -176 -8 -219
        -38 -73 -118 -126 -266 -174 -225 -74 -566 -110 -824 -88 -392 34 -667 126
        -735 244 -33 57 -38 100 -25 193 25 187 124 302 319 371 104 36 111 34 145
        -44z m265 -44 c134 -52 253 -53 391 -5 38 13 69 21 69 18 0 -27 -232 -282
        -257 -282 -20 0 -171 156 -221 228 -24 34 -42 62 -40 62 2 0 28 -10 58 -21z"/>
        <path d="M3294 2766 c-55 -24 -64 -106 -16 -142 13 -11 116 -13 478 -14 l461
        0 20 23 c29 32 34 52 21 88 -20 58 -31 59 -505 59 -338 -1 -435 -4 -459 -14z"/>
        <path d="M3302 2429 c-47 -14 -68 -64 -48 -113 23 -55 42 -57 524 -54 465 3
        461 3 481 60 7 18 7 38 0 56 -20 57 -15 57 -489 59 -260 1 -448 -2 -468 -8z"/>
        <path d="M3278 2076 c-51 -38 -38 -124 22 -145 23 -7 166 -11 457 -11 469 0
        481 1 501 59 13 36 8 56 -21 89 l-20 22 -461 0 c-362 -1 -465 -3 -478 -14z"/>
        <path d="M3273 1728 c-29 -32 -34 -53 -21 -88 20 -60 23 -60 503 -60 480 0
        483 0 503 60 13 35 8 56 -21 88 l-20 22 -462 0 -462 0 -20 -22z"/>
        <path d="M1392 787 c-31 -33 -29 -87 4 -118 22 -21 35 -24 101 -24 70 0 76 2
        99 28 31 37 31 81 -1 112 -21 22 -33 25 -103 25 -69 0 -81 -3 -100 -23z"/>
        <path d="M3525 785 c-32 -31 -32 -75 -1 -112 23 -26 29 -28 99 -28 66 0 79 3
        101 24 34 32 36 88 3 119 -20 19 -35 22 -101 22 -68 0 -80 -3 -101 -25z"/>
        </g>
        </svg>
        
                </div>
                <p><strong><?= htmlspecialchars(User::getById($post->user_id)->getUsername()) ?>:</strong></p>
        </div>
                <p class="post-content"><?= htmlspecialchars($post->content) ?></p>

                <?php 
                if ((isset($_SESSION['user_id']) && $post->user_id == $_SESSION['user_id']) || $isThreadOwner): ?>
                    <form action="../public/thread.php?id=<?= $thread->id ?>&post_id=<?= $post->id ?>" method="POST">
                        <button id="delete-btn" type="submit" name="delete_post"><svg version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="25" height="25" viewBox="0 0 512.000000 512.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
fill="#76C7E8" stroke="none">
<path d="M2220 5106 c-149 -42 -257 -152 -299 -305 -11 -41 -22 -75 -24 -77
-2 -2 -179 37 -394 86 -216 50 -413 90 -440 90 -153 -1 -304 -93 -371 -226
-30 -60 -112 -382 -112 -442 0 -50 36 -82 115 -101 39 -10 637 -147 1331 -307
l1261 -289 -1286 -5 c-1019 -4 -1291 -8 -1308 -18 -13 -7 -30 -26 -38 -42 -14
-28 -5 -139 130 -1558 79 -840 149 -1553 154 -1584 22 -128 120 -252 238 -300
l58 -23 1240 0 1240 0 60 24 c84 34 183 134 215 216 20 53 39 235 166 1555 79
822 143 1496 144 1498 0 1 17 -1 38 -4 43 -7 95 14 111 44 19 37 91 383 91
440 0 81 -31 176 -79 243 -87 122 -129 139 -586 244 -209 47 -385 88 -391 90
-9 3 -8 19 2 62 49 209 -47 408 -238 492 -23 11 -230 63 -459 115 -429 99
-480 106 -569 82z m503 -272 c205 -47 391 -90 414 -96 57 -14 127 -77 149
-133 12 -33 15 -61 11 -103 -12 -113 57 -117 -607 35 -322 74 -587 136 -589
139 -11 11 24 117 50 154 34 47 114 90 167 90 18 0 200 -38 405 -86z m-89
-489 c849 -195 1557 -360 1573 -366 70 -26 132 -119 133 -197 0 -34 -45 -260
-53 -269 -2 -2 -211 46 -1722 393 -1556 357 -1759 404 -1763 408 -5 6 33 173
54 237 26 81 116 147 201 149 18 0 727 -160 1577 -355z m1462 -1067 c-3 -29
-67 -694 -141 -1478 -74 -784 -139 -1442 -145 -1462 -14 -50 -55 -97 -105
-119 -38 -18 -97 -19 -1230 -19 -1133 0 -1192 1 -1230 19 -50 22 -91 70 -104
119 -12 45 -291 2974 -284 2985 2 4 733 7 1625 7 l1620 0 -6 -52z"/>
<path d="M2453 3112 c-12 -2 -33 -15 -47 -29 l-26 -26 0 -1293 0 -1293 23 -26
c29 -34 87 -44 123 -20 56 37 54 -22 54 1332 0 811 -4 1261 -10 1284 -6 20
-17 42 -24 48 -19 16 -66 28 -93 23z"/>
<path d="M1536 3079 c-18 -21 -26 -42 -26 -67 0 -148 123 -2522 132 -2545 12
-30 54 -57 88 -57 35 0 76 27 89 58 10 23 0 273 -49 1288 -33 692 -63 1270
-66 1282 -9 43 -48 72 -98 72 -37 0 -48 -5 -70 -31z"/>
<path d="M3275 3086 c-13 -13 -26 -39 -29 -57 -3 -19 -30 -558 -61 -1199 -30
-641 -58 -1206 -61 -1257 l-7 -92 36 -36 c51 -51 122 -43 153 17 11 21 28 297
74 1250 33 673 60 1249 60 1280 0 46 -5 62 -26 87 -22 26 -33 31 -70 31 -34 0
-51 -6 -69 -24z"/>
</g>
</svg></button>
                    </form>

                    <a class="edit" href="../public/edit_post.php?id=<?= $post->id ?>&thread_id=<?= $thread->id ?>">
                        <button id="edit-btn"><svg version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="25" height="25" viewBox="0 0 512.000000 512.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
fill="#76C7E8" stroke="none">
<path d="M4253 5080 c-78 -20 -114 -37 -183 -83 -44 -29 -2323 -2296 -2361
-2349 -21 -29 -329 -1122 -329 -1168 0 -56 65 -120 122 -120 44 0 1138 309
1166 329 15 11 543 536 1174 1168 837 838 1157 1165 1187 1212 74 116 105 270
82 407 -7 39 -30 105 -53 154 -36 76 -55 99 -182 226 -127 127 -150 145 -226
182 -135 65 -260 78 -397 42z m290 -272 c55 -27 258 -231 288 -288 20 -38 24
-60 24 -140 0 -121 -18 -160 -132 -279 l-82 -86 -303 303 -303 303 88 84 c49
46 108 93 132 105 87 42 203 41 288 -2z m-383 -673 l295 -295 -933 -932 -932
-933 -295 295 c-162 162 -295 299 -295 305 0 13 1842 1855 1855 1855 6 0 143
-133 305 -295z m-1822 -2284 c-37 -12 -643 -179 -645 -178 -1 1 30 115 68 252
38 138 79 285 91 329 l21 78 238 -238 c132 -132 233 -241 227 -243z"/>
<path d="M480 4584 c-209 -56 -370 -206 -444 -414 l-31 -85 0 -1775 c0 -1693
1 -1778 18 -1834 37 -120 77 -187 167 -277 63 -63 104 -95 157 -121 146 -73 3
-69 2113 -66 l1895 3 67 26 c197 77 336 218 401 409 22 64 22 74 25 710 3 579
2 648 -13 676 -21 40 -64 64 -114 64 -33 0 -49 -7 -79 -34 l-37 -34 -5 -634
c-5 -631 -5 -633 -28 -690 -41 -102 -118 -179 -220 -220 l-57 -23 -1834 -3
c-1211 -1 -1853 1 -1890 8 -120 22 -227 104 -277 213 l-29 62 0 1760 0 1760
29 63 c37 80 122 161 203 194 l58 23 630 5 c704 6 664 1 700 77 23 48 13 95
-31 138 l-35 35 -642 -1 c-533 0 -651 -3 -697 -15z"/>
</g>
</svg>
</button>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>
</section>

<?php include('../includes/footer.php'); ?>
