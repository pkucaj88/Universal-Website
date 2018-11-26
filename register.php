<?php
include 'header.php';
require_once 'includes/register_ban.php';
include_once 'res/securimage/securimage.php';
$securimage = new Securimage();
?>

<div class="box round first">
<h2>Register</h2>
<div class="block">

<?php

if (isset($_POST['email'])) {

	$email = $_POST['email'];
	$pass = $_POST['pass'];
	$repass = $_POST['repass'];

	$email = StrToLower(Trim($email));
	$pass = Trim($pass);
	$repass = Trim($repass);
	$msg = 'Registration error';


	if (empty($email) || empty($pass) || empty($repass)) {
		$msg = '<span class="message error">One or more fields are empty.</span>';
	}

	elseif (!(isset($_POST['tos']))) {
		$msg = '<span class="message error">You must agree to the terms of service if you want to register an account.</span>';
	}

	elseif ((StrLen($email) < 6) or (StrLen($email) > 50)) {
		$msg = '<span class="message error">Email Address must have at least 6 and not more than 50 characters.</span>';
	}

	elseif (!preg_match($pattern_email, $email)) {
	 	$msg = '<span class="message error">Incorrect Email Address.</span>';
	}

	elseif (preg_match($pattern, $pass)) {
		$msg = '<span class="message error">Password can contain only a-Z 0-9 _  . - + ! @ # $ characters.</span>';
	}

	elseif (preg_match($pattern, $repass)) {
		$msg = '<span class="message error">Confirmation password error.</span>';
	}

	elseif ((StrLen($pass) < 6) or (StrLen($pass) > 30)) {
		$msg = '<span class="message error">Password must have at least 4 and not more than 30 characters.</span>';
	}

	elseif ((StrLen($repass) < 6) or (StrLen($repass) > 30)) {
		$msg = '<span class="message error">Password must have at least 4 and not more than 30 characters.</span>';
	}

	elseif ($repass != $pass) {
		$msg = '<span class="message error">Confirmation password error.</span>';
	}

	elseif (contains($email, $banned_domains)) {
		$msg = '<span class="message error">This email <b>'.$email.'</b> contains banned word or is a spam address. Please use different email.</span>';
		error_log(date('[Y-m-d H:i] '). "Banned Email domain - username: $Login, email: $email, IP: $ip_client" . PHP_EOL, 3, BAN_LOG);
	}

	elseif ($securimage->check($_POST['captcha_code']) == false) {
		$msg = '<span class="message error">The security code entered was incorrect. Please make sure you have enabled JavaScript.</span>';
	}

	elseif (!domain_exists($email)) {
		$msg = '<span class="message error">Please use your real e-mail address.</span>';
	}

	else {

		GetMyConnection();

		$email = mysqli_real_escape_string($db_link, $email);
		$pass = mysqli_real_escape_string($db_link, $pass);

		$result1 = mysqli_query($db_link, "SELECT email FROM user WHERE email='$email'") or ("Can't execute query.");
		$result2 = mysqli_query($db_link, "SELECT email FROM validation WHERE email='$email' AND type = 1") or ("Can't execute query.");

		if (mysqli_num_rows($result1) || mysqli_num_rows($result2)) {
			$msg = '<span class="message error">Account <b>'.$email.'</b> already exists</span>';
		}

		else {

			$code = md5($email.time());
			$salt = md5($email.$pass);

			mysqli_query($db_link, "INSERT INTO validation VALUES ('$email', '$salt', '$ip_client', '$date', '$code', '1', '0')") or die("Couldn't perform");

			$to      = "$email";
			$subject = "Account Activation - $site_name";
			$message = <<<EOD

Welcome {$email},

to activate your account, please click on the link below:
{$site_address}activate.php?code={$code}

EOD;

			mail($to, $subject, $message, $headers);

			CleanUpDB();

			$msg = '<div class="message info">Account <b>'.$email.'</b> has been registered, but needs to be activated.<br>
			Please check your Spam folder for a message.</div>';

		}
	}
	
	echo $msg ,'<br>';
}

?>

<br>
<form method='post' action='register.php'>
<table class='form'>
<tr><td class="col1"><label>Email Address: </label></td><td class="col2"><input name='email' type='text' id='email' class='input' maxlength='50'/> <span id="email-result"></span></td></tr>
<tr><td class="col1"><label>Password: </label></td><td class="col2"><input name='pass' type='password' id='pass' class='input' maxlength='30'/> <span id="pass-result"></span></td></tr>
<tr><td class="col1"><label>Repeat Password: </label></td><td class="col2"><input name='repass' type='password' id='repass' class='input' maxlength='20' onblur='checkPass(); return false;'/> <span id="repass-result"></span></td></tr>

<tr><td class="col1"><label> </label></td><td class="col2"><br><img id="captcha" title="captcha" src="res/securimage/securimage_show.php" alt="CAPTCHA Image" /></td></tr>
<tr><td class="col1"><label> </label></td><td class="col2"> <input type="text" placeholder="Captcha" name="captcha_code" size="10" maxlength="6" /> &nbsp;&nbsp; <a href="#" title="reload" onclick="document.getElementById('captcha').src = 'res/securimage/securimage_show.php?' + Math.random(); return false">[&#8635;]</a></td></tr>

<tr><td class="col1"><label> </label></td><td class="col2"><br><label style="padding:6px 0 6px 6px"><input type="checkbox" name="tos" /> I agree to the </label> <a href="#" onclick="window.open('tos.php', 'newwindow', 'width=550, height=390'); return false;">Terms of service</a>.</td></tr>
<tr><td class="col1"><label> </label></td><td class="col2"><br><input type='submit' name='Submit' value='Register' class='btn btn-blue'/></td></tr>
</table>
</form>

<br><br>
<a href='resend.php'>› Re-send activation email</a>

<script type="text/javascript">
$("#email").blur(function (e) {
   var email = $(this).val();
   $.post('includes/regcheck.php', {'email':email}, function(data) {
   $("#email-result").html(data);
   });
});
$("#pass").blur(function (e) {
   var pass = $(this).val();
   $.post('includes/regcheck.php', {'pass':pass}, function(data) {
   $("#pass-result").html(data);
   });
});

function checkPass()
{
    //Store the password field objects into variables ...
    var pass = document.getElementById('pass');
    var repass = document.getElementById('repass');
    var message = document.getElementById('repass-result');
    if(pass.value == repass.value){
        message.innerHTML = '<img src="img/available.png" />'
    }else{
        message.innerHTML = '<span class="message error field">Passwords do not match</span>'
    }
}
</script>
<?php include 'footer.php'; ?>

