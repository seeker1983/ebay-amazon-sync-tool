<?
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

require_once('lib/ebay-sdk.php');

class EbaySelling
{

    public static function get_items($type = 'ActiveList')
    {
        global $service, $token, $user;

        $request = new Types\GetMyeBaySellingRequestType();

        $request->RequesterCredentials = new Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = $token;


        $request->$type = new Types\ItemListCustomizationType();
        $request->$type->Include = true;
        $request->$type->Pagination = new Types\PaginationType();
        $request->$type->Pagination->EntriesPerPage = 100;

        if($type == 'ActiveList')
            $request->$type->Sort = Enums\ItemSortTypeCodeType::C_TIME_LEFT;

        $pageNum = 1;

        $items = array();

        do {
            $request->$type->Pagination->PageNumber = $pageNum;

            /**
             * Send the request to the GetMyeBaySelling service operation.
             *
             * For more information about calling a service operation, see:
             * http://devbay.net/sdk/guides/getting-started/#service-operation
             */
            $response = $service->getMyeBaySelling($request);

            /**
             * Output the result of calling the service operation.
             *
             * For more information about working with the service response object, see:
             * http://devbay.net/sdk/guides/getting-started/#response-object
             */
            if (isset($response->Errors)) 
            {
                show_response_errors($response);
                return false;
            }

            if ($response->Ack !== 'Failure' && isset($response->$type)) 
                foreach ($response->$type->ItemArray->Item as $item) 
                    $items[]  = $item;

            $pageNum++;

        } while(isset($response->$type) && $pageNum <= $response->$type->PaginationResult->TotalNumberOfPages);

        return $items;
    }


}

