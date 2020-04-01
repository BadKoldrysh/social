<?php

declare(strict_types = 1);

require_once(__DIR__ . '/includes/header.php');
session_destroy();
?>

        <div class="user-details column">
            <a href="#"> <img src="<?= $user['profile_pic'] ?>" alt=""> </a>
        </div>

    </div>
</body>
</html>
