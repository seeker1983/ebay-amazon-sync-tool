<?php
set_time_limit(0);
require ('AddFixedPriceItem.php');


		$title = 'Test title 04';
		$description = 'Test Desc';
		$image_url = 'http://ecx.images-amazon.com/images/I/31QElIhkfrL.jpg';
		$weight = 50;
		$height = 20;
		$width = 30;
		$length = 60;
		$brand = 'DR';
		$mpn = 'MH5-45';
		$ean = 0736021533601;
		$ebay_price = 600;
		$currency_code = 'USD';
		$dispatch_time_max = 3;
		$duration = 'Days_7';
		$sku = 'MySku';
		$upc = 736021533601;
		$quantity = 3;
		$ebay_price = 650;
		$condition_id = 1000;
		$listing_type = 'FixedPriceItem';
		$refund_option = 'MoneyBack';
		$shipping_service = 'UPSGround';
		$shipping_type = 'Flat';
		$shipping_cost = 2.0;
		$payment_method = 'PayPal';
		$cat_id = 111422;
		$return_within = 'Days_30';
		$uuid = md5(uniqid(rand(), true));
		$site = "US";
		
		
		$add_request = add_fixed_price_item();
		$response = simplexml_load_string($add_request);
		
		print_r($response);

?>