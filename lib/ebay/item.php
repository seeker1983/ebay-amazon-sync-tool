<?
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

require_once('lib/ebay-sdk.php');

class Ebay
{
	public static function add_item($item_data)
	{
		global $service, $token, $user;

		if($item_data['verify'])
			$request = new Types\VerifyAddFixedPriceItemRequestType();
		else
			$request = new Types\AddFixedPriceItemRequestType();
		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $token;
		$item = new Types\ItemType();

		$item->ListingType = Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM;
		$item->Quantity = 1;

		/**
		 * Let the listing be automatically renewed every 30 days until cancelled.
		 */
//		$item->ListingDuration = Enums\ListingDurationCodeType::C_GTC;
		$item->ListingDuration = $item_data['duration'];

		/**
		 * The cost of the item is $19.99.
		 * Note that we don't have to specify a currency as eBay will use the site id
		 * that we provided earlier to determine that it will be United States Dollars (USD).
		 */
		
		$item->StartPrice = new Types\AmountType(array('value' => floatval($item_data['price'])));

		/**
		 * Allow buyers to submit a best offer.
		 */
		$item->BestOfferDetails = new Types\BestOfferDetailsType();
		$item->BestOfferDetails->BestOfferEnabled = false;

		/**
		 * Automatically accept best offers of $17.99 and decline offers lower than $15.99.
		 */
		$item->ListingDetails = new Types\ListingDetailsType();
//		$item->ListingDetails->BestOfferAutoAcceptPrice = new Types\AmountType(array('value' => floatval($item_data['price']) * 0.95));
		$item->ListingDetails->MinimumBestOfferPrice = new Types\AmountType(array('value' => floatval($item_data['price']) * 0.90));

		/**
		 * Provide a title and description and other information such as the item's location.
		 * Note that any HTML in the title or description must be converted to HTML entities.
		 */

		$item->Title = htmlentities(substr($item_data['title'], 0, 79));
		$item->Description = htmlentities($item_data['desc']);
		$item->SKU = (string)$item_data['sku'];
		$item->Country = 'US';
		$item->Location = $user['location'];
		$item->PostalCode = $user['postal_code'];
		/**
		 * This is a required field.
		 */
		$item->Currency = 'USD';

		/**
		 * Display a picture with the item.
		 */
		$item->PictureDetails = new Types\PictureDetailsType();
		$item->PictureDetails->GalleryType = Enums\GalleryTypeCodeType::C_GALLERY;
		$item->PictureDetails->PictureURL = array_slice($item_data['gallery'], 0, 10);

		/**
		 * List item in the Books > Audiobooks (29792) category.
		 */
		$item->PrimaryCategory = new Types\CategoryType();
		$item->PrimaryCategory->CategoryID = (string) $item_data['category_id'];

		/**
		 * Tell buyers what condition the item is in.
		 * For the category that we are listing in the value of 1000 is for Brand New.
		 */
		$item->ConditionID = 1000;

		/**
		 * The return policy.
		 * Returns are accepted.
		 * A refund will be given as money back.
		 * The buyer will have 14 days in which to contact the seller after receiving the item.
		 * The buyer will pay the return shipping cost.
		 */
		$item->ReturnPolicy = new Types\ReturnPolicyType();
		$item->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsAccepted';
		$item->ReturnPolicy->RefundOption = 'MoneyBack';
		$item->ReturnPolicy->ReturnsWithinOption = 'Days_14';
		$item->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';



		$item->ShippingDetails = new Types\ShippingDetailsType();
		$item->ShippingDetails->ShippingType = Enums\ShippingTypeCodeType::C_FLAT;

		/**
		 * Create our first domestic shipping option.
		 * Offer the Economy Shipping (1-10 business days) service at $2.00 for the first item.
		 * Additional items will be shipped at $1.00.
		 */
		$shippingService = new Types\ShippingServiceOptionsType();
		$shippingService->ShippingServicePriority = 1;
		$shippingService->ShippingService = 'Other';
		$shippingService->ShippingServiceCost = new Types\AmountType(array('value' => 0.00));
		$shippingService->ShippingServiceAdditionalCost = new Types\AmountType(array('value' => 0.00));
		$item->ShippingDetails->ShippingServiceOptions[] = $shippingService;




		/**
		 * Buyers can use one of two payment methods when purchasing the item.
		 * Visa / Master Card
		 * PayPal
		 * The item will be dispatched within 1 business days once payment has cleared.
		 * Note that you have to provide the PayPal account that the seller will use.
		 * This is because a seller may have more than one PayPal account.
		 */
		$item->PaymentMethods = explode(',', $user['payment_methods']);

		$item->PayPalEmailAddress = $user['paypal_address'];
		$item->DispatchTimeMax = 1;

		$request->Item = $item;

		if(!empty($item_data['verify']))
			$response = $service->verifyAddFixedPriceItem($request);
		else
			$response = $service->addFixedPriceItem($request);

		if(!empty($item_data['test']))
		{
			xpe($service->logger());
		}

		/**
		 * Output the result of calling the service operation.
		 *
		 * For more information about working with the service response object, see:
		 * http://devbay.net/sdk/guides/getting-started/#response-object
		 */

	 	return $response;	
	}

