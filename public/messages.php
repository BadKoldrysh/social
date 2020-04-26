<?php

declare(strict_types = 1);

require_once(__DIR__ . "/includes/header.php");
require_once(__DIR__ . "/includes/classes/Message.php");
require_once(__DIR__ . "/includes/classes/Post.php");
require_once(__DIR__ . "/includes/classes/User.php");

$message_obj = new Message($con, $userLoggedIn);

if (isset($_GET['u'])) {
    $user_to = $_GET['u'];
} else {
    $user_to = $message_obj->getMostRecentUser();

    if ($user_to == false) {
        $user_to = 'new';
    }
}

if ($user_to != 'new') {
    $user_to_obj = new User($con, $user_to);
}

?>

<div class="user-details column">
    <a href="<?= $userLoggedIn ?>"> <img src="<?= $user['profile_pic'] ?>" alt=""> </a>

    <div class="user-details-left-right">

        <a href="<?= $userLoggedIn ?>">
            <?php
                echo $user['first_name'] . " " . $user['last_name'];
            ?>
        </a>
        <br />
        <?php
            echo "Posts: " . $user['num_posts'] . "<br />";
            echo "Likes: " . $user['num_likes'];
        ?>
    </div>
</div>

<div class="main-column column" id="main_column">
    <?php
    if ($user_to != 'new') {
        echo "<h4>You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h4><hr /><br />";
    }
    ?>
</div>
