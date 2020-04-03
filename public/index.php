<?php

declare(strict_types = 1);

require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/classes/User.php');
require_once(__DIR__ . '/includes/classes/Post.php');

// session_destroy();

if (isset($_POST['post'])) {
    $post = new Post($con, $userLoggedIn);
    $post->submitPost($_POST['post_text'], 'none');
    header("Location: index.php");
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

        <div class="column main-column">
            <form action="index.php" class="post-form" method="post">
                <textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>
                <input type="submit" name="post" id="post_button" value="Post">
                <hr />
            </form>
            <?php
                $post = new Post($con, $userLoggedIn);
                $post->loadPostsFriends();
            ?>
        </div>
        

    </div>
</body>
</html>
