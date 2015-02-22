<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/item.php');
require_once('lib/scrape.php');
require_once('blocks/head.php');
require_once('blocks/menu.php');

if(isset($_POST['revise']))
{
    $item = new Item($_POST['id']);

    $item->revise($_POST);
}

if(isset($_GET['id']))
{
    $item = new Item($_GET['id']);

    $item->load_ebay_data();    
    $item->scrape();
}

?>
    <body>
    

<!--     <div id="ShowResults" style="margin:auto; width:98%;">
        <div style="height:50px;">
            <a href="add_asin.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-info disabled" type="button">Add Asin</button></a>  
            <a href="grab_amazon.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-success" data-loading-text="Loading..." type="button">Fetch Asin Details</button></a>
            <a href="ebay_edit.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-inverse" type="button">Edit Ebay Settings</button></a>
            <a href="send_to_ebay.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-danger" type="button">Add to Ebay</button></a>
            <a href="view_ebay_data.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-warning" type="button">View Ebay Listings</button></a>

        </div>
        <div style="clear:both;"></div>
 -->    
    
<div class="container-fluid span14" style="margin-top: 0px">
    
    <div class="row-fluid">
        
      <section id="global" class="span14">
          
        <div class="row-fluid">
        
        <section id="global" class="span12">
        <?php //xp($item->ebay_data); ?>
        <legend><?php echo $item->ebay_data->Title ?></legend>
            <form id="amazonForm" class="form-horizontal" method="post">
                <fieldset>
                    <input type=hidden name="id" value="<?php echo @$item->item_id; ?>">

                <div class="control-group">
                  <label class="control-label" for="searchField">Title</label>
                  <div class="controls">
                    <input id="title" name="title" placeholder="Title" class="input-large" type="text"
                        value="<?php echo htmlentities( @$item->ebay_data->Title); ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="searchField">SKU</label>
                  <div class="controls">
                    <input id="sku" name="sku" placeholder="SKU" class="input-large" type="text"
                        value="<?php echo htmlentities( @$item->ebay_data->SKU); ?>">
                  </div>
                </div>
                <div class="control-group" style='display:none'>
                  <label class="control-label" for="searchField">Description</label>
                  <div class="controls">
                    <textarea id="desc" name="desc">                    
                    <?php echo htmlentities(''); ?>
                    </textarea>
                    <script> $('#desc').jqte() </script>
                  </div>
                </div>

                <div class="control-group">
                  <label class="control-label" for="searchField">Current ebay quantity</label>
                  <div class="controls">
                    <input id="nbprodamaz" name="ebay_quantity" placeholder="Current number of products" class="input-large" type="text"
                        value="<?php echo htmlentities( @$item->ebay_data->Quantity); ?>">
                  </div>
                </div>
               
                <div class="control-group">
                  <label class="control-label" for="searchField">Current ebay price</label>
                  <div class="controls">
                    <input id="nbprodamaz" name="ebay_quantity" placeholder="Current number of products" class="input-large" type="text"
                        value="<?php echo htmlentities( @$item->ebay_data->SellingStatus->CurrentPrice->value); ?>">
                  </div>
                </div>
               
                <div class="control-group">
                  <label class="control-label" for="searchField">Current vendor quantity</label>
                  <div class="controls">
                    <input id="nbprodamaz" name="vendor_quantity" placeholder="Current number of products" class="input-large" type="text"
                        value="<?php echo htmlentities( @$item->vendor_data['quantity']); ?>">
                  </div>
                </div>
               
                <? 
                $vendor_price = $item->vendor_data['offerprice'];
                $profit_pc = $item->get_profit_ratio();

                require('blocks/price_block.php'); 
                ?>

                <!-- Form Name -->
                

                <!-- Text input-->

                
                <div class="control-group">
                  <label class="control-label" for="searchField">Max quantity</label>
                  <div class="controls">
                    <input id="nbprodamaz" name="max_quantity" placeholder="Maximum number of products" class="input-large" type="text"
                      value="<?php echo empty($item->local_data['max_quantity']) ? 1 : $item->local_data['max_quantity']; ?>">
                  </div>
                </div>
               
      <div class="control-group">
        <div class="controls">
          <button type="submit" id="searchWord" name="revise" class="btn btn-primary">Revise</button>
        </div>
      </div>
   </fieldset>
   </form>

<div>
<pre>
<? echo htmlspecialchars(implode("", $item->tail(100)) ); ?> 
</pre>
</div>

    <div class="spacer"></div>
       

    
</body>
</html>