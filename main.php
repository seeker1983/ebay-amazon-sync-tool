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

        foreach(DB::query_rows("SELECT user_products.*, TIMESTAMPDIFF(HOUR , lastUpdate, NOW()) as ts  FROM `user_products` WHERE  `UserID`='$active_user' ORDER BY `sort`") as $item)
            $db_items[$item['ItemID']] = $item;

        $itemTypes = array( "ActiveList", "UnsoldList", "BidList", "DeletedFromSoldList", "DeletedFromUnsoldList", "ScheduledList", "SoldList");
        $itemTypes = array( "ActiveList");

        foreach($itemTypes as $type)
            $items[$type] = Ebay_deprecated::get_items($type);
//        xp($items);

        require('blocks/listing_type_navbar.php');
        ?>
        <table class="table table-bordered" id="items_table" class="tablesorter">
            <thead>
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
            </thead>
        <?php

            foreach($items as $type => $type_items)
                {
                ?>
                    <tbody ebay-type-container='<?php echo $type; ?>' style='display:none'>
                <?php

                    foreach($type_items as $item) 
                    {                
//		        		xp($item);

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
                <? echo implode("<br>", Log::tail(500)); ?>

                </td>
            </tr>
          </tbody>
       </table>
       <?
       return ob_get_clean();
    }, 300);
?>
</body>

<script>

$(function(){
    $('#items_table thead tr th').click(function() {
        sort_table(this)
    })

    if(localStorage['sort_column'] >=0)
    {
        sort_table($('#items_table thead tr th')[localStorage['sort_column']])
    }

})

function sort_table(th)
{    
    var col = $(th).parent().children().index($(th));

    localStorage['sort_column'] = col

    var tbl = document.getElementById("items_table").tBodies[0];
    var store = [];
    for(var i=0, len=tbl.rows.length; i<len; i++){
        var row = tbl.rows[i];
        var sortnr = parseFloat(row.cells[col].textContent || row.cells[col].innerText);
        if(!isNaN(sortnr)) store.push([sortnr, row]);
    }

    store.sort(function(x,y){
        return x[0] - y[0];
    });
    for(var i=0, len=store.length; i<len; i++){
        tbl.appendChild(store[i][1]);
    }
}

</script>




