<?php
require_once 'ebay-sdk/vendor/autoload.php';

global $user, $service, $token;

$config = array(
    'sandbox' => array( 
        'devId' => $user['dev_name'],
        'appId' => $user['app_name'],
        'certId' => $user['cert_name'],
        'userToken' => $token
    ),
    'production' => array( 
        'devId' => $user['dev_name'],
        'appId' => $user['app_name'],
        'certId' => $user['cert_name'],
        'userToken' => $token
    ),
    'findingApiVersion' => '1.12.0',
    'tradingApiVersion' => '871',
    'shoppingApiVersion' => '871',
    'halfFindingApiVersion' => '1.2.0'
);



/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Enums;

define('DEBUG', true);

/**
 * Create the service object.
 *
 * For more information about creating a service object, see:
 * http://devbay.net/sdk/guides/getting-started/#service-object
 */


$service = new Services\TradingService(array(
    'apiVersion' => $config['tradingApiVersion'],
    'sandbox' => $user['sandbox'] == 1,
    'siteId' => Constants\SiteIds::US,
    'devId' => $user['dev_name'],
    'appId' => $user['app_name'],
    'certId' => $user['cert_name'],
    'debug' => DEBUG,

));

$real_service = new Services\TradingService(array(
    'apiVersion' => $config['tradingApiVersion'],
    'siteId' => Constants\SiteIds::US,
    'devId' => $user['dev_name'],
    'appId' => $user['app_name'],
    'certId' => $user['cert_name'],
    'debug' => DEBUG,

));

$real_token = 'AgAAAA**AQAAAA**aAAAAA**kuG2VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AHkouhAZSCpw+dj6x9nY+seQ**4KgCAA**AAMAAA**6OVqUNi37vqfQcNAAHsHzYb2czyhOKreivpS75IFLavSQLddYPyH4KqYXHvXUeL4LmCZUG/M+CKv234+x5UHNBfG2WVSb8UR3hvRwblxAMNZWqKACehoB4E0hfbMjVP/xY/nOqrYmRaqE+3ZtAYIj1r75+K7XLtpQwLGdaGspJFe9AkTK7eM2Qx9IMzCCk6xXU5Sfbb4UvxS2SshStMI9tTgAc4VJTd/bjNJ3b+7dzbosTPUAq2sLz6uDcr6mauNgXe2bD2n09K2fzt/mbZiHm8+kbPgaEK0ushIECTMz4KU4aw7YWGP2Ib0VRlleG6Klkg9E8EKBJqxQllICX9PYbZEQAXxaH1NSx9K2CePIrkM7tnwLYlNZlGpFsDr2iBQxZhs/8LE2f6EwhndyRX03AJa6ZH71DvCMIU1bT5xHFIwU6ZIjQ8z4hmWFRXuWaefjimlQRjxP0uTfsa9P9owAjXXmuqzQBYx6aPuSuE3dB0XxGJhfyoXGu8AwSes5AWMyaBd7bD5qd1RIRPMlAP4zSpCL4JPk/2cacqx+dx+CxkjKN11MJUBjl/R2iDkFqSGzBsv8jHtJ9PQJCOJzrfYB4cntSlVtPG52beOLtyf0Qc9VjVzfKnVZlcaoZ/1v4QT0GSjPqAW1/Tw+0Ml7YsPRc6MbwQ26muIbf+nFVrHy7GhG8KDaiwxcWub2SGekCD3yahEbY2Cps7/y8rWxx9a379DnzF9+uZeNA+sL7+K0mRrtKHxYoLkL7rI12eAMCJV';

if(DEBUG)
{
    $logger = new \DTS\eBaySDK\Mocks\Logger();
    $service->logger($logger);
}

function show_log()
{
    global $logger;

    xp($logger);
}

function show_response_errors($response)
{
    if (isset($response->Errors)) {
        foreach ($response->Errors as $error) {
            printf("%s: %s\n%s\n\n",
                $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                $error->ShortMessage,
                $error->LongMessage
            );
        }
    }    
}
