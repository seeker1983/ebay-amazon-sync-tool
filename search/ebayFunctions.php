<?php
	include('keys.php');

function active_user_token($active_user) {
	
	$sql = "SELECT * FROM ebay_users WHERE user_id = $active_user";
	$rs = mysql_query($sql);
	$row = mysql_fetch_array($rs);
	
	$token = $row['token'];
	//$token = encrypt_decrypt('decrypt', $token);
	
	return $token;	
	
}

function getItem ($itemid) {
	
	global $token;
	
   $post_data = '<?xml version="1.0" encoding="utf-8"?>
				<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					  <RequesterCredentials>
						<eBayAuthToken>'.$token.'</eBayAuthToken>
					  </RequesterCredentials>
					  <ItemID>'.$itemid.'</ItemID>
					   <DetailLevel>ReturnAll</DetailLevel>
				</GetItemRequest>';
	
	$body = callapi($post_data,"GetItem");



	return $body; 
}


function add_fixed_price_item() {
	global $token,$asin,$title,$ebay_price,$ebay_description,$image_url,$weight,$height,$width,$length,$brand,$ean,$currency_code,$dispatch_time_max,$sku,$upc,$quantity,$condition_id,$listing_type,$duration,$refund_option,$shipping_service,$shipping_type,$shipping_cost,$payment_method,$cat_id,$return_within,$uuid,$site,$mpn,$return_option,$paypal_address;

//$paypal_address = 'test@testi.dev';
   $post_data = '<?xml version="1.0" encoding="utf-8"?>
						<AddFixedPriceItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
						<ErrorLanguage>en_US</ErrorLanguage>
						<WarningLevel>High</WarningLevel>
						<Item>
							<Title>'.$title.'</Title>
							<SKU>'.$sku.'</SKU>
							<PrimaryCategory>
							   <CategoryID>'.$cat_id.'</CategoryID>
							</PrimaryCategory>
							<Description><![CDATA['.$ebay_description.']]></Description>
							<StartPrice>'.$ebay_price.'</StartPrice>
							<ConditionID>'.$condition_id.'</ConditionID>
							<Country>US</Country>
							<CategoryMappingAllowed>true</CategoryMappingAllowed>
							<Currency>'.$currency_code.'</Currency>
							<DispatchTimeMax>'.$dispatch_time_max.'</DispatchTimeMax>
							<ListingType>'.$listing_type.'</ListingType>
							<ListingDuration>'.$duration.'</ListingDuration>
							<PaymentMethods>'.$payment_method.'</PaymentMethods>
							<PayPalEmailAddress>'.$paypal_address.'</PayPalEmailAddress>
							<PictureDetails>
								<PictureURL>'.$image_url.'</PictureURL>
							</PictureDetails>
							<PostalCode>95125</PostalCode>
							<Quantity>'.$quantity.'</Quantity>
							<ReturnPolicy>
								<ReturnsAcceptedOption>'.$return_option.'</ReturnsAcceptedOption>
								<RefundOption>'.$refund_option.'</RefundOption>
								<ReturnsWithinOption>'.$return_within.'</ReturnsWithinOption>
								<Description>If you are not satisfied, return the item for refund.</Description>
								<ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
							</ReturnPolicy>
							<ShippingDetails>
								<ShippingType>'.$shipping_type.'</ShippingType>
								<InternationalShippingDiscountProfileID>0</InternationalShippingDiscountProfileID>
								 <ShippingDiscountProfileID>0</ShippingDiscountProfileID>
								<ShippingServiceOptions>
									<ShippingServicePriority>1</ShippingServicePriority>
									<ShippingService>'.$shipping_service.'</ShippingService>
									<ShippingServiceCost>'.$shipping_cost.'</ShippingServiceCost>
									<ShippingServiceAdditionalCost>30</ShippingServiceAdditionalCost>
								</ShippingServiceOptions>
								<ShippingServiceOptions>
									<ShippingServicePriority>2</ShippingServicePriority>
									<ShippingService>USPSPriority</ShippingService>
									<ShippingServiceCost>'.$shipping_cost.'</ShippingServiceCost>
									<ShippingServiceAdditionalCost>30</ShippingServiceAdditionalCost>
								</ShippingServiceOptions>
								 <ExcludeShipToLocation>AK</ExcludeShipToLocation>
								  <ExcludeShipToLocation>HI</ExcludeShipToLocation>
							</ShippingDetails>
							<UUID>'.$uuid.'</UUID>
							<Site>'.$site.'</Site>						
						</Item>
							<RequesterCredentials>
							<eBayAuthToken>'.$token.'</eBayAuthToken>
							</RequesterCredentials>
							<WarningLevel>High</WarningLevel>
							</AddFixedPriceItemRequest>';
	
	$body = callapi($post_data,"AddFixedPriceItem");
        
	return $body; 
}

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
        
	return $body;
	
}

function end_item($eid,$class_token=null){
	global $token;
//echo 'end---'.$eid;
        
	$post_data= '<?xml version="1.0" encoding="utf-8"?>
					<EndItemsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					  <EndItemRequestContainer>
						<MessageID>1</MessageID>
						<EndingReason>NotAvailable</EndingReason>
						<ItemID>'.$eid.'</ItemID>
						</EndItemRequestContainer>
						<RequesterCredentials>
						<eBayAuthToken>'.$token.'</eBayAuthToken>
						</RequesterCredentials>
						<WarningLevel>High</WarningLevel>
				      </EndItemsRequest>'; 
 
	$body = callapi($post_data,'EndItems');
	
	return $body;

}

function GetSessionID()
{
	global $ru_name;
	
	$post_data= '<?xml version="1.0" encoding="utf-8"?>
					<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					 <RuName>'.$ru_name.'</RuName>
					</GetSessionIDRequest>';
					
	$body = callapi($post_data,'GetSessionID');
	
	return $body;
}

function FetchToken($SessionID)
{
	$post_data  = '<?xml version="1.0" encoding="utf-8"?>
					<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">
						<Version>831</Version>
						<SessionID>'.$SessionID.'</SessionID>
					</FetchTokenRequest>';
					
	$body = callapi($post_data,'FetchToken');

	return $body;
}

function get_suggested_category($title) {
	global $token;
	
	$site_id = 0;
	
	$post_data = '<?xml version="1.0" encoding="utf-8"?>
					<GetSuggestedCategoriesRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					  <RequesterCredentials>
						<eBayAuthToken>'.$token.'</eBayAuthToken>
					  </RequesterCredentials>
					  <Query>'.$title.'</Query>
					</GetSuggestedCategoriesRequest>';
	
	$body = callapi($post_data,"GetSuggestedCategories",$site_id);	
	return $body; 
	
	
}

function get_taxtable(){
	global $token;

	$post_data = '<?xml version="1.0" encoding="utf-8"?>
<GetTaxTableRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>'.$token.'</eBayAuthToken>
  </RequesterCredentials>
  <DetailLevel>ReturnAll</DetailLevel>
  <ErrorLanguage>en_US</ErrorLanguage>
   <WarningLevel>High</WarningLevel>
</GetTaxTableRequest>';

	$body = callapi($post_data,'GetTaxTable');
	return $body;
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
	
	curl_close ($ch); // Close the connection
	
	return $body;
	
}





?>