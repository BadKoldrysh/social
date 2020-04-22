<?php

declare(strict_types = 1);

require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/classes/User.php');
require_once(__DIR__ . '/includes/classes/Post.php');

if (isset(($_GET['profile_username']))) {
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
    $user_array = mysqli_fetch_array($user_details_query);

    $num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}
?>
        <style>
            .wrapper {
                margin-left: 0px;
                padding-left: 0px;
            }
        </style>

        <div class="profile_left">
            <img src="<?= $user_array['profile_pic'] ?>" alt="profile_pic">
            <div class="profile_info">
                <p>Posts: <?= $user_array['num_posts'] ?></p>
                <p>Likes: <?= $user_array['num_likes'] ?></p>
                <p>Friends: <?= $num_friends ?></p>
            </div>
            <form action="<?= $username ?>">
                <?php
                    $profile_user_obj = new User($con, $username);
    
                    if ($profile_user_obj->isClosed()) {
                        header('Location: user_closed.php');
                    }
    
                    $logged_in_user_obj = new User($con, $userLoggedIn);
                    
                    if ($userLoggedIn !== $username) {
                        if ($logged_in_user_obj->isFriend($username)) {
                            echo '<input type="submit" name="remove_friend" class="danger" value="Remove friend"><br />';
                        } elseif ($logged_in_user_obj->didReceiveRequest($username)) {
                            echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br />';
                        } elseif ($logged_in_user_obj->didSendRequest($username)) {
                            echo '<input type="submit" name="" class="default" value="Request Sent"><br />';
                        } else {
                            echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br />';
                        }
                    }
                ?>
            </form>
        </div>



        <div class="column main-column">
            This is a profile page for <?= $_GET['profile_username'] ?>
        </div>

    </div>
</body>
</html>
