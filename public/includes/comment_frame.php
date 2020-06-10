<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../config/config.php');
require_once(__DIR__ . '/classes/User.php');
require_once(__DIR__ . '/classes/Post.php');
require_once(__DIR__ . '/classes/Helper.php');
?>

<html lang="en">
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="/../assets/css/style.css">
</head>
<body>
    <style>
        * {
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background-color: #f1f3f9;
        }
    </style>

    <?php

    if (isset($_SESSION['username'])) {
        $userLoggedIn = $_SESSION['username'];
        $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
        $user = mysqli_fetch_array($user_details_query);
    } else {
        header("Location: register.php");
    }
    ?>

    <script>
        function toggle() {
            let element = document.getElementById("comment_section");

            if (element.style.display == "block") {
                element.style.display = "none";
            } else {
                element.style.display = "block";
            }
        }
    </script>
        <?php
        // get id of post
        if (isset(($_GET['post_id']))) {
            $post_id = $_GET['post_id'];

        }

        $user_query = mysqli_query($con, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
        $row = mysqli_fetch_array($user_query);

        $posted_to = $row['added_by'];
        $user_to = $row['user_to'];

        if (isset($_POST['postComment' . $post_id])) {
            $post_body = $_POST['post_body'];
            $post_body = mysqli_escape_string($con, $post_body);
            $date_time_now = date("Y-m-d H:i:s");
            $insert_post = mysqli_query($con, "INSERT INTO post_comments(post_body, posted_by, posted_to, date_added, removed, post_id) VALUES ('$post_body','$userLoggedIn','$posted_to','$date_time_now','no','$post_id')");

            if ($posted_to != $userLoggedIn) {
                $notification = new Notification($this->con, $userLoggedIn);
                $notification->insertNotification($post_id, $posted_to, "comment");
            }

            if ($user_to != 'none' && $user_to != $userLoggedIn) {
                $notification = new Notification($this->con, $userLoggedIn);
                $notification->insertNotification($post_id, $user_to, "profile_comment");
            }

            $get_commenters = mysqli_query("SELECT * FROM comments WHERE post_id='$post_id'");
            $notified_users = [];
            while ($row = mysqli_fetch_array($get_commenters)) {
                if ($row['posted_by'] != $posted_to && $row['posted_by'] != $user_to
                    && $row['posted_by'] != $userLoggedIn && !in_array($row['posted_by'], $notified_users)) {
                    $notification = new Notification($this->con, $userLoggedIn);
                    $notification->insertNotification($post_id, $row['posted_by'], "comment_non_owner");

                    array_push($notified_users, $row['posted_by']);
                }
            }

            echo "<p>Comment posted!</p>";
        }

        ?>
        <form action="comment_frame.php?post_id=<?= $post_id ?>" id="comment_form" name="postComment<?= $post_id ?>" method="POST" >
        <textarea name="post_body"></textarea>
        <input type="submit" name="postComment<?= $post_id ?>" value="Post">
    </form>

    <!-- Load comments -->
    <?php
        $get_comments = mysqli_query($con, "SELECT * FROM post_comments WHERE post_id='$post_id' ORDER BY date_added DESC");
        $count = mysqli_num_rows($get_comments);

        if ($count !== 0) {
            while ($comment = mysqli_fetch_array($get_comments)) {
                $comment_body = $comment['post_body'];
                $posted_to = $comment['posted_to'];
                $posted_by = $comment['posted_by'];
                $date_added = $comment['date_added'];
                $removed = $comment['removed'];

                $time_message = Helper::getIntervalFromDate($date_added);

                $user_obj = new User($con, $posted_by);
                ?>
            <div class="comment_section">
                    <a href="/<?= $posted_by ?>" target="_parent">
                    <img src="/<?= $user_obj->getProfilePic() ?>" alt="image_pic" style="float: left; height: 40px">
                </a>
                <a href="/<?= $posted_by ?>" target="_parent"><b><?= $user_obj->getFirstAndLastName() ?></b></a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <?= $time_message . "<br />" . $comment_body ?>
            </div>
            <hr />
            <?php
            }
        } else {
            echo "<center><br /><br />No Comments To Show</center>";
        }
        ?>
</body>
</html>