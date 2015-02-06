<?php
include('keys.php');
function get_my_ebay_selling_ActiveList ($page_no) {
	
	global $token;
	
   $post_data = '<?xml version="1.0" encoding="utf-8"?>
					<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					  <RequesterCredentials>
						<eBayAuthToken>'.$token.'</eBayAuthToken>
					  </RequesterCredentials>
					  <Version>505</Version>
					  <ActiveList>
						<Sort>TimeLeft</Sort>
						<Pagination>
						  <EntriesPerPage>100</EntriesPerPage>
						  <PageNumber>'.$page_no.'</PageNumber>
						</Pagination>
					  </ActiveList>
					</GetMyeBaySellingRequest>';
	
	$body = callapi($post_data,"GetMyeBaySelling");



	return $body; 
}

function revise_price($eid,$price) {
	
	global $token;
//echo $eid.'<br>';
//echo $price.'<br>';
	$post_data= '<?xml version="1.0" encoding="utf-8"?>
	<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
	<ErrorLanguage>en_US</ErrorLanguage>
	<Item>
		<ItemID>'.$eid.'</ItemID>
		<StartPrice>'.$price.'</StartPrice>
	</Item>
	<RequesterCredentials>
	<eBayAuthToken>'.$token.'</eBayAuthToken>
	</RequesterCredentials>
	<WarningLevel>High</WarningLevel>
	</ReviseItemRequest>'; 

	$body = callapi($post_data,'ReviseItem'); 
	
	//return $body;
	
}


function callapi($post_data,$call_name){
	global $COMPATIBILITYLEVEL,$DEVNAME,$APPNAME, $CERTNAME,$SiteId,$eBayAPIURL;

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
	//echo curl_error($ch); 
	curl_close ($ch); // Close the connection
	//echo $body;
	//header("Content-type: application/xml");	
	return $body;
	//echo $body;
}




?>