<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/ebay/selling.php');
require_once('lib/item.php');

if(isset($_GET['user_id']))
{
    $users = array(DB::query_row("SELECT * from ebay_users where `user_id`='${_GET['user_id']}'"));
    $lock_file = LOCK_FILE . '_' . $_GET['user_id'];
}
else
{
    $users = DB::query_rows("SELECT * from ebay_users");
    $lock_file = LOCK_FILE . '';
}

if(!Lock::lock($lock_file, "LOCK", 0*3600))
{
    //die('Another instance is already running: ' . Lock::get_msg($lock_file));
}

Log::push('----- Cron job started -----');

foreach($users as $user_data) 
{
    $user_id = $user_data['user_id'];
    $_user = new User($user_id);

    $DEVNAME = trim($user_data['dev_name']);
    $APPNAME = trim($user_data['app_name']);
    $CERTNAME = trim($user_data['cert_name']);

    $paypal_email = trim($user_data['paypal_address']);

    $token = ($user_data['token']);

    $service = new \DTS\eBaySDK\Trading\Services\TradingService(array(
        'apiVersion' => $config['tradingApiVersion'],
        'sandbox' => $user_data['sandbox'] == 1,
        'siteId' => \DTS\eBaySDK\Constants\SiteIds::US,
        'devId' => $user_data['dev_name'],
        'appId' => $user_data['app_name'],
        'certId' => $user_data['cert_name'],
        'debug' => DEBUG,

    ));

    /* Relisting inactive items */



//    $items = EbaySelling::get_items('UnsoldList');

    $items = array();

    foreach ($items as $num =>$ebay_item) 
    {
        if(!empty($_GET['num']) && $_GET['num'] != $num)
            continue;     

        if(!empty($_GET['id']) && $_GET['id'] != $ebay_item->ItemID)
            continue;

        if(!empty($_GET['SKU']) && $_GET['SKU'] != $ebay_item->SKU)
            continue;

        $item = Item::from_ebay_data($ebay_item);
        $item->scrape($ebay_item->SKU);
        sleep(2);

        if(!empty($item->vendor_data['scrapok']) && !empty($item->vendor_data['offerprice']) && $item->vendor_data['quantity'] && $item->vendor_data['prime'] == 'Yes')
        {
            $current = Ebay::get_item_by_sku($ebay_item->SKU);

            if(@ $error = $current->Errors->offsetGet(0))
                if($error->ErrorCode == '21916270') /* There is no active item matching the specified SKU */
                {
                    $item->log('Should is back in stock.');
                    $_user->notify("Item is back in stock", $item->get_links() . ' can be relisted');
                    //$response = $item->relist();

                }
        }

        if(file_exists('stop'))
            xd('Interrupted');

        gc_collect_cycles();
    }

    /* Updating active items */

    $items = EbaySelling::get_items('ActiveList');

    foreach ($items as $num =>$ebay_item) 
    {
        if(!empty($_GET['num']) && $_GET['num'] != $num)
            continue;     

        if(!empty($_GET['id']) && $_GET['id'] != $ebay_item->ItemID)
            continue;

        if(!empty($_GET['SKU']) && $_GET['SKU'] != $ebay_item->SKU)
            continue;

        $item = Item::from_ebay_data($ebay_item);
        $item->scrape($ebay_item->SKU);
        sleep(2);

        if(! $item->vendor_data['scrapok'])
        {
            echo($ebay_item->ItemID . " => " . $ebay_item->SKU . " scrape FAIL<br>");
            continue;
        }

        echo($ebay_item->ItemID . " => " . $ebay_item->SKU . " scrape OK<br>");

        $item->update();

        $item->setSort($num);


        if(file_exists('stop'))
            xd('Interrupted');

        gc_collect_cycles();
    }
}
Log::push('----- Cron job finished -----');


