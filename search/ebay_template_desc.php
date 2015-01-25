<?php
session_start();
if(!isset($_SESSION['username'])) {
	header("Location:index.php");
}

$active_user = $_SESSION['user_id'];

include('inc.db.php');

	$asin = $_REQUEST['desc_asin'];
	
	$sql = "SELECT ebay_description FROM ebay_asin WHERE asins = '$asin' AND UserID = $active_user";
	$rs = mysql_query($sql);
	
	$row = mysql_fetch_array($rs);
	
		$description = $row['ebay_description'];
		
		echo $description;
		
		


?>