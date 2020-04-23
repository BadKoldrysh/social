<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../../config/config.php');
require_once(__DIR__ . '/../classes/User.php');
require_once(__DIR__ . '/../classes/Post.php');

if (isset($_POST['post_body'])) {
    $post = new Post($con, $_POST['user_from']);
    $post->submitPost($_POST['post_body'], $_POST['user_to']);
}
