<?php
include 'header.php';
?>

<div class="box round first">
<h2>Account Recovery</h2>
<div class="block">

<?php

$msg = '<span class="message error">Verification failed. Please try resetting your password again.</span>';

if (!empty($_GET['code']) && isset($_GET['code']))
	$code = $_GET['code'];


if (isset($_POST['code'])) {

	$code = $_POST['code'];
	$newPassword=$_POST['newpassword'];
	$confirmNew=$_POST['confirmnew'];

	$newPassword = stripslashes(Trim($newPassword));
	$confirmNew = stripslashes(Trim($confirmNew));

	if (preg_match($pattern_code, $code) || (StrLen($code) != 32)) {
		$msg = '<span class="message error">Verification failed. Please try resetting your password again.</span>';
	}

	elseif (empty($newPassword) || empty($confirmNew)) {
		$msg = '<span class="message error">One or more fields are empty.</span>';
	}

	elseif (preg_match($pattern, $newPassword)) {
		$msg = '<span class="message error">Password can contain only a-Z  0-9 _ . - + ! @ # $ characters.</span>';
	}

	elseif (preg_match($pattern, $confirmNew)) {
		$msg = '<span class="message error">Password can contain only a-Z  0-9 _ . - + ! @ # $ characters.</span>';
	}

	elseif ((StrLen($newPassword) < 6) or (StrLen($newPassword) > 30)) {
		$msg = '<span class="message error">New password must have at least 6, and not more than 30 characters.</span>';
	}

	elseif ($newPassword !== $confirmNew) {
		$msg = '<span class="message error">New Password and Confirm New Password fields must match.</span>';
	}

	else {

		GetMyConnection();
		$code = mysqli_real_escape_string($db_link, $code);
		$result1 = mysqli_query($db_link, "SELECT * FROM validation WHERE code='$code' AND type='2' AND activated='0'") or ("Can't execute query1.");

		if (mysqli_num_rows($result1) == 1) {

			$array1 = mysqli_fetch_array($result1);
			$email = $array1['email'];

			$newPassword = mysqli_real_escape_string($db_link, $newPassword);
			$encryptNewPassword = md5($email . $newPassword);

			$getAccountInfo = mysqli_query($db_link, "SELECT * FROM user WHERE email = '$email' AND active = '1'");
			$getAccountNum = mysqli_num_rows($getAccountInfo);

			if ($getAccountNum == 1) {

				$array2 = mysqli_fetch_array($getAccountInfo);
				$userid = $array2['userid'];

				mysqli_query($db_link, "UPDATE user SET password = '$encryptNewPassword' WHERE userid = '$userid'") or die("Couldn't perform");
				mysqli_query($db_link, "UPDATE validation SET activated = 1 WHERE code='$code'") or die ("Can't execute query5.");
				mysqli_query($db_link, "INSERT INTO login VALUES ('$email', 'recover','$ip_client','$date')") or die(mysqli_error($db_link));
				$msg = '<span class="message info">Password for account: <span style="color:#1B548D;font-weight:bold">'.$email.'</span> has been changed!</span>';

				$to      = "$email";
				$subject = "New Password - $site_name";
				$message = <<<EOD

Hello {$email},

the password for your account has been changed successfully.

EOD;

				mail($to, $subject, $message, $headers);
			}

			else {
				$msg = '<span class="message error">Unknown error 2. Please contact the administrator.</span>';
			}
		}

		else {
			$msg = '<span class="message error">This validation code has expired.</span>';
		}

		CleanUpDB();
	}

	echo $msg ,'<br>';
}

?>

<br>
<form method='post' action='recover2.php'>
<table class='form'>
<tr><td class="col1"><label>New Password: </label></td><td class="col2"><input name='newpassword' type='password' class='input'/></td></tr>
<tr><td class="col1"><label>Confirm New Password: </label></td><td class="col2"><input name='confirmnew' type='password' class='input'/></td></tr>
<?php echo '<tr><td><input name="code" type="hidden" value="',$code,'" /></tr></td>'; ?>
<tr><td class="col1"><label></label></td><td class="col2"><br><input type='submit' name='Submit' value='Confirm' class='btn btn-blue'/></td></tr>
</table>
</form>


<?php
include 'footer.php';
?>