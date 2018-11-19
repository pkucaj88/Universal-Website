<?php

//check if its an ajax request, exit if not
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
	die();
}

require_once 'functions.php';


if(isset($_POST["email"])) {
	
	$email2 = $_POST['email'];
	$email2 = StrToLower(Trim($email2));
	$msg2 = '<span class="message error field">Incorrect Email Address.</span>';

	if(StrLen($email2) < 5) die();

	elseif(!preg_match($pattern_email, $email2))
		$msg2 = '<span class="message error field">Incorrect Email Address.</span>';

	elseif(!domain_exists($email2))
		$msg2 = '<span class="message error field">Please use your real e-mail address.</span>';

	else {

		require_once 'config.php';
		GetMyConnection();
		$email2 = mysqli_real_escape_string($db_link, $email2);

		$result1 = mysqli_query($db_link, "SELECT email FROM user WHERE email='$email2'") or ("Can't execute query.");
		$result2 = mysqli_query($db_link, "SELECT email FROM validation WHERE email='$email2' AND type = 1") or ("Can't execute query.");

		if (mysqli_num_rows($result1) || mysqli_num_rows($result2)) {
			$msg2 = '<span class="message error field">Account <b>'.$email2.'</b> already exists</span><br>';
		} else {
			$msg2 = '<img src="img/available.png" />';
		}

		CleanUpDB();
	}

	echo $msg2;
}

elseif(isset($_POST["pass"])) {
	
	$pass2 = $_POST['pass'];
	$pass2 = Trim($pass2);

	if(StrLen($pass2) < 6)
		die();

	elseif(preg_match($pattern, $pass2))
		$msg2 = '<span class="message error field">Allowed symbols: a-Z 0-9 _ . - ! @ # $ </span>';

	else
		$msg2 = '<img src="img/available.png" />';

	echo $msg2;
}

?>