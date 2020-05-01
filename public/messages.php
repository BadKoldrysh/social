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

if (isset($_POST['post_message'])) {
    if (isset($_POST['message_body'])) {
        $body = mysqli_real_escape_string($con, $_POST['message_body']);
        $date = date("Y-m-d H:i:s");
        $message_obj->sendMessage($user_to, $body, $date);
    }
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
        echo '<div class="loaded_messages" id="scroll_messages">';
        echo $message_obj->getMessages($user_to);
        echo '</div>';
    } else {
        echo "<h4>New message</h4>";
    }
    ?>

    <div class="message_post">
        <form action="" method="post" name="post_message">
            <?php
            if ($user_to == 'new') {
                echo "Select the friend you would like to message<br /><br />";
                echo "To: <input type='text'>";
                echo "<div class='results'></div>";
            } else {
                echo "<textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>";
                echo "<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
            }
            ?>
        </form>
    </div>

    <script>
        const div = document.getElementById("scroll_messages");
        div.scrollTop = div.scrollHeight;
    </script>
</div>
