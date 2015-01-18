<?php
session_start();
include('inc.db.php');
require_once 'functions.php';

if(!isset($_POST['user_id']) or !isset($_POST['ebay_item_id']) or
	!isset($_POST['asin']) or !isset($_POST['profit_ratio']))
	die("Invalid Call to Script");


$user_id = $_POST['user_id'];
$ebay_item_id = $_POST['ebay_item_id'];
$asin = $_POST['asin'];
$sql="SELECT asins FROM ebay_asin
			   		WHERE asins LIKE '%$asin%'";

					$rs=mysql_query($sql);
$rowasin= mysql_fetch_assoc($rs);
$asin=$rowasin['asins'];
$profit_ratio = $_POST['profit_ratio'];

$active_user = $_SESSION['user_id'];

if($active_user!=$user_id)
	die("Request Can not be Processed !");

$sql_profit_select = "SELECT 	amazon_price,
								profit_ratio,
								quantity,
								amazon_quantity,
								max_quantity,
								in_amazon
			   		FROM 	 	ebay_asin
			   		WHERE
			   				UserID = $user_id AND
			   				item_id = '$ebay_item_id' AND
			   				asins = '$asin'
			";
	

$rs_profit = mysql_query($sql_profit_select) or die(mysql_error());

$no_record = mysql_num_rows($rs_profit);

if($no_record==1){

	$row = mysql_fetch_assoc($rs_profit);
	
	$amazon_price = $row['amazon_price'];
	$profit_ratio_db = $row['profit_ratio'];
	$in_amazon = $row['in_amazon'];
	$amazon_quantity_db = $row['quantity'];
	$amazon_quantity = $row['amazon_quantity'];
	$max_qty = $row['max_quantity'];
	
	//$ebay_price_db = $row['ebay_price'];
	

	//$regex_profit_ratio = '/^([0-9]|[0-9]\.[0-9]{1,2})$/';

	if($profit_ratio<0.01||$profit_ratio>1000)
		die("Failed:".$profit_ratio_db);
 $profit_ratio=number_format((float)$profit_ratio, 2, '.', '');
	if($profit_ratio_db == $profit_ratio)
		die("Same ratio requested !");

	//$ebay_revise_price = $ebay_price_db;
  
	if($in_amazon==0){
		$ebay_revise_qty = $max_qty;
	}elseif($in_amazon==1){
		if($amazon_quantity_db >=  $max_qty)
			$ebay_revise_qty = $max_qty;
		elseif($amazon_quantity_db < $max_qty)
			$ebay_revise_qty = $amazon_quantity_db;
	}


	

	//$return_result = ebay_revise_item($user_id,$ebay_item_id, $ebay_revise_price, $ebay_revise_qty);
	//$xml = new SimpleXMLElement($return_result);
	
	//if(($xml->Ack)=="Success" or ($xml->Ack)=="Warning"){

	$sql_profit_update = "UPDATE ebay_asin eb, 
								 user_products ups
						  SET eb.profit_ratio = $profit_ratio,
						  	  ups.ProfitRatio = $profit_ratio
						  	 
						  WHERE
						  	eb.UserID = ups.UserID AND
						  	eb.item_id = ups.ItemID AND
						  	eb.asins='$asin' AND
						  	eb.UserID = $user_id AND
			   				eb.item_id = '$ebay_item_id' AND
			   				eb.asins = '$asin' AND
			   				eb.profit_ratio != $profit_ratio
	";

	mysql_query($sql_profit_update) or die(mysql_error());
	print("Updated:".$profit_ratio.":".$ebay_revise_price);
	
	
	

}elseif($no_record>1){
	die("Duplicate Records - Please Report to Developer");

}

function ebay_revise_item($user_id,$item_id, $revise_price, $revise_qty) {

	include('site_conf.php');

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
    $token = encrypt_decrypt('decrypt',$row_config['token']);

    

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
  <WarningLevel>High</WarningLevel>
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