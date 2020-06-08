<?php

declare(strict_types=1);

require_once(__DIR__ . "/../../config/config.php");
require_once(__DIR__ . "/../classes/User.php");
require_once(__DIR__ . "/../classes/Message.php");

$limit = 6;

$message = new Message($con, $_REQUEST['userLoggedIn']);
// echo "<div>Gekko</div>";
echo $message->getConvosDropdown($_REQUEST, $limit);
