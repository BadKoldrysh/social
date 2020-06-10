<?php

declare(strict_types = 1);

require_once(__DIR__ . '/classes/User.php');
require_once(__DIR__ . '/classes/Post.php');
require_once(__DIR__ . '/classes/Message.php');
require_once(__DIR__ . '/classes/Notification.php');
require_once(__DIR__ . '/../config/config.php');

if (isset($_SESSION['username'])) {
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
} else {
    header("Location: register.php");
}
?>

<html>
<head>
    <!-- META -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="/../assets/js/bootstrap.js"></script>
    <script src="/../assets/js/jcrop_bits.js"></script>
    <script src="/../assets/js/jquery.Jcrop.js"></script>
    <script src="/../assets/js/bootbox.min.js"></script>
    <script src="/../assets/js/demo.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="/../assets/css/jquery.Jcrop.css">
    <link rel="stylesheet" type="text/css" href="/../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>A Social Network Web</title>
</head>
<body>
    <div class="top_bar">
        <div class="logo">
            <a href="index.php">Social Network</a>
        </div>
        <nav>
            <?php
                // Unread messages
                $messages = new Message($con, $userLoggedIn);

                $num_messages = $messages->getUnreadNumber();

                // Unread notifications
                $notifications = new Notification($con, $userLoggedIn);

                $num_notifications = $notifications->getUnreadNumber();
            ?>

            <a href="<?= $userLoggedIn?>">
                <?php
                    echo $user['first_name'];
                ?>
            </a>
            <a href="index.php">
                <i class="fa fa-home fa-lg"></i>
            </a>
            <a href="javascript:void(0);" onclick="getDropdownData('<?= $userLoggedIn ?>', 'message')">
                <i class="fa fa-envelope fa-lg"></i>
                <?php
                    if ($num_messages > 0) {
                        echo '<span class="notification_badge" id="unread_message">' . $num_messages . '</span>';
                    }
                    ?>
            </a>
            <a href="javascript:void(0);" onclick="getDropdownData('<?= $userLoggedIn ?>', 'notification')">
                <i class="fa fa-bell-o fa-lg"></i>
                <?php
                    if ($num_notifications > 0) {
                        echo '<span class="notification_badge" id="unread_notifications">' . $num_notifications . '</span>';
                    }
                ?>
            </a>
            <a href="#">
                <i class="fa fa-users fa-lg"></i>
            </a>
            <a href="#">
                <i class="fa fa-cog fa-lg"></i>
            </a>
            <a href="includes/handlers/logout.php">
                <i class="fa fa-sign-out fa-lg"></i>
            </a>
        </nav>

        <div class="dropdown_data_window" style="height: 0px; border: none;"></div>
        <input type="hidden" id="dropdown_data_type" value="">
    </div>
    <script>
        let userLoggedIn = '<?= $userLoggedIn ?>';

        $(".dropdown_data_window").scroll(function() {
            let inner_height = $(".dropdown_data_window").innerHeight(); // div containing posts
            let scroll_top = $(".dropdown_data_window").scrollTop();
            let page = $(".dropdown_data_window").find('.nextPageDropdownData').val();
            let noMoreData = $(".dropdown_data_window").find('.noMoreDropdownData').val();

            if ((scroll_top + inner_height >= $(".dropdown_data_window")[0].scrollHeight) &&
                noMoreData == 'false') {
                let pageName;
                let type = $('#dropdown_data_type').val();

                if (type == 'notification') {
                    pageName = 'ajax_load_notifications.php';
                } else if (type == 'message') {
                    pageName = 'ajax_load_messages.php';
                }

                let ajaxReq = $.ajax({
                    url: "/includes/handlers/" + pageName,
                    type: "POST",
                    data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                    cache: false,

                    success: function(response) {
                        $('.dropdown_data_window').find('.nextPageDropdownData').remove(); // remove current .nextPage
                        $('.dropdown_data_window').find('.noMoreDropdownData').remove(); // remove current .noMorePosts

                        $('.dropdown_data_window').append(response);
                    }
                });
            } // end if

            return false;
        }); // end $(window).scroll()
    </script>

    <div class="wrapper">

