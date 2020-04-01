<?php

declare(strict_types = 1);

require_once(__DIR__ . '/includes/header.php');
session_destroy();
?>

        <div class="user-details column">
            <a href="#"> <img src="<?= $user['profile_pic'] ?>" alt=""> </a>

            <div class="user-details-left-right">

                <a href="#">
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

    </div>
</body>
</html>
