<?php
if(isset($_SESSION))  session_destroy();
include 'header.php';
require_once 'includes/register_ban.php';
?>

<div class="box round first">
<h2>Log in</h2>
<div class="block">

<?php

if (isset($_POST['email']) && isset($_POST['password'])) {

	$email= $_POST['email'];
	$email = StrToLower(Trim($email));
	$password= $_POST['password'];
	$password = Trim($password);


	if (empty($email) || empty($password)) {
		echo "One or more fields are empty.<br><br><input type='button' onClick=location.href='account.php' value='Try Again' class='btn btn-blue'></input>";
	}

	elseif ((StrLen($email) < 6) or (StrLen($email) > 50)) {
		echo "Email Address must have at least 6 and not more than 50 characters.<br><br><input type='button' onClick=location.href='account.php' value='Try Again' class='btn btn-blue'></input>";
	}

	elseif (!preg_match($pattern_email, $email)) {
		echo "Incorrect Email Address.<br><br><input type='button' onClick=location.href='account.php' value='Try Again' class='btn btn-blue'></input>";
	}

	elseif ((StrLen($password) < 6) or (StrLen($password) > 30)) {
		echo "Password must have at least 6 and not more than 30 characters.<br><br><input type='button' onClick=location.href='account.php' value='Try Again' class='btn btn-blue'></input>";
	}

	elseif (preg_match($pattern, $password)) {
		echo "Wrong Password format.<br><br><input type='button' onClick=location.href='account.php' value='Try Again' class='btn btn-blue'></input>";
	}

	elseif (banIP($ip_client, $banned_ips)) {
		echo "Incorrect email or password!<br><br><input type='button' onClick=location.href='account.php' value='Try Again' class='btn btn-blue'></input>"; // Deny access if password is wrong
		error_log(date('[Y-m-d H:i] '). "Banned Registration IP: email: $email, IP: $ip_client" . PHP_EOL, 3, BAN_LOG);
	}


	else {

		GetMyConnection();

		// mysql anti-injection
		$email = stripslashes($email);
		$password = stripslashes($password);
		$email = mysqli_real_escape_string($db_link, $email);
		$password = mysqli_real_escape_string($db_link, $password);
		$encryptPassword= md5($email.$password);

		// 1st step: Check account in database
		$getAccountInfo = mysqli_query($db_link, "SELECT * FROM user WHERE email = '$email' AND active = '1'");
		$getAccountNum = mysqli_num_rows($getAccountInfo);

		if ($getAccountNum == 1) {

			$array = mysqli_fetch_array($getAccountInfo);
			$getID = $array['userid'];
			$getPassword = $array['password'];

			if ($encryptPassword == $getPassword) {

				if (!isset($_SESSION))  sec_session_start();
				$_SESSION['userid'] = $getID;
				$_SESSION['email'] = $email;

				echo "Thank you for logging in, <b>",$email,"</b>!<br><br><input type='button' onClick=location.href='account.php' value=' OK ' class='btn btn-blue'></input>"; // Click here if it doesn't automaticaly redirect the page

				if (isset($_SERVER["HTTP_REFERER"])) {
					$referer = $_SERVER["HTTP_REFERER"];
					header( "Location: $referer" );
					//header( "Location: index.php" );
				}

				else {
					header( "Location: index.php" );
				}
			}

			else {
				echo "Incorrect email or password!<br><br><input type='button' onClick=location.href='account.php' value='Try Again' class='btn btn-blue'></input>"; // Deny access if password is wrong
				mysqli_query($db_link, "INSERT INTO login VALUES ('$email','logincheck','$ip_client','$date')") or die(mysqli_error($db_link));
			}
		}

		else {
			echo "Incorrect email or password!<br><br><input type='button' onClick=location.href='account.php' value='Try Again' class='btn btn-blue'></input>"; // Deny access if user do not exist
		}

		CleanUpDB();
	}
}

include 'footer.php'; ?>
