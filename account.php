<?php
include 'header.php';
?>

<div class="box round first">
<h2>Account Details</h2>
<div class="block">

<?php

if (isset($userid)) {

GetMyConnection();

	if (isset($_POST['name'])) {

		$name = preg_replace('!\s+!', ' ', trim($_POST['name']));
		$surname = preg_replace('!\s+!', ' ', trim($_POST['surname']));
		$city = preg_replace('!\s+!', ' ', trim($_POST['city']));
		$street = preg_replace('!\s+!', ' ', trim($_POST['street']));
		$postal = preg_replace('!\s+!', ' ', trim($_POST['postal']));
		$country = preg_replace('!\s+!', ' ', trim($_POST['country']));
		$phone = preg_replace('!\s+!', ' ', trim($_POST['phone']));

		$postal = preg_replace('/[^0-9-]+/','',$postal);
		$phone = preg_replace('/[^0-9+]+/',' ',$phone);

		$msg = 'Input data error';

		if ((StrLen($name) < 2) or (StrLen($name) > 60)) {
			$msg = '<span class="message error">First Name must have at least 2 and not more than 60 characters.</span><br>';
		}
		elseif ((StrLen($surname) < 2) or (StrLen($surname) > 60)) {
			$msg = '<span class="message error">Last Name must have at least 2 and not more than 60 characters.</span><br>';
		}
		elseif ((StrLen($city) < 2) or (StrLen($city) > 60)) {
			$msg = '<span class="message error">City must have at least 2 and not more than 60 characters.</span><br>';
		}
		elseif ((StrLen($street) < 2) or (StrLen($street) > 60)) {
			$msg = '<span class="message error">Street must have at least 2 and not more than 60 characters.</span><br>';
		}
		elseif ((StrLen($postal) < 2) or (StrLen($postal) > 30)) {
			$msg = '<span class="message error">Postal Code must have at least 2 and not more than 30 characters.</span><br>';
		}
		elseif ((StrLen($country) < 2) or (StrLen($country) > 30)) {
			$msg = '<span class="message error">Country must have at least 2 and not more than 30 characters.</span><br>';
		}
		elseif ((StrLen($phone) < 2) or (StrLen($phone) > 30)) {
			$msg = '<span class="message error">Phone Number must have at least 2 and not more than 30 characters.</span><br>';
		}

		else {

			$name = mysqli_real_escape_string($db_link, $name);
			$surname = mysqli_real_escape_string($db_link, $surname);
			$city = mysqli_real_escape_string($db_link, $city);
			$street = mysqli_real_escape_string($db_link, $street);
			$postal = mysqli_real_escape_string($db_link, $postal);
			$country = mysqli_real_escape_string($db_link, $country);
			$phone = mysqli_real_escape_string($db_link, $phone);

			mysqli_query($db_link, "UPDATE user SET name = '$name', surname = '$surname', city = '$city', street = '$street', postal = '$postal', country = '$country', phone = '$phone' WHERE userid = '$userid'") or die("Couldn't perform");
			$msg = '<span class="message info">Account information successfully updated.</span><br>';

		}

		echo $msg;
	}


	$result = mysqli_query($db_link, "SELECT * FROM user WHERE userid='$userid'") or ("Can't execute query1.");
	$array = mysqli_fetch_array($result);
	$name = $array['name'];
	$surname = $array['surname'];
	$city = $array['city'];
	$street = $array['street'];
	$postal = $array['postal'];
	$country = $array['country'];
	$phone = $array['phone'];


	echo '<br>
	<form method="post" action="account.php">
	<table class="form">
	<tr><td class="col1"><label>First Name: </label></td><td class="col2"><input name="name" value="',$name,'" type="text" class="input" maxlength="60"/> </td></tr>
	<tr><td class="col1"><label>Last Name: </label></td><td class="col2"><input name="surname" value="',$surname,'" type="text" class="input" maxlength="60"/> </td></tr>
	<tr><td class="col1"><label>City: </label></td><td class="col2"><input name="city" value="',$city,'" type="text" class="input" maxlength="60"/> </td></tr>
	<tr><td class="col1"><label>Street: </label></td><td class="col2"><input name="street" value="',$street,'" type="text" class="input" maxlength="60"/> </td></tr>
	<tr><td class="col1"><label>Postal Code: </label></td><td class="col2"><input name="postal" value="',$postal,'" type="text" class="input" maxlength="30"/> </td></tr>
	<tr><td class="col1"><label>Country: </label></td><td class="col2"><input name="country" value="',$country,'" type="text" class="input" maxlength="30"/> </td></tr>
	<tr><td class="col1"><label>Phone Number: </label></td><td class="col2"><input name="phone" value="',$phone,'" type="text" class="input" maxlength="30"/> </td></tr>
	<tr><td class="col1"><label> </label></td><td class="col2"><br><input type="submit" name="Submit" value="Confirm" class="btn btn-blue"/></td></tr>
	</table>
	</form>
	';

	CleanUpDB();
}

else include 'login.php';
include 'footer.php';
?>

