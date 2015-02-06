<?php
require_once 'redirect.php';

$active_user = $_SESSION['user_id'];

include('inc.db.php');

	$asin = $_REQUEST['desc_asin'];
	
	$sql = "SELECT description FROM aws_asin WHERE asin = '$asin' AND UserID = $active_user";
	$rs = mysql_query($sql);
	
	$row = mysql_fetch_array($rs);
	
		$description = $row['description'];
		
		echo $description;
		
		


?>