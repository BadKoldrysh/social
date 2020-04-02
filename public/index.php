<?php

declare(strict_types = 1);

require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/classes/User.php');
// session_destroy();
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
            <form action="index.php" class="post-form">
                <textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>
                <input type="submit" name="post" id="post_button" value="Post">
                <hr />
            </form>
            <?php
                $user_obj = new User($con, $userLoggedIn);

                echo $user_obj->getFirstAndLastName();
            ?>
        </div>
        

    </div>
</body>
</html>
