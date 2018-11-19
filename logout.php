<?php
include 'header.php';

if (!isset($_SESSION)) sec_session_start();

// Unset all session values
$_SESSION = array();

// get session parameters
$params = session_get_cookie_params();

// Delete the actual cookie.
setcookie(session_name(),
        '', time() - 42000,
        $params["path"],
        $params["domain"],
		$params["secure"],
        $params["httponly"]);

// Destroy session
session_destroy();
header('Location: index.php');
?>

<div class="box round first">
<h2>Log Off</h2>
<div class="block">

<p>You have logged off.</p>

<?php include 'footer.php'; ?>