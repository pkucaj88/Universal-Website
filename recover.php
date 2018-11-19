<?php
include 'header.php';
require_once 'includes/register_ban.php';
include_once 'res/securimage/securimage.php';
$securimage = new Securimage();
?>

<div class="box round first">
<h2>Recover Account</h2>
<div class="block">

<?php
if (isset($_POST['email'])) {

	$email = $_POST['email'];
	$email = StrToLower(Trim($email));

	if (empty($email)) {
		$msg = "Entered field is empty.<br><br><input type='button' onClick=location.href='recover.php' value='Try Again' class='btn btn-blue'></input>";
	}
	elseif (!preg_match($pattern_email, $email)) {
		$msg = "Incorrect Email Address format.<br><br><input type='button' onClick=location.href='recover.php' value='Try Again' class='btn btn-blue'></input>";
	}
	elseif ((StrLen($email) < 4) or (StrLen($email) > 50)) {
		$msg = "Email must have at least 4 and not more than 50 characters.<br><br><input type='button' onClick=location.href='recover.php' value='Try Again' class='btn btn-blue'></input>";
	}
	elseif ($securimage->check($_POST['captcha_code']) == false) {
		$msg = "The security code entered was incorrect. Please make sure you have enabled JavaScript.<br><br><input type='button' onClick=location.href='recover.php' value='Try Again' class='btn btn-blue'></input>";
	}
	elseif (!domain_exists($email)) {
		$msg = "Incorrect Email Address.<br><br><input type='button' onClick=location.href='recover.php' value='Try Again' class='btn btn-blue'></input>";
	}
	elseif (contains($email, $banned_users)) {
		$msg = "Recovery e-mail has been sent to <b>".$email."</b><br><br>
		Delivering the message can take some time, depending on the mailing service.<br>
		Please remember to check your Spam folder as well.";
		error_log(date('[Y-m-d H:i] '). "Banned user: $email, IP: $ip_client" . PHP_EOL, 3, BAN_LOG);
	}

	else {

		GetMyConnection();
		$email = mysqli_real_escape_string($db_link, $email);

		$getAccountInfo = mysqli_query($db_link, "SELECT * FROM user WHERE email = '$email' AND active = '1'") or die("Wrong email");
		$getAccountNum = mysqli_num_rows($getAccountInfo);

		if ($getAccountNum != 1) {
			$msg = "User <b>$email</b> not found.<br><br><input type='button' onClick=location.href='recover.php' value='Back' class='btn btn-blue'></input>";
			mysqli_query($db_link, "INSERT INTO login VALUES ('$email','emailcheck','$ip_client','$date')") or die(mysqli_error($db_link));
		}

		else {

			$array = mysqli_fetch_array($getAccountInfo);
			$code = md5($email.time());

			mysqli_query($db_link, "INSERT INTO validation VALUES ('$email', '', '$ip_client', '$date', '$code', '2', '0')") or die("Couldn't perform");

			//Email out the infromation
			$subject = "Validation Code - $site_name";
			$message = <<<EOD

Hello {$email},

The password reset has been requested for this account.
To set the new password, please go here:
{$site_address}recover2.php?code={$code}


This link will expire in 6 hours.
If you didn't request password change, please let us know in the reply message.

EOD;

			if(!mail($email, $subject, $message, $headers)) {
				die ("Sending Email Failed, Please contact the administartor! ($site_email)");
			}
			else {
				$msg = "Recovery e-mail has been sent to <b>$email</b><br><br>
				Delivering the message can take some time, depending on the mailing service.<br>
				Please remember to check your Spam folder as well.";
				mysqli_query($db_link, "INSERT INTO login VALUES ('$email','recover','$ip_client','$date')") or die(mysqli_error($db_link));
			}
		}

		CleanUpDB();
	}

	echo $msg;
}

else {
?>

<br>
<form method='post' action='recover.php'>
<table class='form'>
<tr><td class="col1"><label>Email Address: </label></td><td class="col2"><input name='email' type='text' id='email' class='input' style="width:200px;" maxlength='50'/>

<tr><td class="col1"></td><td class="col2"><br><img id="captcha" title="captcha" src="res/securimage/securimage_show.php" alt="CAPTCHA Image" /></td></tr>
<tr><td class="col1"></td><td class="col2"><input type="text" placeholder="Captcha" name="captcha_code" size="10" maxlength="6" /> &nbsp;&nbsp;
<a href="#" title="reload" onclick="document.getElementById('captcha').src = 'res/securimage/securimage_show.php?' + Math.random(); return false">[&#8635;]</a></td></tr>

<tr><td class="col1"></td><td class="col2"><br><input type='submit' name='Submit' value='Submit' class='btn btn-blue'/></td></tr>
</table>
</form>

<?php
}
include 'footer.php';
?>