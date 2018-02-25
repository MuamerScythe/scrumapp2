<?php
	require('includes/config.php');
	if(!$user->is_logged_in()) {
	header('Location: login.php');
	}
	
	$key = array_keys($_POST);
	
	switch($key[0]) {
		case 'email':
			if(!$user->check_email($_POST[$key[0]])) {
				echo 'false';
			}
			else echo 'true';
		break;
		case 'username':
			if(!$user->check_username($_POST[$key[0]])) {
				echo 'false';
			}
			else echo 'true';
		break;
		default:
	}








?>