<?php

declare(strict_types = 1);

require_once(__DIR__ . '/includes/header.php');

if (isset(($_GET['profile_username']))) {
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
    $user_array = mysqli_fetch_array($user_details_query);

    $num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}
?>

        <div class="profile_left">
            <img src="<?= $user_array['profile_pic'] ?>" alt="profile_pic">
        </div>

        <div class="profile_info">
            <p>Posts: <?= $user_array['num_posts'] ?></p>
            <p>Likes: <?= $user_array['num_likes'] ?></p>
            <p>Friends: <?= $num_friends ?></p>
        </div>

        <div class="column main-column">
            This is a profile page for <?= $_GET['profile_username'] ?>
        </div>

    </div>
</body>
</html>
