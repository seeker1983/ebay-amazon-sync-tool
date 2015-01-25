<?php 
set_time_limit(0);
include('inc.db.php');

include('ebayFunctions.php');
include "simple_html_dom.php";

$itemid=$_GET['itemid'];

delete_item_prime($itemid);   

function delete_item($item_id){

$remove_request=end_item($item_id);
 $xml= simplexml_load_string($remove_request);
 	
return true;
}		  


function delete_item_prime($itemid) {

$result=array();

$active_user = 8;

$sql = "SELECT * FROM user_products where UserID=$active_user and ItemID=$itemid" ;

 $res = mysql_query($sql) or die('Something Wrong...!');
 
 while ($row = mysql_fetch_array($res)) {
 
 $sku=trim($row['SKU']);

 $sku=str_replace(' ','',$sku);
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


 if($posamaz>0){
 
$asin=str_replace('AMZ','',$sku);     
$result=scrap_amazon($asin);

}
elseif($poswm>0){

$asin=str_replace('WM','',$sku);
//$tasin=explode('-',$asin);
//$result=scrap_walmart($tasin[0]);
}
elseif($posos>0){
$asin=str_replace('OS','',$sku);
$result=scrap_overstock($asin);
} 
elseif($posal>0){
$asin=str_replace('AL','',$sku);
$result=scrap_aliexpress($asin);
}
elseif($poshe>0){
$asin=str_replace('HN','',$sku);
$result=scrap_hayneedle($asin);
}
elseif($poswa>0){
$asin=str_replace('WF','',$sku);
$result=scrap_wayfair($asin);
}

else{
$asin=$sku;

$result=scrap_amazon($asin);

} 

$item_id=$row['ItemID'];
//echo $item_id;die; 

revise_ebay($result['offerprice'],$result['prime'],$result['quantity'],$result['scrapok'],$asin,$item_id);
	 if($result['prime']=='No') {
	  $sql = "DELETE FROM ebay_asin where item_id=".$item_id;
	  $sql1 = "DELETE FROM user_products where ItemID=".$item_id;
	  mysql_query($sql);
      mysql_query($sql1);
	  $date=date("d/m/Y h:i:s");
 $message="".$date."  item for id=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a> is ended because <a '_blank' href=http://www.amazon.com/dp/".$asin.">".$asin."</a> doesnt have prime sellers <br>";
 file_put_contents("log.php",$message,FILE_APPEND);
 
  $headers= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail('sam@baycodes.com', 'Deleting Item', $message,$headers);
//mail('c_hix@ca.rr.com', 'Deleting Item', $message,$headers);
mail('chebl.mahmoud@gmail.com', 'Deleting Item', $message,$headers);

	 delete_item($item_id);
	 }
$check_request=getItem($item_id);
$xml= simplexml_load_string($check_request);	 
 $status= $xml->Item->SellingStatus->ListingStatus;

 if(!empty($status)&&$status!='Active'){
	$sql = "DELETE FROM ebay_asin where item_id=".$item_id;
	  $sql1 = "DELETE FROM user_products where ItemID=".$item_id;
	  mysql_query($sql);
      mysql_query($sql1);

}
	 }
}

