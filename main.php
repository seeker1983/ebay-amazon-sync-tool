<?php
require_once ('lib/config.php');
require_once('blocks/head.php');
?>
<body>
<?
    require_once('blocks/menu.php');
    require_once('lib/ebay.php');
    require_once('lib/vendorFunctions.php');

    $items = Ebay::get_active_items();

    
    foreach(DB::query_rows("SELECT * FROM `user_products` WHERE  `UserID`='$active_user' ORDER BY `sort`") as $item)
        $db_items[$item['ItemID']] = $item;

    $count = count($items); 

    if ($count == 0) 
    {
        ?>
            <div id="ErrorMsg" class="alert alert-error">
    			<button type="button" class="close" data-dismiss="alert">&times;</button>
    			No items found. Error?
            </div>
            <p><strong>Products Unavailable</strong></p>
        <?php
    } 
    else 
    {
            ?>
            <h3>Current products on eBay(<?php echo $count;?>)</h3>
            <table class="table table-bordered">
                <tr>
                    <th style="width:80px;">eBbay ID</th>
                    <th style="width:80px;">SKU</th>
                    <th style="width:320px;">Description</th>
                    <th style="width:50px;">Image</th>
                    <th style="width:50px;">Ebay price</th>
                    <th style="width:50px;">Rec. price</th>
                    <th style="width:50px;">Vendor</th>
                    <th style="width:50px;">Vendor price</th>
                    <th style="width:50px;">Vendor quantity</th>
                    <th style="width:50px;">Update item data</th>
                </tr>

            <?php
            foreach($items as $item) 
            {                
                $db_item = isset($db_items[(string)$item->ItemID]) ? $db_items[(string)$item->ItemID] : array();
                $name = str_replace('-', ' ', preg_replace('%http://www.ebay.com/itm/(.*)/\d+%', '$1', $item->ListingDetails->ViewItemURL));
            ?>
                <tr>
                    <td> <a href="<?php echo $item->ListingDetails->ViewItemURL; ?>" target=_blank> <?php echo $item->ItemID; ?> </a></td>
                    <td> <a href="<?php echo get_url_from_sku($item->SKU); ?>" target=_blank> <?php echo $item->SKU; ?>  </a></td>
                    <td> <?php echo $name; ?> </td>
                    <td> <img src='<?php echo $item->PictureDetails->GalleryURL; ?>' /> </td>
                    <td> <?php echo $item->BuyItNowPrice; ?> </td>
                    <td> <?php echo (empty($db_item['VendorPrice'])?'':calculate_rec_price($db_item['VendorPrice'])); ?> </td>
                    <td> <?php echo get_vendor_from_sku($item->SKU); ?> </td>
                    <td id='vendor_price_<?php echo $item->SKU;?>'> <?php echo ($db_item? $db_item['VendorPrice']: $update); ?></td>
                    <td id='vendor_quantity_<?php echo $item->SKU;?>'> <?php echo ($db_item? $db_item['VendorQty']: ''); ?></td>
                    <td> 
                        <a href="#" onclick="update_item('<?php echo $item->ItemID;?>')">
                            <button class="btn btn-primary" type="button">Update</button>
                        </a>
                    </td>
                </tr>
            <? 
            }   
            ?>
           </table>
    <?
        }
    ?>

</body>
