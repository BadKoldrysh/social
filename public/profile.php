<?php

declare(strict_types = 1);

require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/classes/User.php');
require_once(__DIR__ . '/includes/classes/Post.php');
require_once(__DIR__ . '/includes/classes/Message.php');

$message_obj = new Message($con, $userLoggedIn);

if (isset(($_GET['profile_username']))) {
    $username = $_GET['profile_username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
    $user_array = mysqli_fetch_array($user_details_query);

    $num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}

if (isset($_POST['remove_friend'])) {
    $user = new User($con, $userLoggedIn);
    $user->removeFriend($username);
}
if (isset($_POST['add_friend'])) {
    $user = new User($con, $userLoggedIn);
    $user->sendRequest($username);
}
if (isset($_POST['respond_request'])) {
    header("Location: requests.php");
}
if (isset($_POST['post_message'])) {
    if (isset($_POST['message_body'])) {
        $msg_body = mysqli_real_escape_string($con, $_POST['message_body']);
        $current_date = date("Y-m-d H:i:s");

        $message_obj->sendMessage($username, $msg_body, $current_date);
    }

    $link = '#profileTabs a[href="#messages_div"]';
    echo <<<SCR
<script>
    $(function() {
        $('$link').tab('show');
    });
</script>
SCR;

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
            <form action="<?= $username ?>" method="POST">
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
            <input type="button" class='deep_blue' data-toggle="modal" data-target="#post-modal" value="Post something">

            <?php
                if ($userLoggedIn != $username) {
                    echo '<div class="profile_info_bottom">';
                    echo $logged_in_user_obj->getMutualFriends($username) . " Mutual Friends";
                    echo '</div>';
                }
            ?>
        </div>



        <div class="column profile-main-column">
            <ul class="nav nav-tabs" role="tablist" id="profileTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab">Messages</a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active show" id="newsfeed_div">
                    <div class="posts-area"></div>
                    <img id="loading" src="assets/images/icons/loading.gif" alt="loading">
                </div>

                <div role="tabpanel" class="tab-pane fade" id="messages_div">
                    <?php

                        echo "<h4>You and <a href='$username'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr /><br />";
                        echo '<div class="loaded_messages" id="scroll_messages">';
                        echo $message_obj->getMessages($username);
                        echo '</div>';
                    ?>

                    <div class="message_post">
                        <form action="" method="post" name="post_message">
                            <textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
                            <input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
                        </form>
                    </div>

                    <script>
                        const div = document.getElementById("scroll_messages");
                        div.scrollTop = div.scrollHeight;
                    </script>

                </div>
            </div>

        </div>



        <!-- Modal -->
        <div class="modal fade" id="post-modal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Post something</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>This will appear on users profile page and also their newsfeed for your friends to see!</p>

                <form action="" class="profile_post" method="POST">
                    <div class="form-group">
                        <textarea name="post_body" class="form-control"></textarea>
                        <input type="hidden" name="user_from" value="<?= $userLoggedIn ?>">
                        <input type="hidden" name="user_to" value="<?= $username ?>">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
            </div>
            </div>
        </div>
        </div>

        <script>
            // let userLoggedIn = '<?= $userLoggedIn ?>';
            let profileUsername = '<?= $username ?>';

            $(document).ready(function() {
                $("#loading").show();

                // original ajax request for loading first posts
                $.ajax({
                    url: "/includes/handlers/ajax_load_profile_posts.php",
                    type: "POST",
                    data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
                    cache: false,

                    success: function(data) {
                        $('#loading').hide();
                        $('.posts-area').html(data);
                    }
                });
            });

            $(window).scroll(function() {
                let height = $(".posts-area").height(); // div containing posts
                let scroll_top = $(this).scrollTop();
                let page = $('.posts-area').find('.nextPage').val();
                let noMorePosts = $('.posts-area').find('.noMorePosts').val();

                if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) &&
                    noMorePosts == 'false') {
                    $('#loading').show();

                    let ajaxReq = $.ajax({
                        url: "/includes/handlers/ajax_load_profile_posts.php",
                        type: "POST",
                        data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
                        cache: false,

                        success: function(response) {
                            $('.posts-area').find('.nextPage').remove(); // remove current .nextPage
                            $('.posts-area').find('.noMorePosts').remove(); // remove current .noMorePosts

                            $('#loading').hide();
                            $('.posts-area').append(response);
                        }
                    });
                } // end if

                return false;
            }); // end $(window).scroll()
        </script>

    </div>
</body>
</html>