function revise_ebay($amazon_price,$prime,$quantity,$status,$asin,$item_id) {

$active_user = 8;
  $sql = "SELECT * FROM ebay_asin where in_ebay=1 and UserID=".$active_user." and asins='".$asin."'";
$rs = mysql_query($sql) or die(mysql_error());
    $sql_ebay_config = "SELECT * FROM ebay_config WHERE user_id=$active_user";
    $rs_config = mysql_query($sql_ebay_config) or die('Something Wrong...!');
    $row_config = mysql_fetch_assoc($rs_config);
    //$max_quantity = $row_config['max_quantity'];

while ($row = mysql_fetch_array($rs)) {
     	
		$ratio=$row['profit_ratio'];
		 //$amz_quantity=$row['amazon_quantity']
		$max_quantity=$row['max_quantity'];
		
       $amazon_price=number_format((float)$amazon_price, 2, '.', '');
	  
	if($status==1){
	   $amzquantity=$quantity;
	   }
	   else {
	   $amzquantity=1;
	   }
	   $ebay_price=$row['ebay_price'];
	  if($ratio==0.00){ 
	  $ratio=($ebay_price*0.87)-$amazon_price;
	  $ratio=number_format((float)$ratio, 2, '.', '');
		}
		if($ratio!=0&&$max_quantity!=0){
		if ($prime=='Yes'&&$ratio< 2)
        $ebay_price=$amazon_price* (1+ $ratio)/(0.87);
        else if ($prime=='Yes' && $ratio>=2)
        $ebay_price = ($amazon_price+$ratio)/0.87;
	
		 
        if ($quantity > $max_quantity) {
            $quantity = $max_quantity;
        }
		elseif ($quantity == 0&&$status==1) {
              $sql = "DELETE FROM ebay_asin where item_id=".$item_id;
	  $sql1 = "DELETE FROM user_products where ItemID=".$item_id;
	  mysql_query($sql);
      mysql_query($sql1);
	  $date=date("d/m/Y h:i:s");
 $message="".$date."  item for id=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a> is ended because <a '_blank' href=http://www.amazon.com/dp/".$asin.">".$asin."</a> is out of stock <br>";
 file_put_contents("log.php",$message,FILE_APPEND);
 
  $headers= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail('sam@baycodes.com', 'Deleting Item', $message,$headers);
//mail('c_hix@ca.rr.com', 'Deleting Item', $message,$headers);
mail('chebl.mahmoud@gmail.com', 'Deleting Item', $message,$headers);
	delete_item($item_id);       
            }
		elseif ($quantity < $max_quantity) {
		      
            $quantity1 = $quantity;
			$quantity = $quantity1;
        } 
		
               
		 $ebay_price = number_format((float)$ebay_price, 2, '.', '');
	$profit_ratio=($ebay_price*0.87)-$amazon_price;
	
	if(!empty($amazon_price)&&$amazon_price>0&&$ebay_price>0){

$return_result=ebay_revise_item($active_user,$item_id,$ebay_price,$amazon_price,$quantity,$max_quantity,$asin);

$xml =simplexml_load_string($return_result);
	
	$sql_maxqty_update = "UPDATE ebay_asin eb,
									user_products ups 
							SET eb.max_quantity=$max_quantity,
								eb.quantity = $quantity,
								ups.MaxQty = $max_quantity,
								
								ups.AmazonPrice=$amazon_price,
								
								ups.AmazonQty=$amzquantity,
								ups.ProfitRatio=$profit_ratio,
								eb.profit_ratio=$profit_ratio
							WHERE
								eb.UserID = ups.UserID AND
								eb.item_id = ups.ItemID AND
								eb.item_id =$item_id AND
								eb.asins = '$asin' AND
								eb.UserID = $active_user
								
							";
							
							//echo $sql_maxqty_update;die;
					
		mysql_query($sql_maxqty_update) or die(mysql_error());
 
	
	if(($xml->Ack)=="Success" or ($xml->Ack)=="Warning"){
	
		$sql_maxqty_update = "UPDATE ebay_asin eb,
									user_products ups 
							SET eb.max_quantity=$max_quantity,
								eb.quantity = $quantity,
								ups.MaxQty = $max_quantity,
								ups.Qty = $quantity,
								ups.AmazonPrice=$amazon_price,
								ups.Price=$ebay_price,
								ups.AmazonQty=$amzquantity,
								ups.ProfitRatio=$profit_ratio,
								eb.profit_ratio=$profit_ratio
							WHERE
								eb.UserID = ups.UserID AND
								eb.item_id = ups.ItemID AND
								eb.item_id ='$item_id' AND
								eb.asins = '$asin' AND
								eb.UserID = $active_user
								
							";
							
							//echo $sql_maxqty_update;die;
					
		mysql_query($sql_maxqty_update) or die(mysql_error());
 
}
else {



 foreach ($xml->Errors as $error) {
                    $errCode = $error->ErrorCode;
                    $errLongMsg = htmlentities($error->LongMessage);
                    $errSeverity = $error->SeverityCode;
                    echo $errSeverity . ": [" . $errCode . "] " . $errLongMsg . "<br/>";
                }

}
}

}

}

				  }