	public static function drop_item($item_id)
	{
		global $service, $token, $user;

		$request = new Types\EndFixedPriceItemRequestType();


		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $token;

		$request->EndingReason = "NotAvailable";
		$request->ItemID=$item_id;

			$response = $service->EndFixedPriceItem($request);

	//  xpe($service->logger());

	 	return $response;	
	}

	public static function relist_item($item_id)
	{
		global $service, $token, $user;

		$request = new Types\RelistFixedPriceItemRequestType();


		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $token;

		$item = new Types\ItemType();
		$item->Quantity = 1;
		$item->ItemID = $item_id;

		//$item->StartPrice = new Types\AmountType(array('value' => floatval($item_data['price'])));

		$request->Item=$item;
		$response = $service->RelistFixedPriceItem($request);

	//  xpe($service->logger());

	 	return $response;	
	}

	public static function revise_item($item_id, $options = array())
	{
		global $service, $token, $user;

		$request = new Types\ReviseFixedPriceItemRequestType();

		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $token;

		$item = new Types\ItemType();
		$item->ItemID = $item_id;

		if(!empty($options['quantity']))
			$item->Quantity = intval($options['quantity']);

		if(!empty($options['price']))
			$item->StartPrice = new Types\AmountType(array('value' => floatval($options['price'])));

		$request->Item=$item;
		$response = $service->ReviseFixedPriceItem($request);

	 	return $response;	
	}

	public static function get_item($item_id)
	{
		global $service, $token, $user;

		$request = new Types\GetItemRequestType();

		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $token;

		$request->ItemID = $item_id;

		$response = $service->GetItem($request);

	 	return $response;	
	}

	public static function get_item_by_sku($sku)
	{
		global $service, $token, $user;

		$request = new Types\GetItemRequestType();

		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $token;

		$request->SKU = $sku;

		$response = $service->GetItem($request);

	 	return $response;	
	}

	public static function get_categories($options = array())
	{
		global $service, $token, $user;

		$request = new Types\GetCategoriesRequestType();

		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $token;

		$request->DetailLevel = array('ReturnAll');

		$request->OutputSelector = array(
		    'CategoryArray.Category.CategoryID',
		    'CategoryArray.Category.CategoryParentID',
		    'CategoryArray.Category.CategoryLevel',
		    'CategoryArray.Category.CategoryName'
		);

		if(isset($options['LevelLimit'])) $request->LevelLimit = $options['LevelLimit'];
		if(isset($options['CategoryParent'])) $request->CategoryParent = $options['CategoryParent'];

		$response = $service->getCategories($request);		

	 	return $response;	
	}

	public static function get_suggested_categories($query)
	{
		global $real_service, $real_token, $user;

		$request = new Types\GetSuggestedCategoriesRequestType();

		$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
		$request->RequesterCredentials->eBayAuthToken = $real_token;

		$request->Query = $query;

		$response = $real_service->getSuggestedCategories($request);	

	 	return $response;	
	}



}

