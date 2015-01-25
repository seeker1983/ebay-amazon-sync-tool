<?php
$session_expiration = time() + 3600* 6; // +6 hours
session_set_cookie_params($session_expiration);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location:index.php");
}

require_once('head.php');
set_time_limit(0);
require ('inc.db.php');
include('ebayFunctions.php');
include "simple_html_dom.php";
$active_user = $_SESSION['user_id'];

$no_added = '<div id="ErrorMsg" class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								Error! No Items Found to Add to Ebay...
								</div>';
$sql = "delete from aws_asin where asin in (SELECT asins FROM ebay_asin WHERE in_ebay=1 AND UserID=$active_user)";
mysql_query($sql) or die(mysql_error());


 function listitems() {
include('keys.php');
  $active_user = $_SESSION['user_id'];
  
    $f_endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1';  // Finding
  $responseEncoding = 'XML';   // Format of the response
  $s_version = '667';   // Shopping API version number
  $f_version = '1.4.0';   // Finding API version number
  $appID   = $APPNAME; //replace this with your AppID

  $debug   = true;
    $sitearray = array(
                                  0=>'EBAY-US',
                                  2=>'EBAY-ENCA',
                                  3=>'EBAY-GB',
                                   15=>'EBAY-AU',
                                  77=>'EBAY-DE',
								 
								  );
				
                   				 
			    $globalID  =$sitearray[$SiteId];
  $sellerID =$ebayusername;  // cleanse input 	   
	   
	   $apicall = "$f_endpoint?OPERATION-NAME=findItemsAdvanced"
       . "&version=$f_version"
       . "&GLOBAL-ID=$globalID"
       . "&SECURITY-APPNAME=$appID"   // replace this with your AppID
       . "&RESPONSE-DATA-FORMAT=$responseEncoding"
       . "&itemFilter(0).name=Seller"
       . "&itemFilter(0).value=$sellerID"
       . "&paginationInput.entriesPerPage=100"
       . "&affliate.networkId=9"        // fill in your information in next 3 lines
       . "&affliate.trackingId=123456789"
       . "&affliate.customId=456";
	   
		 $resp = simplexml_load_file($apicall);
		  if ($resp->ack == "Success") {
		 
		$nbpages=ceil($resp->paginationOutput->totalEntries/100);
		
		 }
		  else {
		 $nbpages=5;
		 }
		 //$sql="DELETE FROM user_products where ItemID>0 and UserID = $active_user";
		// mysql_query($sql);  
	
   //echo $nbpages;die;
  for($page=1;$page<=$nbpages;$page++) {
  $my_sellings_activelist = get_my_ebay_selling_ActiveList($page);
    $xml = simplexml_load_string($my_sellings_activelist);

    $active_data = $xml->ActiveList->ItemArray->Item;
    
	$count_array = count($active_data);

  	 
    foreach ($active_data as $item) {
         
        $item_id = $item->ItemID;
       // echo $item_id.'<br>';
		$item_url = $item->ListingDetails->ViewItemURL;
        $ebay_price = $item->BuyItNowPrice;
        $quantity = $item->QuantityAvailable;
        $title = $item->Title;
        $sku = $item->SKU;
        $image_url = $item->PictureDetails->GalleryURL;
        
        $sql = "SELECT * FROM ebay_asin WHERE item_id = '$item_id' and UserID =$active_user";
        $rs = mysql_query($sql) or die(mysql_error());
          
         if (mysql_num_rows($rs)) {
         $row = mysql_fetch_array($rs);
        
      
        $amazon_price = $row['amazon_price'];
        $amazon_quantity = $row['amazon_quantity'];
        $max_quantity = $row['max_quantity'];
        $profit_ratio = $row['profit_ratio'];
		
          $sql = "SELECT * FROM user_products WHERE ItemID = $item_id"; 
        $rs = mysql_query($sql) or die(mysql_error());
		if (!mysql_num_rows($rs)) {
        $title=str_replace("'","\'",$title);
        $insert = "INSERT INTO user_products 
                   SET  UserID = $active_user, 
                        ItemID = $item_id, 
                        Qty = $quantity, 
                        Price = $ebay_price, 
                        Title = '$title', 
                        SKU = '$sku', 
                        Image = '$image_url', 
                        ItemUrl = '$item_url', 
                        AmazonPrice = $amazon_price,
                        AmazonQty = $amazon_quantity, 
                        MaxQty = $max_quantity,
                        ProfitRatio = $profit_ratio";

        $update = " UPDATE ebay_asin 
                    SET quantity = $quantity
                    WHERE asins='$asin'
                    AND UserID=$active_user
        ";
   

        $delete = "DELETE ast,aws FROM 
                    asins_table as ast INNER JOIN 
                    aws_asin as aws
                    ON ast.asins = aws.asin
                    AND ast.UserID = aws.UserID 
                    WHERE ast.asins='$asin'
                    AND ast.UserID=$active_user
                    ";
	//	$deleteeb="DELETE FROM ebay_asin WHERE asins='$asin' and UserID=$active_user ";			
	    
        mysql_query($insert)or die(mysql_error()); 
        mysql_query($update) or die(mysql_error());
        mysql_query($delete) or die(mysql_error());  
      // mysql_query($deleteeb);  
  }
  }
  
  
	}
  }	
  //die;
      
}
	
	 //listitems();

	
	?>

