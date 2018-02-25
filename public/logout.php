<?php
require_once('../includes/config.php');
require_once('../classes/user.php');
require_once('../classes/phpmailer/mail.php');
$user = new User($db);
//logout
$user->logout();

//logged in return to index page
header('Location: login.php');
exit;
?>
