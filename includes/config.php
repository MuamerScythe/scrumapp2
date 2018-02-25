<?php
session_start();

//set timezone
date_default_timezone_set('Europe/Sarajevo');

define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','');
define('DBNAME','walter');
define("PAGES_PATH", realpath(dirname(__FILE__) . '/layout'));
//application address
define('DIR','http://domain.com/');
define('SITEEMAIL','noreply@domain.com');
//Results per page
define ('RPP', 10);
define('CSS_PATH', '../');
define('LINKS_PATH', '../');
define('MEETING_TIME', '8:45:00');
try {
	//create PDO connection
	$db = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	//show error
    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
    exit;
}

?>
