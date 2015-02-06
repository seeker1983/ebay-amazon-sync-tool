<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/ebay.php');
require_once('lib/scrape.php');
require_once('blocks/head.php');
require_once('blocks/menu.php');

Log::push('----- Cron job started -----');

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $item = Ebay::get_item($id);
    $sku = $item->SKU;

//    xd(scrap_item($sku));
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
        <fieldset class="form-horizontal">
        <legend>Amazon Fetching Product </legend>

            <form id="amazonForm" class="form-horizontal" action="" method="post">
                <fieldset>

                <!-- Form Name -->
                

                <!-- Text input-->

                
                <div class="control-group">
                  <label class="control-label" for="searchField">Asin Or Amazon Url</label>
                  <div class="controls">
                    <input id="asinID" name="asin" placeholder="enter Asin Code" class="input-large" type="text">
                    <input id="filename" name="upfile" type="hidden" value="">                    
                  </div>
                </div>
                    <div class="control-group">
                  <label class="control-label" for="searchField">Number of products</label>
                  <div class="controls">
                    <input id="nbprodamaz" name="numberprod" placeholder="enter Number of products" class="input-medium" type="text">
                    
                  </div>
                </div>
               
    <div class="control-group">
                  <div class="controls">
                    <button type="submit" id="searchWord" name="searchWord" class="btn btn-primary">upload</button>
                  </div>
                </div>
        </fieldset>
   </form>
    <div class="spacer"></div>
       

    
</body>
</html>