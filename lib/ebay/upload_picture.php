<?php
require_once('lib/ebay-sdk.php');

use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

function ebay_upload_picture($name, $blob)
{
	global $service, $token, $real_service, $real_token;

	$request = new Types\UploadSiteHostedPicturesRequestType();
	$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
	$request->RequesterCredentials->eBayAuthToken = $real_token;

	$request->PictureName = $name;

//	file_put_contents('d:/photo/test.jpg', $blob);
//
//	$request->attachment(file_get_contents('d:/photo/test.jpg'), 'image/jpeg');
	$request->attachment($blob, 'image/jpeg');

	$response = $real_service->uploadSiteHostedPictures($request);

	if ($response->Ack !== 'Failure') 
		return $response->SiteHostedPictureDetails->FullURL;

	if (isset($response->Errors)) {
		print("<pre>");
	    foreach ($response->Errors as $error) {
	        printf("%s: %s\n%s\n\n",
	            $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
	            $error->ShortMessage,
	            $error->LongMessage
	        );
	    }
	    die("Error uploading pictures.");
	}

}


