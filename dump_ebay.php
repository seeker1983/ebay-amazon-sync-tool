<?php
require_once ('lib/config.php');
require_once('blocks/head.php');
?>
<body>
<?
    require_once('blocks/menu.php');

    require_once('lib/ebay.php');

    $items = Ebay::get_active_items();

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
                    <th style="width:50px;">CurrentPrice</th>
                    <th style="width:50px;">BuyItNowPrice</th>
                    <th style="width:50px;">Quantity</th>
                    <th style="width:120px;">Start time</th>
                </tr>

            <?php
            foreach($items as $item) 
            {
                $name = str_replace('-', ' ', preg_replace('%http://www.ebay.com/itm/(.*)/\d+%', '$1', $item->ListingDetails->ViewItemURL));
            ?>
                <tr>
                    <td> <a href="<?php echo $item->ListingDetails->ViewItemURL; ?>"> <?php echo $item->ItemID; ?> </a></td>
                    <td> <?php echo $item->SKU; ?> </td>
                    <td> <?php echo $name; ?> </td>
                    <td> <img src='<?php echo $item->PictureDetails->GalleryURL; ?>' /> </td>
                    <td> <?php echo $item->SellingStatus->CurrentPrice; ?> </td>
                    <td> <?php echo $item->BuyItNowPrice; ?> </td>
                    <td> <?php echo $item->QuantityAvailable; ?> </td>
                    <td> <?php echo $item->ListingDetails->StartTime; ?> </td>

                </tr>
            <? 
            }   
            ?>
           </table>
    <?
        }
    ?>

</body>
