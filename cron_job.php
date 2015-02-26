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
    die('Another instance is already running: ' . Lock::get_msg($lock_file));
}

Log::push('----- Cron job started -----');

foreach($users as $user) 
{
    $user_id = $user['user_id'];

    $DEVNAME = trim($user['dev_name']);
    $APPNAME = trim($user['app_name']);
    $CERTNAME = trim($user['cert_name']);

    $paypal_email = trim($user['paypal_address']);

    $token = decrypt($user['token']);

    /* Relisting inactive items */

    $items = EbaySelling::get_items('UnsoldList');

    foreach ($items as $num =>$ebay_item) 
    {
        if(!empty($_GET['num']) && $_GET['num'] != $num)
            continue;     

        if(!empty($_GET['id']) && $_GET['id'] != $ebay_item->ItemID)
            continue;

        $item = Item::from_ebay_data($ebay_item);
        $item->scrape($ebay_item->SKU);

        if($item->vendor_data['offerprice'] && $item->vendor_data['quantity'] && $item->vendor_data['prime'] == 'Yes')
        {
            $current = Ebay::get_item_by_sku($ebay_item->SKU);

            if(@ $error = $current->Errors->offsetGet(0))
                if($error->ErrorCode == '21916270') /* There is no active item matching the specified SKU */
                {
                    $response = $item->relist();
                }
        }

        if(file_exists('stop'))
            xd('Interrupted');
    }

    /* Updating active items */

    $items = EbaySelling::get_items('ActiveList');

    foreach ($items as $num =>$ebay_item) 
    {
        if(!empty($_GET['num']) && $_GET['num'] != $num)
            continue;     

        if(!empty($_GET['id']) && $_GET['id'] != $ebay_item->ItemID)
            continue;

        $item = Item::from_ebay_data($ebay_item);
        $item->scrape($ebay_item->SKU);

        $item->update();

        $item->setSort($num);


        if(file_exists('stop'))
            xd('Interrupted');
    }
}
Log::push('----- Cron job finished -----');


