<?php

session_start();
$active_user = $_SESSION['user_id'];
include('inc.db.php');

 $sql_asin = "DELETE FROM ebay_asin WHERE UserID=$active_user";

     mysql_query($sql_asin) or die(mysql_error());
	 
 $sql_asin = "DELETE FROM aws_asin WHERE UserID=$active_user";

     mysql_query($sql_asin) or die(mysql_error());	 
$result = array("state"=>"Ok", "data"=>'');
	 echo json_encode($result);

?>