function ebay_revise_item($user_id,$item_id, $revise_price,$amazon_price,$revise_qty,$max_qty,$asin) {

 	
    $sql_config = "SELECT 
    				eu.dev_name,
    				eu.app_name,
    				eu.cert_name,
    				eu.token,
    				ec.paypal_address,
    				ec.return_accept_option,
    				ec.refund_option,
    				ec.return_days 
    		FROM 	
    				ebay_users eu,
    				ebay_config ec
    		WHERE 
    				eu.user_id = ec.user_id AND 
    				eu.user_id = $user_id				
    ";

    $rs_config = mysql_query($sql_config) or die(mysql_error());
    
    if(mysql_num_rows($rs_config)!=1)
    	die("Database Error - Developer Side");

    $row_config = mysql_fetch_assoc($rs_config);

    $DEVNAME = trim($row_config['dev_name']);
    $APPNAME = trim($row_config['app_name']);
    $CERTNAME = trim($row_config['cert_name']);
  // $token = "AgAAAA**AQAAAA**aAAAAA**rk05VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AElYCmCJiFqQqdj6x9nY+seQ**y4ICAA**AAMAAA**I+dnHHqjbr1rjZlo8wEbyge8d1Rj73Vd3La+W4FzvDVDYl4cLgvS2pw9bttZX9kkkFTpeRtt+UDuYxU9N6O3FpzN7OklgZaQKMud9sjKb6O3WQYICnjw2KdMlEaeBKrTTmyM31hEcOiTvJB5u/0U0zFLWXvk1Qk6YAPhqTGejzYiwQtqYGtmd0rGtoS+b+46XxRov3saL5cRdOfSSTcHlWY9mtIqWerFpM4ZP0Qbj6xM4azxUa0m0BIZKf70+EbSBoFOaVo4X2k5ysHvSII+1WTbVBNvqgp9PNvgfyoMs+VZZ77Q+Qlfl+kk1pmGJqgqUY854qv0gABzSV5OEbgHIS2v0K2OhcTbo3EgvDX1SbPr/MKGFmC2aeqrVbn/9x5XZKnwPdtqoDwYJKe3sU/tzonToUKfyqldIF1GbJlvh5lgd2dPv+WS29g0wNNKGtPDyrLmQ0cktF0Ymnx5zvT7xffOtE9HweCCCILJeqFgyS7XeBgSY4d4fXcxXtaMaK9c2HFjwnF4v2WKoJ9KYYT9svSsf3ksk4fGzEv6XVPonZQxvyZESJHxQ7SbR/KE0rAB1WZY8N8x0hMu999SEJEse4lYRP7RjVxx82AVZp6MUVExfHSMQcQQL8O5O6wgjkErAHpfd7CzOmaSeyy4HNzWq3liP0WGQCfVXFDGl2YunJL9stIKaA/SrMeOvx5CSAWYUwEnx4OUwKlPpeZot+x98I+6Q1i2K1Paf6jMM6X5j4HGIrtMHgCMqWfM8IkPU+5Q";
  $token= trim($row_config['token']);
 $SiteId=0; 
 
 $active_user = 8;
 

$eBayAPIURL = "https://api.ebay.com/ws/api.dll";

$COMPATIBILITYLEVEL='837';
    $paypal_email = trim($row_config['paypal_address']);
    $return_accept_option = trim($row_config['return_accept_option']);
    $refund_option = trim($row_config['refund_option']);
    $return_within_days = trim($row_config['return_days']);
    
   $sql="SELECT * FROM user_products WHERE UserID=$active_user and ItemID=$item_id";
  
	$rs=mysql_query($sql);
	 $row = mysql_fetch_assoc($rs);
	                     $sku=$row['SKU'];
				
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
		 $url="http://www.amazon.com/dp/".$asin;
		 }
		 elseif($poswm>0){
		 $linkitem=scrap_walmartlink($asin);
		 $url="http://www.walmart.com/". $linkitem ;
		 }
		 elseif($posos>0){
		 $url="http://www.overstock.com/search/".$asin;
		 }
		 elseif($posal>0){
		 $url="http://www.aliexpress.com/wholesale?SearchText=" . $asin;
		 }
		 elseif($poshe>0){
		 $url="http://search.hayneedle.com/search/index.cfm?Ntt=". $asin ;
		 }
	  elseif($poswa>0){
		 $url1="http://www.wayfair.com/keyword.php?keyword=".$asin;
	                   $data=getPage($url1);
	                   $html = str_get_html($data);
                       foreach($html->find('div[id=sbprodgrid] a') as $item) {
                        $url1=$item->href;
	                    break;
	                   }
		$url=$url1;			   
		 }
		else {
		 $url="http://www.amazon.com/dp/".$asin;
		} 
		
	 if($row['Qty']!=$revise_qty&&$revise_qty>0&&$row['Price']==$revise_price){
      $date=date("d/m/Y h:i:s");
	  $message="".$date." eBay Quantity changed from ".$row['Qty']." to ".$revise_qty." for itemid=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a> because max_quantiy = ".$max_qty."<br>";
	  $headers= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	file_put_contents("log.php",$message,FILE_APPEND);
	//mail('c_hix@ca.rr.com', 'Updating Quantity', $message,$headers);
	mail('sam@baycodes.com', 'Updating Ebay Quantity', $message,$headers);
	mail('chebl.mahmoud@gmail.com', 'Updating Ebay Quantity', $message,$headers);
	
    $post_data = '<?xml version="1.0" encoding="utf-8"?>
  <ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <ErrorLanguage>en_US</ErrorLanguage>
  <Item>    
    <ItemID>' . $item_id . '</ItemID>
    <Quantity>'.$revise_qty.'</Quantity>
   
  </Item>
  <RequesterCredentials>  
  <eBayAuthToken>' . $token . '</eBayAuthToken>
  </RequesterCredentials>
 
  </ReviseItemRequest>';
}
elseif($row['Qty']==$revise_qty&&$revise_qty>0&&$row['Price']!=$revise_price){
 //echo  $ebay_price 
 $amzprice=$row['AmazonPrice'];
	$date=date("d/m/Y h:i:s");
		
		
		if($amzprice!=$amazon_price){
		
	$message="".$date." eBay Price changed from $".$row['Price']." to $".$revise_price." for itemid=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a>  because Vendor price of <a target='_blank' href=".$url.">".$asin."</a> change from $".$amzprice." to $".$amazon_price."<br>";
	  }
	  else{
	$message="".$date." eBay Price changed from $".$row['Price']." to $".$revise_price." for itemid=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a> for vendor product <a target='_blank' href=".$url.">".$asin."</a> priced $".$amazon_price."<br>";
	  }
	  $headers= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	file_put_contents("log.php",$message,FILE_APPEND);
	mail('chebl.mahmoud@gmail.com', 'Updating Ebay Price', $message,$headers);	
	mail('sam@baycodes.com', 'Updating Ebay Price', $message,$headers);	
	
$post_data = '<?xml version="1.0" encoding="utf-8"?>
  <ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <ErrorLanguage>en_US</ErrorLanguage>
  <Item>    
    <ItemID>' . $item_id . '</ItemID>
    <StartPrice>' . $revise_price . '</StartPrice>
      
  </Item>
  <RequesterCredentials>  
  <eBayAuthToken>' . $token . '</eBayAuthToken>
  </RequesterCredentials>
 
  </ReviseItemRequest>';
}
elseif($row['Qty']!=$revise_qty&&$revise_qty>0&&$row['Price']!=$revise_price){
    $date=date("d/m/Y h:i:s");
	 $amzprice=$row['AmazonPrice'];
    	if($amzprice!=$amazon_price){
	$message1="".$date." eBay Price changed from $".$row['Price']." to $".$revise_price." for itemid=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a>  because Vendor price of <a target='_blank' href=".$url.">".$asin."</a> change from $".$amzprice." to $".$amazon_price."<br>";
	  }
	  else{
	$message1="".$date." eBay Price changed from $".$row['Price']." to $".$revise_price." for itemid=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a> for vendor product <a target='_blank' href=".$url.">".$asin."</a> priced $".$amazon_price."<br>";
	  }
	$message2="".$date." eBay Quantity changed from ".$row['Qty']." to ".$revise_qty." for itemid=<a target='_blank' href=http://www.ebay.com/itm/".$item_id.">".$item_id."</a> because max_quantiy = ".$$max_qty."<br>";	
	file_put_contents("log.php",$message1,FILE_APPEND);
	file_put_contents("log.php",$message2,FILE_APPEND);
	$headers= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	mail('chebl.mahmoud@gmail.com', 'Updating Ebay Price', $message1,$headers);	
	mail('chebl.mahmoud@gmail.com', 'Updating Ebay Quantity', $message2,$headers);	
	//mail('c_hix@ca.rr.com', 'Updating Ebay Price', $message1,$headers);	
	//mail('c_hix@ca.rr.com', 'Updating Ebay Quantity', $message2,$headers);	
	mail('sam@baycodes.com', 'Updating Ebay Price', $message1,$headers);	
	mail('sam@baycodes.com', 'Updating Ebay Quantity', $message2,$headers);	
	

$post_data = '<?xml version="1.0" encoding="utf-8"?>
  <ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <ErrorLanguage>en_US</ErrorLanguage>
  <Item>    
    <ItemID>' . $item_id . '</ItemID>
    <StartPrice>' . $revise_price . '</StartPrice>
	<Quantity>'.$revise_qty.'</Quantity>
	
  </Item>
  <RequesterCredentials>  
  <eBayAuthToken>' . $token . '</eBayAuthToken>
  </RequesterCredentials>
 
  </ReviseItemRequest>';
}  
  
  
  
  	$call_name = "ReviseItem";

	$ebayapiheader = array ( 
							"X-EBAY-API-COMPATIBILITY-LEVEL: $COMPATIBILITYLEVEL", 
							"X-EBAY-API-DEV-NAME: $DEVNAME", 
							"X-EBAY-API-APP-NAME: $APPNAME", 
							"X-EBAY-API-CERT-NAME: $CERTNAME", 
							"X-EBAY-API-SITEID: $SiteId",
							"X-EBAY-API-CALL-NAME: ".$call_name); 
	
      
   	$ch = curl_init(); 
	$res= curl_setopt ($ch, CURLOPT_URL,$eBayAPIURL); 


	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 

	curl_setopt ($ch, CURLOPT_HEADER, 0); // 0 = Don't give me the return header 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $ebayapiheader); // Set this for eBayAPI 
	curl_setopt($ch, CURLOPT_POST, 1); // POST Method 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); //My XML Request 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	$body = curl_exec ($ch); //Send the request 
	
	curl_close ($ch); // Close the connection
  
	return $body;
	
}

