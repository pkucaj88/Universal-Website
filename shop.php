<?php
include 'header.php';
?>

<div class="box round first">
<h2>Shop</h2>
<div class="block">

<?php

if (isset($userid)) {

	GetMyConnection();

	$result = mysqli_query($db_link, "SELECT * FROM product WHERE active='1' ORDER BY itemid") or ("Can't execute query1.");
	$items = '';


	while ($item = mysqli_fetch_array($result)) {
		$items .= <<<HTML
<tr><td><a href="img/shop/{$item['image']}" target="_blank"><img src="img/shop/thumb/{$item['image']}"></a></td><td>{$item['item_name']}</td><td>{$item['description']}</td><td>{$item['price']}</td>

<td>
<a href="https://paypal.com/" target="_blank"><img src="img/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"></a>
</td>
</tr>

HTML;

	}

/*
	while ($item = mysqli_fetch_array($result)) {
		$items .= <<<HTML
<tr><td><a href="img/shop/{$item['image']}" target="_blank"><img src="img/shop/thumb/{$item['image']}"></a></td><td>{$item['item_name']}</td><td>{$item['description']}</td><td>{$item['price']}</td>

<td>
<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="{$payment_email}">
<input type="hidden" name="lc" value="US">
<input type="hidden" name="item_name" value="{$item['item_name']}">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="cancel_return" value="{$site_address}/donate.php">
<input type="hidden" name="return" value="{$site_address}/purchase-success.php">
<input type="hidden" name="notify_url" value="{$site_address}/process.php">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
<input type="hidden" name="amount" value="{$item['price']}">
<input type="hidden" name="on1" value="Item Name"><input type="hidden" name="os1" maxlength="200" value="{$item['item_name']}" readonly>
<input type="hidden" name="on2" value="User Email"><input type="hidden" name="os2" maxlength="200" value="{$email}" readonly>
<input type="hidden" name="custom" value="{$userid}" readonly>
<input type="image" src="img/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
</form>
</td>
</tr>

HTML;

	}
*/

	CleanUpDB();
?>

<table class="simple">
	<thead>
		<tr>
			<td>&nbsp;</td><td>Item</td><td>Description</td><td>Price</td>
		</tr>
	</thead>
	<tbody>
<?php echo $items; ?>
	</tbody>
</table>

<?php
}

else include 'login.php';
include 'footer.php';

?>