<body>
    <div class="page-header" style="background-color:#000000;">
        <h2 style="text-align:center; color:#FFFFFF; margin-top:0px; padding-top:20px; font-family:Georgia, 'Times New Roman', Times, serif;">Ebay - Amazon Tool</h2>
        <p style="color:#FFF; margin:auto; text-align:center; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;"><?php
            echo 'Hello ';
            if (isset($_SESSION['username'])) {
                ?>
                <strong><a href="profile.php"><?php echo $_SESSION['username']; ?></a></strong>
                <?php
            } else {
                echo 'Admin!';
            }
            ?> | <a href="logout.php" style="color:#FFFFFF; font-weight:bold;">Logout</a></p>
    </div>
    <div class="navbar">
        <div class="navbar-inner"> <a class="brand" href="dashboard.php">Ebay - Amazon Tool</a>
        </div>
    </div>

    <div id="ShowResults" style="margin:auto; width:98%;">
        <div style="height:50px;">
             <a href="view_ebay_data.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-warning disabled" type="button">View Ebay Listings</button></a>
                 <a href="log.php" target="_blank" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-success" data-loading-text="Loading..." type="button">view log file</button></a>
       
        </div>
        <div style="clear:both;"></div> 
        <?php
        if (isset($_GET['error'])) {
            echo $no_added;
        }
        ?> 


    </div>

    <?php
    
	$select = "SELECT * FROM user_products WHERE  UserID=$active_user ORDER BY ItemID";
    $result = mysql_query($select);
    $count = mysql_num_rows($result); 


    if ($count == 0) {
        $error['no_items'] = '<div id="ErrorMsg" class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								Sorry! No Ebay Listings Found...
					 		  </div>';

        echo $error['no_items'];
        ?>
        <!--
        <table class="table table-bordered">    
             <tr>
                    <th>eBay ID</th>
                    <th>ASIN</th>
                    <th>Ebay Title</th>
                    <th>eBay Quantity</th>
                    <th>Amazon Quantity</th>
                    <th>Max Quantity</th>  
                    <th>eBay Price</th>
                    <th>Amazon Price</th>
                    <th>Ratio</th>
                    <th>Set Max Qty</th>
                    <th>Set Ratio</th>
                </tr>-->
        <p><strong>Products Unavailable</strong></p>

            <?php
        } else {
            ?>
            <h3>Amazon-eBay Listing(<?php echo $count;?>)</h3>
            <table class="table table-bordered">
                <tr>
                    <th style="width:100px;">eBay ID</th>
                    <th style="width:90px;">ItemNumber</th>
                    <th style="width:320px;">Ebay Title</th>
                    <th style="width:65px;">eBay Qty</th>
                    <th style="width:70px;">Vendor Qty</th>
					  
                    <th style="width:65px;">Max Qty</th>  
                    <th style="width:70px;">eBay Price</th>
                    <th style="width:80px;">Vendor Price</th>
					 
                    <th style="width:65px;">Profit</th>
                    <th style="width:120px;">Set Max Qty</th>
                    <th style="width:120px;">Set Profit</th>                    
                </tr>

                <?php
                while ($row_product = mysql_fetch_array($result)) {

                    $ebay_id = $row_product['ItemID'];
                    $ebay_title = $row_product['Title'];
                    $ebay_qty = $row_product['Qty'];
                    $ebay_price = $row_product['Price'];
                    $item_url = $row_product['ItemUrl'];
                    $amazon_price = $row_product['AmazonPrice'];
                    $sku=$row_product['SKU'];
				
                    $pattern = '/^AMZ/';
                    $posamaz=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
                    $pattern = '/^WM/';
					$poswm=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
                    $pattern = '/^OS/';
					$posos=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
					$pattern = '/^AL/';
					$posal=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
                    $pattern = '/^HN/';
                    $poshe=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
                    $pattern = '/^WF/';
                    $poswa=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);

					$profit_ratio = $row_product['ProfitRatio'];
                    $amazon_quantity = $row_product['AmazonQty'];
                    $max_quantity = $row_product['MaxQty'];
                   // $azon_price=$row_product['azonprice'];
                    $sql_asin = "SELECT asins,item_id FROM ebay_asin WHERE item_id = '$ebay_id'  ";
                    $rs_asin = mysql_query($sql_asin);
                    $row_asin = mysql_fetch_array($rs_asin);
                     
                    $asin = $row_asin['asins'];
					/*if(empty($asin)){
					$sql_asin = "DELETE FROM user_products  WHERE ItemID = '$ebay_id'  ";
                     mysql_query($sql_asin);
					
					}*/
                    ?>  

                    <tr>
                        <td><?php echo '<a href="' . $item_url . '" target="_blank">' . $ebay_id . '</a>'; ?></td>
                        <?php if($poswm>0) {?>
						<td><?php
                        $tasin=explode('-',$asin);
						$linkitem=scrap_walmartlink($tasin[0]);
						echo '<a href=http://www.walmart.com/'. $linkitem . ' target="_blank">' . $tasin[0] . '</a>'; ?></td>
                        
					  <?php 
						} 
						 elseif($posos>0) {?>
						<td><?php echo '<a href=http://www.overstock.com/search/' . $asin . ' target="_blank">' . $asin . '</a>'; ?></td>
                        <?php } 
						
						elseif($posamaz>0) {?>
						<td><?php echo '<a href=http://www.amazon.com/dp/' . $asin . ' target="_blank">' . $asin . '</a>'; ?></td>
                         <?php $filename='http://ezon.org/cl/ezonlister/uploads/amazon/'.$asin.'.jpg';
						 
						 
					
						 }
						elseif($posal>0) {?>
						<td><?php echo '<a href=http://www.aliexpress.com/wholesale?SearchText=' . $asin . ' target="_blank">' . $asin . '</a>'?></td>;
		                  <?php $filename='http://ezon.org/cl/ezonlister/uploads/aliexpress/'.$asin.'.jpg';
						  
						 
						}
                         elseif($poshe>0){
                        ?>
						<td><?php echo '<a href=http://search.hayneedle.com/search/index.cfm?Ntt=' . $asin . ' target="_blank">' . $asin . '</a>'?></td>;
		                  <?php 
						  
						}
                       elseif($poswa>0){
					   $url="http://www.wayfair.com/keyword.php?keyword=".$asin;
	                   $data=getPage($url);
	                   $html = str_get_html($data);
                       foreach($html->find('div[id=sbprodgrid] a') as $item) {
                        $url=$item->href;
	                    break;
	                   }
					   
					   ?>
                     <td><?php echo '<a href='.$url.' target="_blank">' . $asin . '</a>'; ?></td>
                        <?php
						}
						
						else {?>
						 <td><?php echo '<a href=http://www.amazon.com/dp/' . $asin . ' target="_blank">' . $asin . '</a>'; ?></td>
                         
						
						<?php }
						?>
						<td><?php echo $ebay_title; ?></td>
                        <td><span id="quantity_<?php echo $ebay_id;?>"><?php echo $ebay_qty; ?></span></td>
						
						
						<td><?php echo $amazon_quantity;?></td>
						
						<?php if($poswm>0) {
						  $asin=$tasin[0];
						}?>
                        
                        <td><span id="maxquantity_<?php echo $ebay_id;?>"><?php echo $max_quantity;?></span></td>
                        <td><span id="ebayprice_<?php echo $ebay_id;?>" ><?php echo $ebay_price; ?></span></td>
                        
						<td><?php echo $amazon_price; ?></td>
						
						<td><span id="profitratio_<?php echo $ebay_id;?>" ><?php echo $profit_ratio;?></td>
                        <td style="width: 120px;">
                            <input type="text" id="maxqtybox_<?php
                           
							echo $active_user."_".$ebay_id."_".$asin;
						
							?>" value="<?php echo $max_quantity;?>" style="width:40px;float: left;"/>
                            <input type="submit" class="btn btn-info" value="Save" onclick="update_max_qty(maxqtybox_<?php echo $active_user."_".$ebay_id."_".$asin;?>)" style="margin-left: 5px;"/>
                        </td>
                        <td style="width: 125px;">
                            <input type="text" id="profitextbox_<?php 
							
							echo $active_user."_".$ebay_id."_".$asin;
							
							?>" value="<?php echo $profit_ratio;?>" style="width:40px;float: left;"/>
                            <input type="submit" id="<?php echo $asin."_".$ebay_id;?>" class="btn btn-info" value="Save" onclick="update_profit_ratio(profitextbox_<?php echo $active_user."_".$ebay_id."_".$asin;?>)" style="margin-left: 10px;"/>
                        </td>
                    </tr>

                    <?php
                }
            }
	function scrap_walmartlink($itemid){
	
	$url="http://www.walmart.com/search/search-ng.do?search_query=".$itemid;
	
	$data=postForm($url);
	$link="";
	//echo $data;die;
	$html=str_get_html($data);
	if($html){
	foreach($html->find('a[class=js-product-title]') as $title)
	{
	
	$link=$title->href;
	
	break;
	}
	}
	return $link;
	}		
	
	function getPage($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 20 );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    $ua = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0";
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);

    $str = curl_exec($ch);
   
    curl_close($ch);
 
    return $str;

}
  function postForm($url)
{
    $ch = curl_init();
    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Pragma: "; // browsers keep this blank.

    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   /* $proxyfile = dirname(__FILE__)."/pro/pro.txt";
        $file = fopen($proxyfile, "r");
        $i = 0;
        while (!feof($file)) {
       $proxies_array[] = fgets($file);
}
      fclose($file); 
      $random_key   = array_rand($proxies_array);
$random_proxy   = $proxies_array[$random_key];
$random_proxy   = trim(str_replace("<br />","",$random_proxy)); 
   $new_proxy = explode(":", $random_proxy); 
   //$random_proxy="192.69.250.210:24084";   
  //$loginpassw = $new_proxy[2].":".$new_proxy[3];
   curl_setopt($ch, CURLOPT_PROXY, $random_proxy);
  */
    curl_setopt($ch, CURLOPT_URL, $url);
     $result = curl_exec($ch);
    $info = curl_getinfo($ch);
        curl_close($ch);
		
		if($info['http_code']!=200){$result=false;}
       // $result = ($info['http_code']!=200) ? false : $result;
		sleep(2);
        return $result;
}
			
            ?>

        </table>
</body>