function scrap_price($asin){
$url="https://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new&condition=new";

$data=getPage($url);

$htmloffer = str_get_html($data);

$price=2;
if($htmloffer){

foreach($htmloffer->find('div[class=a-section a-spacing-double-large]') as $html1) {

  foreach($html1->find('div[class=a-row a-spacing-mini olpOffer]') as $html2) {
      foreach($html2->find('div[class=a-column a-span2]') as $html3) {
	    $price=0;
		foreach($html3->find('span[class=a-size-large a-color-price olpOfferPrice a-text-bold]') as $html4) {
	     
		 $price1=$html4->plaintext;
         	
		}
        foreach($html3->find('span[class=supersaver]') as $html6) {
		preg_match_all('/\d+(\.\d+)/',$price1, $matches);
       $price1=$matches[0][0];
		return $price1;
		}
		
		 }
	   }
  	  }
	 unset($htmloffer);
	 unset($data);
	} 
	
	 return $price;
	 }


function scrap_amazon($asin) {
//$asin="B002KW3OQS";
$url="http://www.amazon.com/dp/".$asin;
//$url="http://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new&condition=new";
$data=getPage($url);

  $html = str_get_html($data);
  $ok=0;
	   foreach($html->find('h1[id=title]') as $title) {	
       $ok=1;
	   
	 }
	 
	 if($ok==1){
	 $result['scrapok']=1;
	 }
	 else{
	 $result['scrapok']=0;
	 }
	 
	 $result['offerprice']=scrap_price($asin);
	 $result['offerprice']=str_replace(',','',$result['offerprice']);
	 $counter=1;
      while($result['offerprice']==2&&$counter<=5){
      sleep(2);
	 $result['offerprice']=scrap_price($asin); 
	  $counter++;
	  }
	  
	 
	 if($result['offerprice']==0){
          $result['prime']='No';	 
	  }
	  else {
	  $result['prime']='Yes';	 

	  }
  if($result['offerprice']==2){
  $result['offerprice']=0;
  }
$qte=0;
 foreach($html->find('select[id=quantity]') as $quantity) {	
   foreach($quantity->find('option') as $opt) {	
$qte++;
 }
 }
 if($qte==0){
 foreach($html->find('div[id=availability] span') as $quantity) {	
 preg_match_all('/\d+(\.\d+)/',$quantity->plaintext, $matches);
 $qte=$matches[0][0];
 if(trim($quantity->plaintext)=='In Stock.')
 $qte=1;
 else 
 if(trim($quantity->plaintext)=='Currently unavailable.')
 $qte=0;

 break;
 }
 }
 $result['quantity']=$qte;
unset($html);
 unset($data);
 
return $result;
}

