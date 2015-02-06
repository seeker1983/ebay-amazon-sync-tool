<?php 
set_time_limit(0);
include('inc.db.php');

include('ebayFunctions.php');
include "simple_html_dom.php";


delete_item_prime();   

 echo '<script>  
					window.location.href = "view_ebay_data.php";
				  </script>';		



function delete_item_prime() {

$result=array();
 $cron_file = 'commandsync.txt';
   

 
$sql = "SELECT * FROM ebay_cron";

 $res = mysql_query($sql) or die('Something Wrong...!');
 $minute=0;
 $hour=0;
 while ($row = mysql_fetch_array($res)) {
 
  $url=$row['url'];	
	  if($hour>23){
	  $hour=0;
	  }
	  
	  if($minute>=60)
	  {
	   $minute=0;
	  }
	  $hour1=$hour;
	  $hour2=$hour1+4;
	  if($hour2>23){
	  $hour2=$hour2-24;
	  }
	  $hour3=$hour2+4;
	  if($hour3>23){
	  $hour3=$hour3-24;
	  }
	  $hour4=$hour3+4;
	  if($hour4>23){
	  $hour4=$hour4-24;
	  }
	  $hour5=$hour4+4;
	  if($hour5>23){
	  $hour5=$hour5-24;
	  }
	  $hour6=$hour5+4;
	  if($hour6>23){
	  $hour6=$hour6-24;
	  }
	  file_put_contents($cron_file,$minute.' '.$hour1.','.$hour2.','.$hour3.','.$hour4.','.$hour5.','.$hour6.' * * * wget '.$url."\n",FILE_APPEND);
	  $minute+=10;
	  $hour++;
    	//exec('crontab'.$cron_file);
       	   
	}
	
	exec('crontab '.$cron_file);
  
 }


?>