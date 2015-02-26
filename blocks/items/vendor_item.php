<tr id='<?php echo $item->ItemID; ?>'>
    <td> <a href="<?php echo $item->ListingDetails->ViewItemURL; ?>" target=_blank> <?php echo $item->ItemID; ?> <br/> (Upd. <?php echo ($db_item? $db_item['ts']: ''); ?> h ago.) </a></td>
    <td> <a href="<?php echo get_url_from_sku($item->SKU); ?>" target=_blank> <?php echo $item->SKU; ?>  </a></td>
    <td> <font color="<? echo empty($db_item['VendorQty'])? 'red' : 'black'?>" >
            <?php echo $item->Title; ?> 
         </font>
    </td>
    <td> <img src='<?php echo $item->PictureDetails->GalleryURL; ?>' /> </td>
    <td> <?php echo $item->BuyItNowPrice; ?> </td>
    <td> <?php echo (empty($db_item['VendorPrice'])?'':calculate_rec_price($db_item['VendorPrice'])); ?> </td>
    <td> <?php echo get_vendor_from_sku($item->SKU); ?> </td>
    <td id='vendor_price_<?php echo $item->SKU;?>'> <?php echo ($db_item? $db_item['VendorPrice']: ''); ?></td>
    <td> <?php echo ($db_item? $db_item['ProfitRatio']: ''); ?> </td>
    <td id='vendor_quantity_<?php echo $item->SKU;?>'> <?php echo ($db_item? $db_item['VendorQty']: ''); ?></td>
    <td align=center> 
        <a target=item_<?php echo $item->ItemID;?> href="/view_item.php?id=<?php echo $item->ItemID;?>">
            <button class="btn btn-primary" type="button">View</button>
        </a>
<!--         <a href="#" onclick="update_item('<?php echo $item->ItemID;?>')">
            <button class="btn btn-info" type="button">Revise</button>
        </a>
-->
        <?php if($type !=='ActiveList') { ?>
        <a href="/update.php?action=relist&id=<?php echo $item->ItemID;?>">
            <button class="btn btn-success" type="button">Relist</button>
        </a>
        <?php } ?>
        <?php if($type ==='ActiveList') { ?>
        <a href="/update.php?action=drop&id=<?php echo $item->ItemID;?>">
            <button class="btn btn-warning" type="button">Drop</button>
        </a>
        <?php } ?>
    </td>
</tr>


