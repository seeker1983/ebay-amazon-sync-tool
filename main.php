<?php
require_once ('lib/config.php');
require_once('blocks/head.php');
?>
<body>
<?php
    require_once('blocks/menu.php');
    require_once('lib/ebay.php');
    require_once('lib/vendorFunctions.php');

    echo Cache::get("items_${active_user}", function(){
        ob_start();

        global $active_user;

        foreach(DB::query_rows("SELECT * FROM `user_products` WHERE  `UserID`='$active_user' ORDER BY `sort`") as $item)
            $db_items[$item['ItemID']] = $item;

        $itemTypes = array( "ActiveList", "UnsoldList", "BidList", "DeletedFromSoldList", "DeletedFromUnsoldList", "ScheduledList", "SoldList");
        $itemTypes = array( "ActiveList", "UnsoldList");

        foreach($itemTypes as $type)
            $items[$type] = Ebay_deprecated::get_items($type);

        require('blocks/listing_type_navbar.php');
        ?>
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
                <th style="width:50px;">Profit %</th>
                <th style="width:50px;">Vendor quantity</th>
                <th style="width:80px;">Manage</th>
            </tr>
        <?php

            foreach($items as $type => $type_items)
                {
                ?>
                    <tbody ebay-type-container='<?php echo $type; ?>' style='display:none'>
                <?php

                    foreach($type_items as $item) 
                    {                
                        $db_item = isset($db_items[(string)$item->ItemID]) ? $db_items[(string)$item->ItemID] : array();
                        require('blocks/items/vendor_item.php');
                    }
                ?>
                    </tbody>
                <?php
                }
                ?>
          <tbody ebay-type-container='Log' style='display:none'>
              <tr>
                <td colspan="10">
                <pre>
                <? echo htmlspecialchars(implode("\r\n", Log::tail(500)) ); ?>
                </pre>

                </td>
            </tr>
          </tbody>
       </table>
       <?
       return ob_get_clean();
    }, 300);
?>
</body>






