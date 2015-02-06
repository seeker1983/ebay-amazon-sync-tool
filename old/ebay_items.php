<?php
ignore_user_abort(true); 
//set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '1024M');
//include('keys.php');

include "simple_html_dom.php";

include('inc.db.php');
include('ebayFunctions.php');


$active_user = 8;
$sql = "SELECT * FROM ebay_users WHERE user_id = $active_user ";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);

$DEVNAME = trim($row['dev_name']);
$APPNAME = trim($row['app_name']);
$CERTNAME = trim($row['cert_name']);

//$token = "AgAAAA**AQAAAA**aAAAAA**rk05VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AElYCmCJiFqQqdj6x9nY+seQ**y4ICAA**AAMAAA**I+dnHHqjbr1rjZlo8wEbyge8d1Rj73Vd3La+W4FzvDVDYl4cLgvS2pw9bttZX9kkkFTpeRtt+UDuYxU9N6O3FpzN7OklgZaQKMud9sjKb6O3WQYICnjw2KdMlEaeBKrTTmyM31hEcOiTvJB5u/0U0zFLWXvk1Qk6YAPhqTGejzYiwQtqYGtmd0rGtoS+b+46XxRov3saL5cRdOfSSTcHlWY9mtIqWerFpM4ZP0Qbj6xM4azxUa0m0BIZKf70+EbSBoFOaVo4X2k5ysHvSII+1WTbVBNvqgp9PNvgfyoMs+VZZ77Q+Qlfl+kk1pmGJqgqUY854qv0gABzSV5OEbgHIS2v0K2OhcTbo3EgvDX1SbPr/MKGFmC2aeqrVbn/9x5XZKnwPdtqoDwYJKe3sU/tzonToUKfyqldIF1GbJlvh5lgd2dPv+WS29g0wNNKGtPDyrLmQ0cktF0Ymnx5zvT7xffOtE9HweCCCILJeqFgyS7XeBgSY4d4fXcxXtaMaK9c2HFjwnF4v2WKoJ9KYYT9svSsf3ksk4fGzEv6XVPonZQxvyZESJHxQ7SbR/KE0rAB1WZY8N8x0hMu999SEJEse4lYRP7RjVxx82AVZp6MUVExfHSMQcQQL8O5O6wgjkErAHpfd7CzOmaSeyy4HNzWq3liP0WGQCfVXFDGl2YunJL9stIKaA/SrMeOvx5CSAWYUwEnx4OUwKlPpeZot+x98I+6Q1i2K1Paf6jMM6X5j4HGIrtMHgCMqWfM8IkPU+5Q";
$token=trim($row['token']);
$ebayusername=trim($row['ebay_name']);
$sql = "SELECT * FROM ebay_config WHERE user_id = $active_user ";
////$sql = "SELECT * FROM ebay_users WHERE user_id = 1 ";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$paypal_address=trim($row['paypal_address']);


$eBayAPIURL = "https://api.ebay.com/ws/api.dll";
//$eBayAPIURL = "https://api.sandbox.ebay.com/ws/api.dll";
$COMPATIBILITYLEVEL = '837';
$SiteId = 0;
 
 listitems();


