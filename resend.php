<?php
include 'header.php';
require_once 'includes/register_ban.php';
include_once 'res/securimage/securimage.php';
$securimage = new Securimage();
?>

<div class="box round first">
<h2>Re-send Activation Email</h2>
<div class="block">

<?php

$msg = 'If aren\'t getting the account activation e-mail, please input your username in the field below to re-send it.';

if (isset($_POST['email'])) {

	$email = $_POST['email'];
	$email = StrToLower(Trim($email));

	if (empty($email)) {
		$msg = '<span class="message error">Entered field is empty.</span>';
	}
	elseif (!preg_match($pattern_email, $email)) {
		$msg = '<span class="message error">Incorrect Email Address format.</span>';
	}
	elseif ((StrLen($email) < 4) or (StrLen($email) > 50)) {
		$msg = '<span class="message error">Email must have at least 4 and not more than 50 characters.</span>';
	}
	elseif ($securimage->check($_POST['captcha_code']) == false) {
		$msg = '<span class="message error">The security code entered was incorrect. Please make sure you have enabled JavaScript.</span>';
	}
	elseif (!domain_exists($email)) {
		$msg = '<span class="message error">Incorrect Email Address.</span>';
	}
	elseif (contains($email, $banned_users)) {
		$msg =  '<div class="message info">Activation Email of account <b>'.$email.'</b> has been re-sent.<br>
		Please visit your mailbox to activate it.<br>
		Remember to check your Spam folder.</div>';
		error_log(date('[Y-m-d H:i] '). "Banned user: $email, IP: $ip_client" . PHP_EOL, 3, BAN_LOG);
	}

	else {

		GetMyConnection();
		$email = mysqli_real_escape_string($db_link, $email);

		$getAccountInfo = mysqli_query($db_link, "SELECT * FROM validation WHERE email = '$email' AND type = 1 AND activated = 0") or die(mysqli_error($db_link));
		$getAccountNum = mysqli_num_rows($getAccountInfo);

		if ($getAccountNum != 1) {
			$msg = '<span class="message error">Username couldn\'t be found or is already activated.</span>';
			mysqli_query($db_link, "INSERT INTO login VALUES ('$email','emailcheck','$ip_client','$date')") or die(mysqli_error($db_link));
		}

		else {

			$array = mysqli_fetch_array($getAccountInfo);
			$email = $array['email'];
			$code = $array['code'];

			$to = "$email";
			$subject = "Account Activation - $site_name";
			$message = <<<EOD

Welcome {$email},

to activate your account, please click on the link below:
{$site_address}activate.php?code={$code}

EOD;

			mail($to, $subject, $message, $headers);

			$msg =  '<div class="message info">Activation Email of account <b>'.$email.'</b> has been re-sent.<br>
			Please visit your mailbox to activate it.<br>
			Remember to check your Spam folder.</div>';
			mysqli_query($db_link, "INSERT INTO login VALUES ('$email','recover','$ip_client','$date')") or die(mysqli_error($db_link));
		}

		CleanUpDB();
	}
}

echo $msg ,'<br>';
?>

<br>
<form method='post' action='resend.php'>
<table class='form'>
<tr>
<tr><td class="col1"><label>Email Address:</label></td><td class="col2"><input name='email' type='text' id='email' class='input' style="width:200px;" maxlength='50'/>
<tr><td class="col1"><label> </label></td><td class="col2"><br><img id="captcha" title="captcha" src="res/securimage/securimage_show.php" alt="CAPTCHA Image" /></td></tr>
<tr><td class="col1"><label> </label></td><td class="col2"><input type="text" placeholder="Captcha" name="captcha_code" size="10" maxlength="6" /> &nbsp;&nbsp; <a href="#" title="reload" onclick="document.getElementById('captcha').src = 'res/securimage/securimage_show.php?' + Math.random(); return false">[&#8635;]</a></td></tr>
<tr><td class="col1"><label> </label></td><td class="col2"><br><input type='submit' name='Submit' value='Confirm' class='btn btn-blue'/></td>
</tr>
</table>
</form>

<?php
include 'footer.php';
?>