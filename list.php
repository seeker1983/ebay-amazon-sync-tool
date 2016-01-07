<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/scrape.php');
require_once('lib/ebay/item.php');
require_once('lib/image/watermark.php');
require_once('blocks/head.php');
require_once('blocks/menu.php');

if(isset($_GET['data']))
{
    $item = (array)json_decode($_GET['data']);
}
else if(isset($_GET['sku']))
{
    $item = scrap_item($sku);
}
else if(isset($_GET['url']))
{
  $url = $_GET['url'];

  $item = scrap_item_url($url);  
  $item['desc'] = $_GET['desc']; // Amazon w/a
}

$item['img'][] = 'data:image/jpeg;base64,' . base64_encode(file_get_contents('watermark/freeshipping.jpg'));

?>


		<link rel="stylesheet" type="text/css" href="css/jquery-te-1.4.0.css">
		<script type="text/javascript" src="js/jquery-te-1.4.0.min.js"></script>
        <style type="text/css">
	        form input, form textarea
	        {
	            width: 800px !important; 
	        }

	        .jqte *
			{
				-webkit-box-sizing: content-box !important; 
				-moz-box-sizing: content-box !important; 
				box-sizing: content-box !important; 
			}
        </style>
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
        <fieldset class="form-horizontal">
        <legend>Add ebay listing (<a target=_source href="<?php echo $url; ?>">Source</a>) </legend>

            <form id="amazonForm" class="form-horizontal" action="/list_send.php" method="post">
                <fieldset>

                <!-- Form Name -->
                

                <!-- Text input-->

                
                <div class="control-group">
                  <label class="control-label" for="searchField">Title</label>
                  <div class="controls">
                    <input id="title" name="title" placeholder="Title" class="input-large" type="text" maxlength="79"
                    value="<?php echo isset($item['title'])?($item['title']):''; ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="searchField">Keywords</label>
                  <div class="controls">
                    <input id="title" name="keywords" placeholder="Title" class="input-large" type="text" maxlength="79"
                    value="<?php echo isset($item['title'])?($item['title']):''; ?>">
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label" for="searchField">SKU</label>
                  <div class="controls">
                    <input id="sku" name="sku" placeholder="SKU" class="input-large" type="text"
                    value="<?php echo isset($item['sku'])?htmlentities($item['sku']):''; ?>">
                  </div>
                </div>
                <? 
                   //require('blocks/categories_block.php'); 
//                   require('blocks/suggested_categories_block.php'); 
                ?>

                <div class="control-group">
                  <label class="control-label" for="searchField">Description</label>
                  <div class="controls">
                    <textarea id="desc" name="desc">
                        <span style="font-size:20px"><b style="font-size: 20px;">Product Description:</b></span>
                        <br/><br/>
                        <?php echo $item['desc']; ?>
                        <br/><br/>
                        <? if(isset($item['features'])) { ?>
                        <b>Features</b><br>
                        <?php 
                        $features = array_map(function($el){return '<li>' . $el . '</li>';}, $item['features']);
                        echo implode("\n", $features); 
                        ?>
                        <br/><br/>
                        <? } ?>
                        <b>FREE Shipping</b><br><br>Delivery usually takes 5-10 business days. (Most items are delivered within 5 business days) <br>This item may require an extra 1-2 days to process.<br>We ship to the continental 48 U.S. States only<br>No shipping to P.O. Boxes or APO/FPO<br>We do not offer combined shipping services<br>We do not offer local pickup<br>Tracking Number is provided approximately 48 - 72 hours after shipment<br>We will only ship to authorized Paypal addresses<br>Once an item is marked "shipped" if it does not arrive within 7 days, please feel free to contact us<br>There is a $10 cancellation fee for any order placed but not yet processed<br><br><b>Return Policies</b><br><br>We offer a 30 days return. However, all items must be returned in unused or unopened condition and contain all original materials included with the shipment. An RMA number is required for all returns. Message us for return instructions. Items returned without a RMA number will not be processed. Items defective upon receipt must be packaged in their retail packaging as if new and returned with a detailed description of the problem. Return shipping fees are the customers obligation. We reserve the right to decline any returns if the above guidelines are not followed.<br>For all returned items there will be a 20% return stocking fee.<br><br>In The Unlikely Event That Your Item Has Been DISCONTINUED or Is SOLD OUT, You Will Be Refunded 100%.<br><br><b>Your feedback is very important to us on eBay. We will leave a positive feedback for you in return.</b><br><br><br></p><br></div>
                    </textarea>
                    <script> $('#desc').jqte() </script>
                  </div>
                </div>

                <? 
                   $vendor_price = @floatval($item['offerprice']);
                   $profit_pc = 0.15;
                   require('blocks/price_block.php'); 
                ?>

                <? foreach(array_values($item['img']) as $i => $img) { ?>
                <div>
	                  <div class="control-group">
	                    <label class="control-label" for="searchField">Pictures</label>
	                    <div class="controls">
	                      <img src="<?php echo Watermark::add_watermark($img); ?>">
	                      <input id="img_<? echo $i; ?>" name="img[]" type="hidden" value="<?php echo htmlentities(Watermark::add_watermark($img)); ?>">
	                      <a href="#" onclick="$(this).parent().parent().parent().remove(); return false;"> Delete</a>
	                    </div>
	                  </div>
	              </div>
                <? } ?>    
                  <div class="control-group">
                    <label class="control-label" for="searchField">Listing duration</label>
                    <select name="duration">
                      <!-- <option value="Days_1"> Days 1  </option> -->
                      <option value="Days_3"> Days 3  </option>
                      <option value="Days_7"> Days 7  </option>
                      <option value="Days_10"> Days 10  </option>
                      <!-- <option value="Days_120"> Days 120  </option> -->
                      <option value="Days_14"> Days 14  </option>
                      <!-- <option value="Days_21"> Days 21  </option> -->
                      <option value="Days_30" selected> Days 30  </option>
                      <!-- <option value="Days_5"> Days 5  </option> -->
                      <!-- <option value="Days_60"> Days 60  </option> -->
                      <!-- <option value="Days_90"> Days 90  </option> -->
                      <option value="GTC"> Until cancelled </option>
                    </select>
                  </div>       
               
                <div class="control-group">
                  <div class="controls">
                    <button type="submit" name="do" class="btn btn-primary" onclick='return validate();'>Send listing to ebay</button>
                  </div>
                </div>
        </fieldset>
   </form>
    <div class="spacer"></div>

    <script>
    function validate()
    {
      if(! $('#category_id').val() )
      {
          alert('Please choose category!');
          return false;
      }

      return true;
    }
    </script>

    
</body>
</html>