function listitems() {
 global $APPNAME,$ebayusername,$SiteId;
  $active_user = 8;
  
    $f_endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // Finding
  $responseEncoding = 'XML';   // Format of the response
  $s_version = '667';   // Shopping API version number
  $f_version = '1.4.0';   // Finding API version number
  $appID   = $APPNAME; //replace this with your AppID

  $debug   = true;
    $sitearray = array(
                                  0=>'EBAY-US',
                                  2=>'EBAY-ENCA',
                                  3=>'EBAY-GB',
                                   15=>'EBAY-AU',
                                  77=>'EBAY-DE',
								 
								  );
				
                   				 
			    $globalID  =$sitearray[$SiteId];
  $sellerID =$ebayusername;  // cleanse input 	   
	 
	   $apicall = "$f_endpoint?OPERATION-NAME=findItemsAdvanced"
       . "&version=$f_version"
       . "&GLOBAL-ID=$globalID"
       . "&SECURITY-APPNAME=$appID"   // replace this with your AppID
       . "&RESPONSE-DATA-FORMAT=$responseEncoding"
       . "&itemFilter(0).name=Seller"
       . "&itemFilter(0).value=$sellerID"
       . "&paginationInput.entriesPerPage=100"
       . "&affliate.networkId=9"        // fill in your information in next 3 lines
       . "&affliate.trackingId=123456789"
       . "&affliate.customId=456";
	   
		 $resp = simplexml_load_file($apicall);
		
		  if ($resp->ack == "Success") {
		
		$nbpages=ceil($resp->paginationOutput->totalEntries/100);
		
		 }
		 
		 else {
		 $nbpages=1;
		 }
		
		 //$sql="DELETE FROM user_products where ItemID>0 and UserID = $active_user";
		// mysql_query($sql);  
	
   //echo $nbpages;die;
  
    /*touch($cron_file); 
    chmod($cron_file, 0777); 
	*/
  for($page=1;$page<=$nbpages;$page++) {
  $my_sellings_activelist = get_my_ebay_selling_ActiveList($page);
  
    $xml = simplexml_load_string($my_sellings_activelist);

    $active_data = $xml->ActiveList->ItemArray->Item;
    
	$count_array = count($active_data);

    $minute=0;
    $hour=0;	
    foreach ($active_data as $item) {
	    $item_id = $item->ItemID;
		
		$item_url = $item->ListingDetails->ViewItemURL;
        $ebay_price = $item->BuyItNowPrice;
        $quantity = $item->QuantityAvailable;
        $title = $item->Title;
        $sku = $item->SKU;
		$sku=str_replace(' ','',$sku);
        $image_url = $item->PictureDetails->GalleryURL;
		$pattern = '/^AMZ/';
       $posamaz=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
       $pattern = '/^WM/';
		$poswm=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
        $pattern = '/^OS/';
		$posos=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
		$pattern = '/^AL/';
		$posal=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
		$pattern = '/^HN/';
        $poshe=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
        $pattern = '/^WF/';
        $poswa=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);

       if($posamaz>0) {
	   $asin=str_replace('AMZ','',$sku);
	   }
	   elseif($poswm>0) {
	   $asin=str_replace('WM','',$sku);
        }  
	   elseif($posos>0){
	   $asin=str_replace('OS','',$sku);
	   }
	   elseif($posal>0){
	   $asin=str_replace('AL','',$sku);
	   }
	   elseif($poshe>0){
       $asin=str_replace('HN','',$sku);
        }
       elseif($poswa>0){
       $asin=str_replace('WF','',$sku);
        }

	   
	else {
	     $asin=$sku;
	    }	  
		
		if(!empty($asin)){
		
	  $sql = "SELECT * FROM ebay_asin WHERE asins='$asin'"; 
        $rs = mysql_query($sql);
		if (!mysql_num_rows($rs)) {
        $insert="INSERT INTO ebay_asin SET UserID=$active_user,asins='$asin',item_id=$item_id,ebay_title='$title',ebay_price= $ebay_price,quantity=$quantity,max_quantity=$quantity,in_ebay=1,in_amazon=1";
        
		 mysql_query($insert); 
		}
         $sql = "SELECT * FROM user_products WHERE SKU='$sku'"; 
        $rs = mysql_query($sql);
		if (!mysql_num_rows($rs)) {
		
		 $insert="INSERT INTO user_products SET UserID=$active_user,ItemID=$item_id,Qty=$quantity,Price=$ebay_price,Title='$title',SKU='$sku',Image='$image_url',ItemUrl='$item_url',MaxQty=$quantity";
         
		mysql_query($insert);
		
        $hour1=$hour;
		if($hour1>23){
		$hour1=$hour1-24;
		}
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
		
		$command=$minute.' '.$hour1.','.$hour2.','.$hour3.','.$hour4.','.$hour5.','.$hour6.' * * * wget http://dropshippingpower.com/ezonsync/cron_price_qty.php?itemid='.$item_id;	
		append_cronjob($command);
    $minute+=10;
    if($minute>60){
    $minute=0;
   }
 	$hour+=1;
	if($hour>23){
	$hour=0;
	}
		
	  }
	 } 
	}
	  
  }
   
}
//0 11,15,19,23,3,7 * * * wget http://ezon.org/cl/ezonsync/cron_price_qty.php?itemid=331403136186
function cronjob_exists($command){
   
    $cronjob_exists=false;
    $file="cronlist.txt";
    exec('crontab -l > cronlist.txt', $crontab);
  
    if(isset($crontab)&&is_array($crontab)){

        $crontab = array_flip($crontab);
         
        if(isset($crontab[$command])){

            $cronjob_exists=true;

        }

    }
    return $cronjob_exists;
}

function append_cronjob($command){

    if(is_string($command)&&!empty($command)&&cronjob_exists($command)===FALSE){
     
        //add job to crontab
		 $cronfile="cronlist.txt";
		file_put_contents($cronfile,$command."\n",FILE_APPEND);
       exec('crontab '.$cronfile);


    }

    return true;
}

?>