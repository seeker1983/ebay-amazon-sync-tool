<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/ebay.php');
require_once('lib/scrape.php');

Log::push('----- Cron job started -----');

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $item = Ebay::get_item($id);
    $sku = $item->SKU;

    xd(scrap_item($sku));
}

xd(1);

$users = DB::query_rows("SELECT * from ebay_users");

touch('lock');

foreach($users as $user) 
{
    $user_id = $user['user_id'];

    $DEVNAME = trim($user['dev_name']);
    $APPNAME = trim($user['app_name']);
    $CERTNAME = trim($user['cert_name']);
    $token = decrypt($user['token']);

    $paypal_email = trim($user['paypal_address']);

    DB::query("update `user_products` set `Qty`='-1' where `UserID`='$user_id'");

    $items = Ebay::get_active_items();

    foreach ($items as $num =>$item) 
    {
        /* Load item data from vendor site */

        $result = scrap_item($item->SKU);

        if(empty($result['offerprice']))
        {
          $result['offerprice'] = 0;
          $result['quantity'] = 0;
        }

        /* Check if item exists */

        $current = DB::query_row("SELECT VendorQty from `user_products` where ItemID='$item->ItemID'");

        if($current && $current['VendorQty']>0 && $result['quantity'] == 0)
            mail('jgiven1@gmail.com', "$item->Title OUT of stock", "Item $item->ViewItemURL is out of stock.");

        if($current)
        {
            $sql = "UPDATE `user_products` SET `VendorPrice` = '${result['offerprice']}',
                  `VendorQty` = '${result['quantity']}',
                  `Qty` = '$ebay_quantity'
                   WHERE `user_products`.`ItemID` ='$item->ItemID'";
        }
        else
        {   
            $sql = "INSERT INTO  `user_products` (
                `UserID` ,
                `ItemID` ,
                `Qty` ,
                `Price` ,
                `Title` ,
                `SKU` ,
                `Image` ,
                `ItemUrl` ,
                `VendorPrice` ,
                `VendorQty`,
                `ProfitRatio`,
                `VendorUrl`,
                `sort`
                )
                VALUES (
                '$user_id',  '$item->ItemID',  '$ebay_quantity',  '$item->BuyItNowPrice',
                '$item->Title',  '$item->SKU',  
                '$item->PictureDetails->GalleryURL',
                '$item->ListingDetails->ViewItemURL', 
                 '${result['offerprice']}',  '${result['quantity']}',
                 '15.00',
                 '${result['url']}',
                 '$num'
            )";
        }
        if(file_exists('stop'))
            xd('Interrupted');

        DB::query($sql);
        Log::push(str_replace("\n", '', $sql));

        print($sql . "<br>");
        sleep(2);
    }
}
unlink('lock');

