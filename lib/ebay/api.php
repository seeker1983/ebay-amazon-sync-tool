<?php
class EbayApi
{
	public static function active_user_token($active_user)
	{
		
		$sql = "SELECT * FROM ebay_users WHERE user_id = $active_user";
		$rs = mysql_query($sql);
		$row = mysql_fetch_array($rs);
		
		$token = decrypt($row['token']);
		
		return $token;	
		
	}

	public static function get_item ($itemid)
	{
		
		global $token;
		
	   $post_data = '<?xml version="1.0" encoding="utf-8"?>
					<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
						  <RequesterCredentials>
							<eBayAuthToken>'.$token.'</eBayAuthToken>
						  </RequesterCredentials>
						  <ItemID>'.$itemid.'</ItemID>
						   <DetailLevel>ReturnAll</DetailLevel>
					</GetItemRequest>';
		
		$body = self::callapi($post_data,"GetItem");



		return $body; 
	}


	public static function add_fixed_price_item()
	{
		global $token,$asin,$title,$ebay_price,$ebay_description,$image_url,$weight,$height,$width,$length,$brand,$ean,$currency_code,$dispatch_time_max,$sku,$upc,$quantity,$condition_id,$listing_type,$duration,$refund_option,$shipping_service,$shipping_type,$shipping_cost,$payment_method,$cat_id,$return_within,$uuid,$site,$mpn,$return_option,$paypal_address;

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
		
		$body = self::callapi($post_data,"AddFixedPriceItem");
	        
		return $body; 
	}

	public static function get_my_ebay_selling($type, $page_no, $entries = 100)
	{
		global $token;

	   $post_data = '<?xml version="1.0" encoding="utf-8"?>
						<GetMyeBaySellingRequest xmlns="urn:ebay:apis:eBLBaseComponents">
						  <RequesterCredentials>
							<eBayAuthToken>'.$token.'</eBayAuthToken>
						  </RequesterCredentials>
						  <Version>505</Version>
						  <'.$type.'>'
							. ($type=='ActiveList'? '<Sort>TimeLeft</Sort>' : '' )
							.'<Pagination>
							  <EntriesPerPage>' . $entries .'</EntriesPerPage>
							  <PageNumber>'.$page_no.'</PageNumber>
							</Pagination>
						  </'.$type.'>
						</GetMyeBaySellingRequest>';
		
		$body = self::callapi($post_data,"GetMyeBaySelling");

		return $body; 
	}

	public static function revise_price($eid,$price)
	{
		
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

		$body = self::callapi($post_data,'ReviseItem'); 
	        
		return $body;
		
	}
	public static function revise_item($options)
	{
		
		global $token;

		$post_data= '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		<ErrorLanguage>en_US</ErrorLanguage>
		<Item>
			<ItemID>'.$options['id'].'</ItemID>
			<StartPrice>'.$options['price'].'</StartPrice>
	    	<Quantity>' . $options['quantity'] . '</Quantity>
		</Item>
		<RequesterCredentials>
		<eBayAuthToken>'.$token.'</eBayAuthToken>
		</RequesterCredentials>
		<WarningLevel>High</WarningLevel>
		</ReviseItemRequest>'; 

		$body = self::callapi($post_data,'ReviseItem'); 

		return $body;
		
	}



	public static function end_item($eid,$class_token=null){
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
	 
		$body = self::callapi($post_data,'EndItems');
		
		return $body;

	}

	public static function GetSessionID()
	{
		global $ru_name;
		
		$post_data= '<?xml version="1.0" encoding="utf-8"?>
						<GetSessionIDRequest xmlns="urn:ebay:apis:eBLBaseComponents">
						 <RuName>'.$ru_name.'</RuName>
						</GetSessionIDRequest>';
						
		$body = self::callapi($post_data,'GetSessionID');
		
		return $body;
	}

	public static function FetchToken($SessionID)
	{
		$post_data  = '<?xml version="1.0" encoding="utf-8"?>
						<FetchTokenRequest xmlns="urn:ebay:apis:eBLBaseComponents">
							<Version>831</Version>
							<SessionID>'.$SessionID.'</SessionID>
						</FetchTokenRequest>';
						
		$body = self::callapi($post_data,'FetchToken');

		return $body;
	}

	public static function get_suggested_category($title)
	{
		global $token;
		
		$site_id = 0;
		
		$post_data = '<?xml version="1.0" encoding="utf-8"?>
						<GetSuggestedCategoriesRequest xmlns="urn:ebay:apis:eBLBaseComponents">
						  <RequesterCredentials>
							<eBayAuthToken>'.$token.'</eBayAuthToken>
						  </RequesterCredentials>
						  <Query>'.$title.'</Query>
						</GetSuggestedCategoriesRequest>';
		
		$body = self::callapi($post_data,"GetSuggestedCategories",$site_id);	
		return $body; 
		
		
	}

	public static function get_taxtable(){
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

		$body = self::callapi($post_data,'GetTaxTable');
		return $body;
	}

	public static function callapi($post_data,$call_name)
	{	
		global $COMPATIBILITYLEVEL,$DEVNAME,$APPNAME, $CERTNAME,$SiteId,$eBayAPIURL;

		$ebayapiheader = array ( 
								"X-EBAY-API-COMPATIBILITY-LEVEL: $COMPATIBILITYLEVEL", 
								"X-EBAY-API-DEV-NAME: $DEVNAME", 
								"X-EBAY-API-APP-NAME: $APPNAME", 
								"X-EBAY-API-CERT-NAME: $CERTNAME", 
								"X-EBAY-API-SITEID: $SiteId",
								"X-EBAY-API-CALL-NAME: ".$call_name); 


	   	$ch = curl_init(); 
		$res= curl_setopt ($ch, CURLOPT_URL, $eBayAPIURL); 


		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 

		curl_setopt ($ch, CURLOPT_HEADER, 0); // 0 = Don't give me the return header 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $ebayapiheader); // Set this for eBayAPI 
		curl_setopt($ch, CURLOPT_POST, 1); // POST Method 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); //My XML Request 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 

	    if( ($body = curl_exec($ch)) === false)
	    {
	    	die("Api request error: " . curl_error($ch));
	    }

		
		curl_close ($ch); // Close the connection

        $xml = simplexml_load_string($body);
        if($xml->Ack == 'Failure')
        {
        	xp($xml);
        }

		
		return $body;
		
	}

	
}


?>