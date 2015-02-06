<?php 
session_start();

$active_user = $_SESSION['user_id'];

include('inc.db.php');
$_REQUEST["value"]=str_replace("'","\'",$_REQUEST["value"]);
if($_REQUEST['column']=="0") {
            $field = '`title`';
        } elseif($_REQUEST['column']=="1") {
            $field = '`brand`';
        }
		elseif($_REQUEST['column']=="2") {
            $field = '`features`';
        }
		elseif($_REQUEST['column']=="3") {
            $field = '`description`';
        }
		elseif($_REQUEST['column']=="5") {
            $field = '`offer_price`';
        }
       elseif($_REQUEST['column']=="6") {
            $field = '`prime`';
        }
		elseif($_REQUEST['column']=="7") {
            $field = '`quantity`';
        }
		  $sql="UPDATE `aws_asin` SET ".$field." ='".$_REQUEST["value"]."' WHERE `UserID` =".$active_user." and `title` ='".$_REQUEST['row_id']."'";
           
	  
    mysql_query($sql) or die('Something Wrong...!');
	
		
?>