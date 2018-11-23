<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<?php
	require_once 'includes/config.php';
	echo '<title>', $site_name ,'</title>
	<meta name="description" content="', $site_description ,'"/>',"\n";?>
	<link rel="stylesheet" type="text/css" href="res/reset.css?v1" />
	<link rel="stylesheet" type="text/css" href="res/layout.css?v1" media="screen"/>
	<link rel="stylesheet" type="text/css" href="res/sm-core-css.css" />
	<link rel="stylesheet" type="text/css" href="res/sm-mint.css?v2" />
	<link rel="stylesheet" type="text/css" href="res/lightbox/lightbox.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
</head>
<body>
	<div class="container">
		<div class="header">
			<div id="branding">
				<?php echo '<a href="index.php">', $logo_text ,'</a>',"\n";?>
			</div>
			<div class="login">
			<?php
				require_once 'includes/functions.php';
				
				if (!isset($_SESSION))
						sec_session_start();

				if (isset($_SESSION['userid']) && isset($_SESSION['email'])) {

					$userid = $_SESSION['userid'];
					$email = $_SESSION['email'];
					echo '
				<div>
					<ul class="header-ul">
						<li>Logged in as: <span class="grey">', $email ,'</span></li>
						<li><a class="floatright" href="logout.php">Logout</a></li>
					</ul>
				</div>
				';
				}
				else {
					echo '
				<div>
					<ul class="header-ul">
						<li><br><a class="floatright" href="account.php">Log in</a></li>
					</ul>
				</div>
				';
				}
				?>
			</div>
			<div class="clear"></div>
		</div>
		<nav>
			<ul id="main-menu" class="sm sm-mint">
				<li><a href="index.php">News</a></li>
				<li><a href="gallery.php">Gallery</a></li>
				<li><a href="about.php">About Us</a></li>
				<li><a href="#">Shop</a>
					<ul>
						<li><a href="shop.php">Purchase Product</a></li>
						<li><a href="purchases.php">Purchase History</a></li>
					</ul>
				</li>
				<li><a href="#">Account</a>
					<ul>
						<li><a href="account.php">Account Details</a></li>
						<li><a href="changepw.php">Change Password</a></li>
					</ul>
				</li>
			<?php if (!isset($userid))
				echo '<li><a href="register.php">Register</a></li>';
			?>
			</ul>
		</nav>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="res/jquery.smartmenus.js?v4"></script>
		<script>
			$(function() {
				$('#main-menu').smartmenus({
					subMenusSubOffsetX: 1,
					subMenusSubOffsetY: -8
				});
			});
		</script>
