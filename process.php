<?php

if(isset($_POST['payment_status']) && isset($_POST['receiver_email'])) {

	require_once 'includes/functions.php';
	require_once 'includes/config.php';
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'IpnListener.php');
	$listener = new \WadeShuler\PhpPaypalIpn\IpnListener();
	$listener->use_sandbox = false;


	// Inspect IPN validation result and act accordingly
	if ($verified = $listener->processIpn()) {
		//$transactionRawData = $listener->getRawPostData();      // raw data from PHP input stream
		//$transactionData = $listener->getPostData();            // POST data array
		//file_put_contents(dirname(__FILE__).'/logs/ipn_errors.log', print_r($transactionData, true) . PHP_EOL, LOCK_EX | FILE_APPEND);
		GetMyConnection();
		// assign posted variables to local variables
		$txn_id = $_POST['txn_id'];
		$txn_type = $_POST['txn_type'];
		$payer_email = $_POST['payer_email'];
		$payer_email = StrToLower(Trim($payer_email));
		$item_name = $_POST['item_name'];
		$item_name = mysqli_real_escape_string($db_link, $item_name);
		$payment_amount = $_POST['mc_gross'];
		$payment_currency = $_POST['mc_currency'];
		$payment_date = date("y-m-d H:i:s");
		$payment_status = $_POST['payment_status'];
		$array_status = array("Completed","Pending");
		$receiver_email = $_POST['receiver_email'];
		$array_email = array($payment_email);
		$userid = preg_replace('/[^0-9]+/','',$_POST['custom']);


		if (!empty($userid) && contains($payment_status, $array_status) && contains($receiver_email, $array_email)) {

			if (strpos($payment_status,'Completed') !== false) {

				mysqli_query($db_link, "INSERT INTO transaction (txn_id, userid, payer_email, item_name, payment_amount, payment_currency, payment_status, payment_date) VALUES ('$txn_id', '$userid', '$payer_email', '$item_name', '$payment_amount', '$payment_currency', '$payment_status', '$payment_date')") or die("Couldn't perform");


				$to      = "$payer_email";
				$subject = "Order Confirmation - $site_name";
				$message = <<<EOD

Hello,

If you have received this email it means that your order has been confirmed and will be processed soon.

Order details:
----------------------------
Email: {$email}
Date: {$payment_date}
Item Name: {$item_name}
Amount: {$payment_amount} {$payment_currency}
----------------------------

EOD;

				mail($to, $subject, $message, $headers);

			}

			elseif (strpos($payment_status,'Pending') !== false) {

				$to      = "$payer_email";
				$subject = "Order being processed - {$site_name}";
				$message = <<<EOD

Hello,

If you have received this email it means that the payment for your order is currently being processed.
Item will be sent as soon we receive confirmation of the transaction.

Order details:
----------------------------
Email: {$email}
Date: {$payment_date}
Item Name: {$item_name}
Amount: {$payment_amount} {$payment_currency}
----------------------------

EOD;

				mail($to, $subject, $message, $headers);

			}
		}

		CleanUpDB();
	}

	else {
		$errors = $listener->getErrors();
		file_put_contents(dirname(__FILE__).'/logs/ipn_errors.log', print_r($errors, true) . PHP_EOL, LOCK_EX | FILE_APPEND);
	}

}

echo "no PHP errors in the script";
?>