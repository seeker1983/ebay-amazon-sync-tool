<?php 
session_start();
include('inc.db.php');
  $active_user = $_SESSION['user_id'];
  
$itemnumber=rawurlencode($_POST['itemnumber']);
                    $res=mysql_query("select * from asins_table where asins='".$asin."' and UserID=".$active_user."");
                    if(!mysql_num_rows($res))
                     {    
                      mysql_query("INSERT INTO asins_table(asins,UserID,processed,provider) values('".$itemnumber."',".$active_user.",0,'Overstock')");
					  $result = array("state"=>"Ok", "data"=>"");
                       echo json_encode($result);
                     }
						else { 
		$result = array("state"=>"error", "data"=>"Product already exist in the list");
        echo json_encode($result);
        exit(200);
		}
 
?>					 