<?PHP
$token= 'AgAAAA**AQAAAA**aAAAAA**SakyUQ**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4CoAZWFogmdj6x9nY+seQ**z48BAA**AAMAAA**gPnELAEIQDNwy0teP2XodQlbf7gClSTV48789/bdCYq/wBOwQ1zFpvryiwbsi3unRa/7Gqf5LIjqIXuMhFivXruV/yjQi3odiGuJ7GLkMhHtmo9N7QJ3UP8Fe9Ps+3pCM+NQNpTEMfpzQv2mjTk/66xHZNdwrkHwNqWphxzQMAps1nPsi5Uz2e3sYRETlSE8rXiGUl8Z+Wk2aMOZqMBO3zpDG+cWo65VhHP8q1oFvv/bNmV//dDxZG8IZRwYwnUB3AgJ0D9M1rD2jy+VC8OwX/LBi508YVTgYPYO9QgNryyqcxfNfWgPqGv7X+xPVArJmA9MSI8STr3sz6pjnauN8srUAqI3WXIZreWbsHNN2M/wPEnh2gi5McLbxFmlvK0dufrwyK7sGqveJuxBmJ2PJMJhHODY9O2cpSEYUkz+N16fJHImSCCQOL3NtGer7qA1t2NDCb7GX3Q04OzuVzDeoicJOTpfQ5Nr805jy37IaYawJ1CozjM/+mGJogh6YLL653amY4Jw6D/j+qyoFWOEmdflsI6SQzOWVjCLvRz1cJfdOSEtf/TCCKfvG8MGDFdA1xA32x+IPyGPbbUQ9AzdBZUiPIC4KM2AGh1dW+toqjxdxAc8UOXalEO5EpXVVclkks9t06gAO6aw4o5SMNlvZ82m9sdchBe2oChahZFkviIuyqXaUvpSt0/vO0A5Jnzrxi6QvxVY8skkYz3e4o7bCuxn9MWavAGvGELMXbmim1wx52N1IBBo9VwBiIJocJ4M'; 

$eBayAPIURL = "https://api.sandbox.ebay.com/ws/api.dll"; 
$COMPATIBILITYLEVEL = 819; 
$DEVNAME = 'eb0511a6-d4ac-491d-a375-73c1c9f30ec4'; 
$APPNAME = 'Imarasof-9e26-49b5-b917-3f6564350b67'; 
$CERTNAME= '0938a88e-69fb-41f4-9ac0-9e6bfa2d4522'; 
$SiteId = 0; 
//$callname = "AddItem"; 


/*<ProductListingDetails>
							  <UPC>'.$upc.'</UPC>
							  <BrandMPN>
								<Brand>'.$brand.'</Brand>
								<MPN>'.$mpn.'</MPN>
							  </BrandMPN>
							</ProductListingDetails>*/

		

function add_fixed_price_item() {
	
	global $token,$asin,$title,$ebay_price,$ebay_description,$image_url,$weight,$height,$width,$length,$brand,$ean,$currency_code,$dispatch_time_max,$sku,$upc,$quantity,$condition_id,$listing_type,$duration,$refund_option,$shipping_service,$shipping_type,$shipping_cost,$payment_method,$cat_id,$return_within,$uuid,$site,$mpn;
	
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
							<PayPalEmailAddress>rinsad@gmail.com</PayPalEmailAddress>
							<PictureDetails>
								<PictureURL>'.$image_url.'</PictureURL>
							</PictureDetails>
							<PostalCode>95125</PostalCode>
							<Quantity>'.$quantity.'</Quantity>
							<ReturnPolicy>
								<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
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