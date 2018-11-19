<?php

if (!isset($db_link)) {
	$db_link = false;
}

$site_name = "Universal Website";
$site_description = "Example description";
$site_address = "http://vps604032.ovh.net/site/";    // with trailing slash
$site_email = "example_email@live.com";
$payment_email = "example_email2@live.com";

$headers  = 'MIME-Version: 1.0' . "\n";
$headers .= 'Content-type: text/plain; charset=UTF-8' . "\n";
$headers .= 'From: '. $site_name .' <' . $site_email . ">\n";
$headers .= 'Reply-To: '. $site_name .' <' . $site_email . ">\n";
$headers .= 'X-Mailer: PHP/'.phpversion() . "\n";

define("BAN_LOG", dirname(__FILE__).'/../logs/_banned.log');


function GetMyConnection() {
	
	$db_host = "127.0.0.1";
	$db_user = "root";
	$db_password = "root";
	$db_name = "test";

	global $db_link;
	if($db_link)
		return $db_link;

	$db_link = mysqli_connect($db_host,  $db_user,  $db_password) or die('We are back in few minutes!');
	$db = mysqli_select_db($db_link, $db_name);
	$db_link -> set_charset("utf8");

	// security check for http_get variables to prevent injections
	foreach ($_GET as $key => $value)
	{
		$_GET[$key] = mysqli_real_escape_string($db_link, $value);
	}
	// security check for http_get variables to prevent injections
	foreach ($_POST as $key => $value)
	{
		$_POST[$key] = mysqli_real_escape_string($db_link, $value);
	}

	return $db_link;
}


function CleanUpDB() {
	
	global $db_link;
	if( $db_link != false )
		((is_null($___mysqli_res = mysqli_close($db_link))) ? false : $___mysqli_res);
	$db_link = false;
}
