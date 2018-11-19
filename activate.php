<?php
include 'header.php';
require_once 'includes/register_ban.php';
?>

<div class="box round first">
<h2>Account Activation</h2>
<div class="block">

<?php

$msg='Verification failed. Please register your account again.';

if(!empty($_GET['code']) && isset($_GET['code'])) {
	
	$code = $_GET['code'];

	if (preg_match($pattern_code, $code) || (StrLen($code) != 32)) {
		$msg =  "Verification failed. Please try again or contact the administrator.";
	}

	else {

		GetMyConnection();
		$code = mysqli_real_escape_string($db_link, $code);
		$result1 = mysqli_query($db_link, "SELECT * FROM validation WHERE code='$code' AND type='1' AND activated='0'") or ("Can't execute query1.");

		if (mysqli_num_rows($result1) == 1) {

			$array = mysqli_fetch_array($result1);
			$email = $array['email'];
			$password = $array['password'];
			$ip = $array['ip'];

			if (contains($email, $banned_users)) {
				$msg =  "Your account <b>".$email."</b> has been successfully activated!";
				error_log(date('[Y-m-d H:i] '). "Banned Registration Email - email: $email, IP: $ip_client" . PHP_EOL, 3, BAN_LOG);
			}
			elseif (banIP($ip, $banned_ips)) {
				$msg =  "Your account <b>".$email."</b> has been successfully activated!";
				error_log(date('[Y-m-d H:i] '). "Banned Registration IP - email: $email, IP: $ip" . PHP_EOL, 3, BAN_LOG);
			}
			elseif (banIP($ip_client, $banned_ips)) {
				$msg =  "Your account <b>".$email."</b> has been successfully activated!";
				error_log(date('[Y-m-d H:i] '). "Banned Registration IP - email: $email, IP: $ip_client" . PHP_EOL, 3, BAN_LOG);
			}


			$result2 = mysqli_query($db_link, "SELECT * FROM user WHERE email='$email'") or ("Can't execute query2.");

			if (!mysqli_num_rows($result2)) {

				$result3 = mysqli_query($db_link, "SELECT MAX(userid) AS maxid FROM user") or ("Can't execute query3.");
				$array3 = mysqli_fetch_array($result3);
				$newid = $array3['maxid']+1;

				mysqli_query($db_link, "INSERT INTO user VALUES ('$newid', '$email', '$password', '', '', '', '', '', '', '', '$ip_client', '$date', '1')") or die("Can't execute query4.");
				mysqli_query($db_link, "UPDATE validation SET activated = 1 WHERE code='$code'") or die ("Can't execute query5.");
				$msg =  "Your account <b>".$email."</b> has been successfully activated!";

			}

			else {
				$msg =  "Verification failed. Please try again or contact the administrator.";
			}
		}

		else $msg = "This activation code already expired. Please register your account again.";
		CleanUpDB();
	}
}

echo $msg;

include 'footer.php'; ?>