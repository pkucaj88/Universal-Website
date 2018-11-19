<?php
include 'header.php';
?>

<div class="box round first">
<h2>Change Password</h2>
<div class="block">

<?php
if (isset($userid)) {
	if (isset($_POST['oldpassword'])) {

	$msg = 'Password change error';
	$oldPassword=$_POST['oldpassword'];
	$newPassword=$_POST['newpassword'];
	$confirmNew=$_POST['confirmnew'];

	$oldPassword = stripslashes(Trim($oldPassword));
	$newPassword = stripslashes(Trim($newPassword));
	$confirmNew = stripslashes(Trim($confirmNew));


		if (empty($oldPassword) || empty($newPassword) || empty($confirmNew)) {
			$msg = '<span class="message error">One or more fields are empty.</span><br>';
		}

		elseif (preg_match($pattern, $oldPassword)) {
			$msg = '<span class="message error">Incorrect current password.</span><br>';
		}

		elseif (preg_match($pattern, $newPassword)) {
			$msg = '<span class="message error">Password can contain only a-Z  0-9 _  . - + ! @ # $ characters.</span><br>';
		}

		elseif (preg_match($pattern, $confirmNew)) {
			$msg = '<span class="message error">Password can contain only a-Z  0-9 _  . - + ! @ # $ characters.</span><br>';
		}

		elseif ((StrLen($oldPassword) < 4) or (StrLen($oldPassword) > 30)) {
			$msg = '<span class="message error">Password must have at least 4, and not more than 30 characters.</span><br>';
		}

		elseif ((StrLen($newPassword) < 6) or (StrLen($newPassword) > 30)) {
			$msg = '<span class="message error">New password must have at least 6, and not more than 30 characters.</span><br>';
		}

		elseif ($newPassword !== $confirmNew) {
			$msg = '<span class="message error">New Password and Confirm New Password fields must match.</span><br>';
		}


		else {

			GetMyConnection();
			$oldPassword = mysqli_real_escape_string($db_link, $oldPassword);
			$newPassword = mysqli_real_escape_string($db_link, $newPassword);

			//Encrypt Password and email
			$encryptOldPassword = md5($email . $oldPassword);
			$encryptNewPassword = md5($email . $newPassword);

			$getAccountInfo = mysqli_query($db_link, "SELECT * FROM user WHERE userid = '$userid' and active = '1'");
			$getAccountNum = mysqli_num_rows($getAccountInfo);

			if ($getAccountNum == 1) {

				$array = mysqli_fetch_array($getAccountInfo);
				$checkPassword = $array['password'];
				$email = $array['email'];

				if ($encryptOldPassword == $checkPassword) {

					mysqli_query($db_link, "UPDATE user SET password = '$encryptNewPassword' WHERE userid = '$userid'") or die("Couldn't perform");
					mysqli_query($db_link, "INSERT INTO login VALUES ('$email', 'changepw','$ip_client','$date')") or die(mysqli_error($db_link));
					$msg = '<span class="message info">Password for account: <span style="color:#1B548D;font-weight:bold">'.$email.'</span> has been changed!</span><br>';


					$to      = "$email";
					$subject = "New Password - $site_name";
					$message = <<<EOD

Hello {$email},

the password for your account has been changed successfully.

EOD;

					mail($to, $subject, $message, $headers);

				}

				else {
					$msg = '<span class="message error">Wrong password. Please try again.</span><br>';
				}
			}

			else {
				$msg = '<span class="message error">Unknown error. Please contact the administrator.</span><br>';
			}

			CleanUpDB();
		}

		echo $msg;
	}
?>
<br>
<form method='post' action='changepw.php'>
<table class='form'>
<tr><td class="col1"><label>Current Password: </label></td><td class="col2"><input name='oldpassword' type='password' id='pass1' class='input' maxlength='30'/></td></tr>
<tr><td class="col1"><label>New Password: </label></td><td class="col2"><input name='newpassword' type='password' id='pass2' class='input' maxlength='30'/></td></tr>
<tr><td class="col1"><label>Confirm New Password: </label></td><td class="col2"><input name='confirmnew' type='password' id='repass' class='input' maxlength='30'/></td></tr>
<tr><td class="col1"><label>  </label></td><td class="col2"><br><input type='submit' name='Submit' value='Confirm' class='btn btn-blue'/></td></tr>
</table>
</form>


<?php
}

else include 'login.php';
include 'footer.php';
?>

