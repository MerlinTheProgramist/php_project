<?php
ob_start();
session_start();
// unset($_SESSION['user_id']);
// unset($_SESSION['auth']);
// unset($_SESSION['login']);
// unset($_SESSION['profile_pic']);
session_destroy();

header("Location: ./login.php");
?>
