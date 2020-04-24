<?php

declare(strict_types = 1);

require_once(__DIR__ . '/Helper.php');

class Post
{
    private $con;
    private $user_obj;

    public function __construct($con, $user)
    {
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function submitPost($body, $user_to)
    {
        $body = strip_tags($body); // remove html tags

        $body = str_replace('\r\n', '\n', $body);
        $body = nl2br($body);

        $body = mysqli_real_escape_string($this->con, $body);
        $check_empty = preg_replace('#\s+#', '', $body);

        if ($check_empty !== "") {
            // current date and time
            $date_added = date("Y-m-d H:i:s");
            // get username
            $added_by = $this->user_obj->getUsername();

            // if user is on own profile, user_to is 'none'
            if ($user_to === $added_by) {
                $user_to = 'none';
            }

            // insert post
            $query = mysqli_query($this->con, "INSERT INTO posts(body, added_by, user_to, date_added, user_closed, deleted, likes) VALUES('$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
            $returned_id = mysqli_insert_id($this->con);

            // insert notification

            // update post count for user
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
        }
    }

    public function loadPostsFriends($data, $limit)
    {
        $page = $data['page'];
        $userLoggedIn = $this->user_obj->getUsername();

        if ($page == 1) {
            $start = 0;
        } else {
            $start = ($page - 1) * $limit;
        }

        $str = "";
        $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

        if (mysqli_num_rows($data_query) > 0) {
            $num_iterations = 0; // number of results checked ( not necesserily posted)
            $count = 1;

            while ($row = mysqli_fetch_array($data_query)) {
                $id = $row['id'];
                $body = $row['body'];
                $added_by = $row['added_by'];
                $date_time = $row['date_added'];

                // prepare user_to string so it can be included even if not posted to a user
                if ($row['user_to'] === 'none') {
                    $user_to = "";
                } else {
                    $user_to_obj = new User($this->con, $row['user_to']);
                    $user_to_name = $user_to_obj->getFirstAndLastName();
                    $user_to = "to <a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
                }

                // check if user who posted, has their accound closed
                $added_by_obj = new User($this->con, $added_by);
                if ($added_by_obj->isClosed()) {
                    continue;
                }

                $user_logged_obj = new User($this->con, $userLoggedIn);
                if ($user_logged_obj->isFriend($added_by)) {
                    if ($num_iterations++ < $start) {
                        continue;
                    }

                    // once 10 posts have been loaded, break

                    if ($count > $limit) {
                        break;
                    } else {
                        $count++;
                    }

                    if ($userLoggedIn == $added_by) {
                        $delete_button = '<button class="delete_button btn-danger" id="post' . $id . '">X</button>';
                    } else {
                        $delete_button = '';
                    }

                    $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_pic'];

                    ?>
                    <script>
                        function toggle<?= $id ?>() {
                            let target = $(event.target);
                            if (!target.is("a")) {
                                let element = document.getElementById("toggleComment<?= $id ?>");

                                if (element.style.display == "block") {
                                    element.style.display = "none";
                                } else {
                                    element.style.display = "block";
                                }
                            }
                        }
                    </script>

                    <?php

                    $comments_check = mysqli_query($this->con, "SELECT * FROM post_comments WHERE post_id='$id'");
                    $comments_check_num = mysqli_num_rows($comments_check);

                    // timeframe
                    $time_message = Helper::getIntervalFromDate($date_time);

                    $str .= "<div class='status-post' onclick='javascript:toggle$id()'>
                                <div class='post-profile-pic'>
                                    <img src='$profile_pic' width='50'>
                                </div>

                                <div class='posted-by' style='color: #acacac;'>
                                    <a href='$added_by'> $first_name $last_name </a> $user_to &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                                    $delete_button
                                </div>
                                <div class='post-body'>
                                    $body
                                    <br />
                                    <br />
                                </div>

                                <div class='newsfeedPostOptions'>
                                    Comments ($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;
                                    <iframe src='includes/like.php?post_id=$id' id='like_iframe' frameborder='0' scrolling='no'></iframe>
                                </div>
                            </div>
                            <div class='post_comment' id='toggleComment$id' style='display:none;'>
                                <iframe src='includes/comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
                            </div>
                            <hr />
                    ";
                }
                ?>
            <script>

                $(document).ready(function() {
                    $('#post<?= $id ?>').on('click', function() {
                        bootbox.confirm("Are you sure you want to delete this post?", function(result) {
                            $.post("includes/form_handlers/delete_post.php?post_id=<?= $id ?>", {result: result});

                            if (result == true) {
                                location.reload();
                            }
                        })
                    });
                });
            </script>
            <?php
            } // end while loop

            if ($count > $limit) {
                $str .= '<input type="hidden" class="nextPage" value="' . ($page + 1) . '">' .
                            '<input type="hidden" class="noMorePosts" value="false">';
            } else {
                $str .= '<input type="hidden" class="noMorePosts" value="true">' .
                            '<p style="text-align: center;"> No more posts to show </p>';
            }
        }


        echo $str;
    }

    public function loadProfilePosts($data, $limit)
    {
        $page = $data['page'];
        $profileUser = $data['profileUsername'];
        $userLoggedIn = $this->user_obj->getUsername();

        if ($page == 1) {
            $start = 0;
        } else {
            $start = ($page - 1) * $limit;
        }

        $str = "";
        $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' AND ((added_by='$profileUser' AND user_to='none') OR user_to='$profileUser') ORDER BY id DESC");

        if (mysqli_num_rows($data_query) > 0) {
            $num_iterations = 0; // number of results checked ( not necesserily posted)
            $count = 1;

            while ($row = mysqli_fetch_array($data_query)) {
                $id = $row['id'];
                $body = $row['body'];
                $added_by = $row['added_by'];
                $date_time = $row['date_added'];

                if ($num_iterations++ < $start) {
                    continue;
                }

                // once 10 posts have been loaded, break

                if ($count > $limit) {
                    break;
                } else {
                    $count++;
                }

                if ($userLoggedIn == $added_by) {
                    $delete_button = '<button class="delete_button btn-danger" id="post' . $id . '">X</button>';
                } else {
                    $delete_button = '';
                }

                $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                $user_row = mysqli_fetch_array($user_details_query);
                $first_name = $user_row['first_name'];
                $last_name = $user_row['last_name'];
                $profile_pic = $user_row['profile_pic'];

                ?>
                <script>
                    function toggle<?= $id ?>() {
                        let target = $(event.target);
                        if (!target.is("a")) {
                            let element = document.getElementById("toggleComment<?= $id ?>");

                            if (element.style.display == "block") {
                                element.style.display = "none";
                            } else {
                                element.style.display = "block";
                            }
                        }
                    }
                </script>

                <?php

                $comments_check = mysqli_query($this->con, "SELECT * FROM post_comments WHERE post_id='$id'");
                $comments_check_num = mysqli_num_rows($comments_check);

                // timeframe
                $time_message = Helper::getIntervalFromDate($date_time);

                $str .= "<div class='status-post' onclick='javascript:toggle$id()'>
                            <div class='post-profile-pic'>
                                <img src='$profile_pic' width='50'>
                            </div>

                            <div class='posted-by' style='color: #acacac;'>
                                <a href='$added_by'> $first_name $last_name </a> &nbsp;&nbsp;&nbsp;&nbsp;$time_message
                                $delete_button
                            </div>
                            <div class='post-body'>
                                $body
                                <br />
                                <br />
                            </div>

                            <div class='newsfeedPostOptions'>
                                Comments ($comments_check_num)&nbsp;&nbsp;&nbsp;&nbsp;
                                <iframe src='includes/like.php?post_id=$id' id='like_iframe' frameborder='0' scrolling='no'></iframe>
                            </div>
                        </div>
                        <div class='post_comment' id='toggleComment$id' style='display:none;'>
                            <iframe src='includes/comment_frame.php?post_id=$id' id='comment_iframe' frameborder='0'></iframe>
                        </div>
                        <hr />
                ";
                ?>
            <script>

                $(document).ready(function() {
                    $('#post<?= $id ?>').on('click', function() {
                        bootbox.confirm("Are you sure you want to delete this post?", function(result) {
                            $.post("includes/form_handlers/delete_post.php?post_id=<?= $id ?>", {result: result});

                            if (result == true) {
                                location.reload();
                            }
                        })
                    });
                });
            </script>
            <?php
            } // end while loop

            if ($count > $limit) {
                $str .= '<input type="hidden" class="nextPage" value="' . ($page + 1) . '">' .
                            '<input type="hidden" class="noMorePosts" value="false">';
            } else {
                $str .= '<input type="hidden" class="noMorePosts" value="true">' .
                            '<p style="text-align: center;"> No more posts to show </p>';
            }
        }


        echo $str;
    }
}
