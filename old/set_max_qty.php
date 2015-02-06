<?php
session_start();
include('inc.db.php');
require_once('functions.php');
include('keys.php');
if(!isset($_POST['user_id']) or !isset($_POST['ebay_item_id']) or
	!isset($_POST['asin']) or !isset($_POST['max_qty']))
	die("Invalid Call to Script");

$user_id = $_POST['user_id'];
$ebay_item_id = $_POST['ebay_item_id'];
$asin = $_POST['asin'];
$sql="SELECT asins FROM ebay_asin
			   		WHERE asins LIKE '%$asin%'";

					$rs=mysql_query($sql);
$rowasin= mysql_fetch_assoc($rs);
$asin=$rowasin['asins'];

$max_qty = $_POST['max_qty'];

$active_user = $_SESSION['user_id'];

if($active_user!=$user_id)
	die("Request Can not be Processed !");

$sql_maxqty_select = "SELECT 	quantity,
								amazon_quantity,
								max_quantity,
								ebay_price,
								in_amazon
			   		FROM 	 	ebay_asin
			   		WHERE
			   				UserID = $user_id AND
			   				item_id = '$ebay_item_id' AND
			   				asins = '$asin'
			";

$rs_max_qty = mysql_query($sql_maxqty_select) or die(mysql_error());

$no_record = mysql_num_rows($rs_max_qty);

if($no_record==1){
	$row = mysql_fetch_assoc($rs_max_qty);

	$quantity_db = $row['quantity'];
	$max_qty_db = $row['max_quantity'];
	$ebay_price_db = $row['ebay_price'];
	$amazon_quantity_db = $row['amazon_quantity'];
	$in_amazon = $row['in_amazon'];
	//
	$regex_max_qty = '/^([1-9]|[1-9]{1}[0-9]{1}|[1-2]{1}[0-9]{1,2})$/';

	if(!preg_match($regex_max_qty,$max_qty))
		die("Failed:".$max_qty_db);

	if($max_qty_db == $max_qty)
		die("Same Max Qty requested !");

	$ebay_revise_price = $ebay_price_db;
  
	if($in_amazon==0){
		$ebay_revise_qty = $max_qty;
	}elseif($in_amazon==1){
		if($amazon_quantity_db >=  $max_qty)
			$ebay_revise_qty = $max_qty;
		elseif($amazon_quantity_db < $max_qty)
			$ebay_revise_qty = $amazon_quantity_db;
	}

	if(!isset($ebay_revise_price) or !isset($ebay_revise_qty))
		die("Revise Values Failed !");

	//$return_result = ebay_revise_item($user_id,$ebay_item_id,$ebay_revise_price,$ebay_revise_qty);
	
	//$xml =simplexml_load_string($return_result);
	
	//if(($xml->Ack)=="Success" or ($xml->Ack)=="Warning"){
	  
		$sql_maxqty_update = "UPDATE ebay_asin eb,
									user_products ups 
							SET eb.max_quantity=$max_qty,
								eb.quantity = $ebay_revise_qty,
								ups.MaxQty = $max_qty,
								ups.Qty = $ebay_revise_qty
							WHERE
								eb.UserID = ups.UserID AND
								eb.item_id = ups.ItemID AND
								eb.asins='$asin' AND
								eb.item_id ='$ebay_item_id' AND
								eb.asins = '$asin' AND
								eb.UserID = $user_id AND
								eb.max_quantity != $max_qty
							";
							
		mysql_query($sql_maxqty_update) or die(mysql_error());
		print("Updated:".$max_qty.":".$ebay_revise_qty);
	/*}elseif(($xml->Ack)=="Failure"){
			echo str_replace('','',($xml->Errors->ShortMessage));
			return;
	}else {
		echo "Please Report to Developer";
		return;
	}*/
	

}elseif($no_record>1){
	die("Duplicate Records - Please Report to Developer");

}

function ebay_revise_item($user_id,$item_id, $revise_price, $revise_qty) {

	
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
   $token = "AgAAAA**AQAAAA**aAAAAA**rk05VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AElYCmCJiFqQqdj6x9nY+seQ**y4ICAA**AAMAAA**I+dnHHqjbr1rjZlo8wEbyge8d1Rj73Vd3La+W4FzvDVDYl4cLgvS2pw9bttZX9kkkFTpeRtt+UDuYxU9N6O3FpzN7OklgZaQKMud9sjKb6O3WQYICnjw2KdMlEaeBKrTTmyM31hEcOiTvJB5u/0U0zFLWXvk1Qk6YAPhqTGejzYiwQtqYGtmd0rGtoS+b+46XxRov3saL5cRdOfSSTcHlWY9mtIqWerFpM4ZP0Qbj6xM4azxUa0m0BIZKf70+EbSBoFOaVo4X2k5ysHvSII+1WTbVBNvqgp9PNvgfyoMs+VZZ77Q+Qlfl+kk1pmGJqgqUY854qv0gABzSV5OEbgHIS2v0K2OhcTbo3EgvDX1SbPr/MKGFmC2aeqrVbn/9x5XZKnwPdtqoDwYJKe3sU/tzonToUKfyqldIF1GbJlvh5lgd2dPv+WS29g0wNNKGtPDyrLmQ0cktF0Ymnx5zvT7xffOtE9HweCCCILJeqFgyS7XeBgSY4d4fXcxXtaMaK9c2HFjwnF4v2WKoJ9KYYT9svSsf3ksk4fGzEv6XVPonZQxvyZESJHxQ7SbR/KE0rAB1WZY8N8x0hMu999SEJEse4lYRP7RjVxx82AVZp6MUVExfHSMQcQQL8O5O6wgjkErAHpfd7CzOmaSeyy4HNzWq3liP0WGQCfVXFDGl2YunJL9stIKaA/SrMeOvx5CSAWYUwEnx4OUwKlPpeZot+x98I+6Q1i2K1Paf6jMM6X5j4HGIrtMHgCMqWfM8IkPU+5Q";

   
 

$eBayAPIURL = "https://api.ebay.com/ws/api.dll";
$SiteId=0;
$COMPATIBILITYLEVEL='837';
    $paypal_email = trim($row_config['paypal_address']);
    $return_accept_option = trim($row_config['return_accept_option']);
    $refund_option = trim($row_config['refund_option']);
    $return_within_days = trim($row_config['return_days']);
      
    $post_data = '<?xml version="1.0" encoding="utf-8"?>
  <ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <ErrorLanguage>en_US</ErrorLanguage>
  <Item>    
    <ItemID>' . $item_id . '</ItemID>
    <StartPrice>' . $revise_price . '</StartPrice>
    <Quantity>'.$revise_qty.'</Quantity>
        <PayPalEmailAddress>'.$paypal_email.'</PayPalEmailAddress>
	<ReturnPolicy>
            <ReturnsAcceptedOption>'.$return_accept_option.'</ReturnsAcceptedOption>
            <RefundOption>'.$refund_option.'</RefundOption>
            <ReturnsWithinOption>'.$return_within_days.'</ReturnsWithinOption>
            <ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
	</ReturnPolicy>
  </Item>
  <RequesterCredentials>  
  <eBayAuthToken>' . $token . '</eBayAuthToken>
  </RequesterCredentials>
 
  </ReviseItemRequest>';

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

?>