function scrap_walmart($itemid) {

$result=array();
$url="http://www.walmart.com/search/?query=".$itemid;


$data=PostForm($url);
 

$result['itemid']=$itemid;
  $htmlb = str_get_html($data);
  foreach($htmlb->find('h4[class=tile-heading] a') as $link){ 
    
   $url='http://www.walmart.com/'.$link->href;
   $data=PostForm($url);
  
   $html = str_get_html($data);
  break;
  }
 $result['scrapok']=1;
 foreach($html->find('div[class=js-price-display price price-display price-display-oos-color]') as $price) {
$result['offerprice']=$price->plaintext;
break;
} 
$result['offerprice']=str_replace('$','',$result['offerprice']);    
foreach($html->find('div[class=js-price-display price price-display]') as $price) {
$result['offerprice']=$price->plaintext;
break;
}
$result['offerprice']=str_replace('$','',$result['offerprice']);
$result['offerprice']=str_replace(" ","",$result['offerprice']);
$result['offerprice']=str_replace(',','',$result['offerprice']);
$result['quantity']=0;
$qte=0;
foreach($html->find('div[class=col6 form-inline form-inline-mini]') as $quantity) {

  foreach($quantity->find('select') as $select) {
	 foreach($select->find('option') as $opt) {
       $qte++;
 }
 }
 }
$result['quantity']=$qte;

$result['prime']="Yes";


return $result;
}

