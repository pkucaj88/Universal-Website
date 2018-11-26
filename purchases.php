<?php
include 'header.php';
?>

<div class="box round first">
<h2>Purchase History</h2>
<div class="block">

<?php
if (isset($userid)) {
	GetMyConnection();
?>

	<table class="display datatable">
		<thead>
			<tr>
				<th>Payment Date </th>
				<th>Transaction ID </th>
				<th>Payer Email </th>
				<th>Item Name </th>
				<th>Payment Amount </th>
			</tr>
		</thead>
		<tbody>

<?php

	$result = mysqli_query($db_link, "SELECT * FROM transaction WHERE userid='$userid' ORDER BY payment_date DESC") or die("Couldn't perform");
	$array = mysqli_fetch_assoc($result);

	do {
		echo '<tr><td>';
		echo $array['payment_date'];
		echo '</td><td>';
		echo $array['txn_id'];
		echo '</td><td>';
		echo $array['payer_email'];
		echo '</td><td>';
		echo $array['item_name'];
		echo '</td><td>';
		echo $array['payment_amount'] , ' ' , $array['payment_currency'];
		echo '</td></tr>';
	} while ($array = mysqli_fetch_assoc($result));

	echo '
		</tbody>
	</table>';

	CleanUpDB();
?>

	<script src="res/jquery.dataTables.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$('.datatable').DataTable( {
			"lengthMenu": [ [15, 25, -1], [15, 25, "All"] ],
			 "order": [[ 0, "desc" ]]
		} );
	} );
	</script>

<?php
}

else include 'login.php';
include 'footer.php';
?>