<?php 
set_time_limit(0);
include('inc.db.php');

include('ebayFunctions.php');
include "simple_html_dom.php";

/*
$item_id=121470641474;
$date=date("d/m/Y h:i:s");
$message="".$date."  item for id=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a> is ended<br>";
 file_put_contents("log.php",$message,FILE_APPEND);
 
  $headers= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail('chebl.mahmoud@gmail.com', 'Deleting Item', $message,$headers);	
die;
*/

delete_item_prime();   

 echo '<script>  
					window.location.href = "view_ebay_data.php";
				  </script>';		



function delete_item_prime() {

$result=array();
 //$cron_file = 'commandsync.txt';
   // touch($cron_file); 
    // chmod($cron_file, 0777); 
$active_user = 8;
 
$sql = "SELECT * FROM user_products where UserID=$active_user";

 $res = mysql_query($sql) or die('Something Wrong...!');

 while ($row = mysql_fetch_array($res)) {
 $itemid=$row['ItemID'];
  $url='http://ezon.org/cl/ezonsync/cron_price_qty.php?itemid='.$itemid;	
  $sql="SELECT * FROM ebay_cron WHERE ItemID=$itemid";
    $rs = mysql_query($sql);
    if (!mysql_num_rows($rs)) {
       $sql="INSERT INTO ebay_cron SET url='$url',ItemID=$itemid,UserID=$active_user"
       mysql_query($sql);
       }
	 
 }


?>