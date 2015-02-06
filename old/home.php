<?php
session_start();
if (!isset($_SESSION['username']))
    header("Location:index.php");


$active_user = $_SESSION['user_id'];
//

require_once 'inc.db.php';
$sql_ebay_users = "SELECT * FROM ebay_users WHERE user_id=$active_user";
$rs_ebay_users = mysql_query($sql_ebay_users) or die(mysql_error());

if (mysql_num_rows($rs_ebay_users) != 1){
    session_destroy();
    header("Location:index.php");
}
    
header("Location:view_ebay_data.php");
    