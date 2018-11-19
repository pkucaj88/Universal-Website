<?php
include 'header.php';
?>

<div class="box round first">
<h2>Gallery</h2>
<div class="block">

<?php

GetMyConnection();

$result = mysqli_query($db_link, "SELECT * FROM gallery ORDER BY imgid") or die("Couldn't perform");

while ($array = mysqli_fetch_assoc($result))
{
	echo '
	<a href="img/gallery/', $array["image"] ,'" data-lightbox="lightbox" data-title="', $array["image_name"] ,'"><img src="img/gallery/thumb/', $array["image"] ,'" alt="', $array["image_name"] ,'"></a>';
}


CleanUpDB();

?>

<script src="res/lightbox/lightbox.min.js"></script>

<?php
include 'footer.php';
?>