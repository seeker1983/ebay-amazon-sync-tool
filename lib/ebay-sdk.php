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
