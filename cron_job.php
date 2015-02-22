<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/ebay.php');
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

    /* Relisting inactive items */

    $items = Ebay_deprecated::get_items('UnsoldList');

    foreach ($items as $num =>$ebay_item) 
    {
//        if($ebay_item->ItemID != '110155539176')
            continue;

        $item = Item::from_ebay_data($ebay_item);
        $item->scrape($ebay_item->SKU);

        if($item->vendor_data['offerprice'] && $item->vendor_data['quantity'] && $item->vendor_data['prime'] == 'Yes')
            $item->relist();

        if(file_exists('stop'))
            xd('Interrupted');
    }

    /* Updating active items */

    $items = Ebay_deprecated::get_items();

    foreach ($items as $num =>$ebay_item) 
    {
        if(!empty($_GET['num']) && $_GET['num'] != $num)
            continue;     

        if(!empty($_GET['id']) && $_GET['id'] != $ebay_item->ItemID)
            continue;

        $item = Item::from_ebay_data($ebay_item);
        $item->scrape($ebay_item->SKU);

        $item->update();

        if(file_exists('stop'))
            xd('Interrupted');
    }
}
Log::push('----- Cron job finished -----');


