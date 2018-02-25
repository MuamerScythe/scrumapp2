<?php
require_once('../includes/config.php');
require_once('../classes/user.php');;
require_once('../classes/customers.php');
$user = new User($db);

if(isset($_POST['arrived'])) {
  if(isset($_POST['id'])) {
    echo $user->setArrival($_POST);
  }
}

?>