function scrap_overstock($itemid) {
$result=array();

$url="http://www.overstock.com/search/".$itemid;
//echo $url;
$data=postForm($url);
  $html = str_get_html($data);
  
$result['itemid']=$itemid;


 $result['scrapok']=1;
 
foreach($html->find('span[class=price_sale main-price-red] span[class=Ovalue main-price-red]') as $price) {
$result['offerprice']=$price->plaintext;
break;
}
$result['offerprice']=str_replace('$','',$result['offerprice']);  
$result['offerprice']=str_replace(',','',$result['offerprice']);
$qte=0;
foreach($html->find('div[id=addCartMain_quantity] select') as $select) {
   foreach($select->find('option') as $opt) {
       $qte++;
     }
   }
$result['quantity']=$qte;

$result['prime']='Yes';

return $result;

}

function scrap_aliexpress($itemid) {
$result=array();

$url="http://www.aliexpress.com/wholesale?SearchText=".$itemid;
//echo $url;
  $data=postForm($url);
  //echo $data;die;
  $html = str_get_html($data);
  $result['itemid']=$itemid;

preg_match("|<span.*?itemprop=\"price\".*?>(.*?)</span>|s", $html, $match_item_price);
						
						if(isset($match_item_price[1]))
						{
							$item_price =$match_item_price[1];
						}
$result['offerprice']=$item_price;

 $offerprice=explode('-',trim($result['offerprice']));
 $result['offerprice']='$'.$offerprice[0];
$result['offerprice']=str_replace('$','',$result['offerprice']);  
$result['offerprice']=str_replace(',','',$result['offerprice']);
 $result['scrapok']=1;
 $qte=0;
foreach($html->find('dl[id=product-info-quantity] input[id=product-info-txt-quantity]') as $quantity) {

$qte=intval($quantity->value);
break;
}
if($qte>0){$result['quantity']=$qte;}
else {$result['quantity']=1;}
 
$result['prime']='Yes';
return $result;

}
function scrap_hayneedle($url) {
    
	if (!filter_var($url, FILTER_VALIDATE_URL)) {
	$url="http://search.hayneedle.com/search/index.cfm?Ntt=".$url;
	}

	$data=getPage($url);
	$html = str_get_html($data);
    $result['scrapok']=0;
	if($html){
   foreach($html->find('span[class=standard-style noWrap]') as $item) {
     $itemnumber=$item->plaintext;
	 $result['scrapok']=1;  
   break;
}
 $result['scrapok']=1;
  $result['itemid']=$itemnumber;
 
 preg_match("|<span.*?itemprop=\"price\".*?>(.*?)</span>|s", $html, $match_item_price);
  	
	if(isset($match_item_price[1]))
     {
	   $item_price =trim($match_item_price[1]);
	}
$result['offerprice']=$item_price;
 $result['offerprice']=str_replace('$','',$result['offerprice']);
$result['offerprice']=str_replace(',','',$result['offerprice']);

 /*
$qte=0;
foreach($html->find('div[id=addCartMain_quantity] select') as $select) {
   foreach($select->find('option') as $opt) {
       $qte++;
     }
   }
   */
$result['quantity']=100;
$result['prime']='Yes';
return $result;
}else return -1;
}	

