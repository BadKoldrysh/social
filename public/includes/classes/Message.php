<?php

declare(strict_types = 1);

class Message
{
    private $user_obj;
    private $con;

    public function __construct(mysqli $con, string $user)
    {
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function getMostRecentUser()
    {
        $userLoggedIn = $this->user_obj->getUserName();

        $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn' ORDER BY id DESC LIMIT 1");

        if (mysqli_num_rows($query) == 0) {
            return false;
        }

        $row = mysqli_fetch_array($query);
        $user_to = $row['user_to'];
        $user_from = $row['user_from'];

        if ($user_to != $userLoggedIn) {
            return $user_to;
        } else {
            return $user_from;
        }
    }

    public function sendMessage($userTo, $body, $date)
    {
        if ($body !== "") {
            $userLoggedIn = $this->user_obj->getUsername();
            $query = mysqli_query($this->con, "INSERT INTO messages(user_to, user_from, body, date, opened, viewed, deleted) VALUES('$userTo', '$userLoggedIn', '$body', '$date', 'no', 'no', 'no')");
        }
    }

    public function getMessages($otherUser)
    {
        $userLoggedIn = $this->user_obj->getUsername();
        $data = "";

        $query = mysqli_query($this->con, "UPDATE messages SET opened='yes' WHERE user_to='$userLoggedIn' AND user_from='$otherUser'");

        $get_messages_query = mysqli_query($this->con, "SELECT * FROM messages WHERE (user_to='$userLoggedIn' AND user_from='$otherUser') OR (user_from='$userLoggedIn' AND user_to='$otherUser')");

        while ($row = mysqli_fetch_assoc($get_messages_query, )) {
            $user_to = $row['user_to'];
            $user_from = $row['user_from'];
            $body = $row['body'];

            $div_top = ($user_to === $userLoggedIn) ? '<div class="message" id="green">' : '<div class="message" id="blue">';
            $data .= $div_top . $body . '</div><br /><br />';
        }

        return $data;
    }

    public function getLatestMessage()
    {
        $
    }

    public function getConvos()
    {
        $userLoggedIn = $this->user_obj->getUsername();
        $return_string = "";
        $convos = [];

        $query = mysqli_query($this->con, "SELECT user_to, user_from FROM messages WHERE user_to='$userLoggedIn' OR user_from='$userLoggedIn'");

        while ($row = $query->mysqli_fetch_array($query)) {
            $user_to_push = ($row['user_to'] != $userLoggedIn) ? $row['user_to'] : $row['user_from'];

            if (!in_array($user_to_push, $convos)) {
                array_push($convos, $user_to_push);
            }
        }

        foreach ($convos as $username) {
            $user_found_obj = new User($this->con, $username);
            $latest_message_details = $this->getLatestMessage($userLoggedIn, $username);
        }
    }
}
