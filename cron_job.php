<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/ebay.php');
require_once('lib/scrape.php');

if(isset($_GET['user_id']))
{
    $users = array(DB::query_row("SELECT * from ebay_users where `user_id`='$user_id'"));
}

$users = DB::query_rows("SELECT * from ebay_users");

if(file_exists('lock'))
{
//    die('Another instance is running')    ;
}

touch('lock');

Log::push('----- Cron job started -----');

foreach($users as $user) 
{
    $user_id = $user['user_id'];

    $DEVNAME = trim($user['dev_name']);
    $APPNAME = trim($user['app_name']);
    $CERTNAME = trim($user['cert_name']);
    $token = decrypt($user['token']);

    $paypal_email = trim($user['paypal_address']);

//    DB::query("update `user_products` set `Qty`='-1' where `UserID`='$user_id'");

    $items = Ebay::get_active_items();

    foreach ($items as $num =>$item) 
    {
        /* Load item data from vendor site */
//        if(!preg_match('%^OS%', $item->SKU)) continue;
        if(!preg_match('%231454408857%', $item->ItemID)) continue;

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
                  `Qty` = '$item->QuantityAvailable'
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
                '$user_id',  '$item->ItemID',  '$item->QuantityAvailable',  '$item->BuyItNowPrice',
                '" . mysql_real_escape_string($item->Title) . "',  
                '" . mysql_real_escape_string($item->SKU) . "',  
                '$item->PictureDetails->GalleryURL',
                '$item->ListingDetails->ViewItemURL', 
                 '${result['offerprice']}',  
                 '${result['quantity']}',
                 '15.00',
                 '${result['url']}',
                 '$num'
            )";
        }
        if(file_exists('stop'))
            xd('Interrupted');
        xd($sql);

        DB::query($sql);
        Log::push(str_replace("\n", '', $sql));

        print($sql . "<br>");
        sleep(2);
    }
}
Log::push('----- Cron job finished -----');

unlink('lock');