function scrap_wayfair($url) {

if (!filter_var($url, FILTER_VALIDATE_URL)) {

	$url="http://www.wayfair.com/keyword.php?keyword=".$url;
	$data=getPage($url);
	$html = str_get_html($data);
	
   foreach($html->find('div[id=sbprodgrid] a') as $item) {
    $url=$item->href;
	break;
	}

 }
 
$data=getPage($url);
	$html = str_get_html($data);
$result['scrapok']=0;
if($html){
   foreach($html->find('span[class=breadcrumb note ltbodytext] span[class=emphasis]') as $item) {
     $itemnumber=$item->plaintext;
    $result['scrapok']=1; 	 
   break;
}
  $result['itemid']=$itemnumber;

  preg_match("|<span.*?data-id=\"dynamic-sku-price\".*?>(.*?)</span>|s", $html, $match_item_price);
  	  
	   	   foreach($html->find('span[class=product_price emphasis]') as $price){
           $result['offerprice']= $price->plaintext;
		   }
	   $result['offerprice']=str_replace('$','',$result['offerprice']);  
	   $result['offerprice']=str_replace(',','',$result['offerprice']);
  
$qte=0;
foreach($html->find('div[class=qty centertext margin_md_bottom] select') as $select) {
   foreach($select->find('option') as $opt) {
       $qte++;
     }
   }
   
$result['quantity']=$qte;
$result['prime']='Yes';
 
return $result;
}
else {
return -1;
}
}




function postForm($url)
{
    $ch = curl_init();
    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Pragma: "; // browsers keep this blank.

    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_URL, $url);


    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}
function getPage($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 20 );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    $ua = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0";
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);

    $str = curl_exec($ch);
   
    curl_close($ch);
    sleep(2);

    return $str;

}
function scrap_walmartlink($itemid){
	
	$url="http://www.walmart.com/search/search-ng.do?search_query=".$itemid;
	
	$data=postForm($url);
	$link="";
	//echo $data;die;
	$html=str_get_html($data);
	if($html){
	foreach($html->find('a[class=js-product-title]') as $title)
	{
	
	$link=$title->href;
	
	break;
	}
	}
	return $link;
	}

?>