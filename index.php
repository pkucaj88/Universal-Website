<?php
include 'header.php';

GetMyConnection();
$result = mysqli_query($db_link, "SELECT * FROM news WHERE active='1' ORDER BY date DESC") or ("Can't execute query1.");

while ($array = mysqli_fetch_assoc($result))
{
	echo '
		<div class="box round first">
			<h3>', $array["title"] ,'</h3>
			<div class="newsinfo">
				', $array["author"] ,', on ', date("l, j F Y", strtotime($array["date"])) ,'
			</div>
			<div class="news">
				', $array["content"] ,'
			</div>
		</div>';
}

CleanUpDB();
?>

	</div>
</body>
</html>