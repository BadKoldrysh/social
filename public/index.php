<?php

declare(strict_types = 1);

require_once(__DIR__ . '/includes/header.php');
require_once(__DIR__ . '/includes/classes/User.php');
require_once(__DIR__ . '/includes/classes/Post.php');

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
        <div class="posts-area"></div>
        <img id="loading" src="assets/images/icons/loading.gif" alt="loading">
        <script>
            let userLoggedIn = '<?= $userLoggedIn ?>';

            $(document).ready(function() {
                $("#loading").show();

                // original ajax request for loading first posts
                $.ajax({
                    url: "/includes/handlers/ajax_load_posts.php",
                    type: "POST",
                    data: "page=1&userLoggedIn=" + userLoggedIn,
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
                console.log("ok");
                if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) &&
                    noMorePosts == 'false') {
                    console.log('not ok');
                    $('#loading').show();

                    let ajaxReq = $.ajax({
                        url: "/includes/handlers/ajax_load_posts.php",
                        type: "POST",
                        data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
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

    </div>
</body>
</html>
