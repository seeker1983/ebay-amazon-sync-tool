<?php
require_once('head.php');
require ('inc.db.php');
include('ebayFunctions.php');
include "simple_html_dom.php";
?>

<body>
    <?php
    require_once('blocks/menu.php');   
    
	$select = "SELECT * FROM user_products WHERE  UserID=$active_user ORDER BY sort";
    $result = mysql_query($select);
    $count = mysql_num_rows($result); 


    if ($count == 0) 
    {
        ?>
            <div id="ErrorMsg" class="alert alert-error">
    			<button type="button" class="close" data-dismiss="alert">&times;</button>
    			Sorry! No Ebay Listings Found...
            </div>
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
                    <th style="width:70px;">Current eBay Price</th>
                    <th style="width:80px;">Rec. Price</th>
                    <th style="width:80px;">Vendor Price</th>
                    <th style="width:65px;">Profit %</th>
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

//                    if($ebay_qty< 0 )                   continue;

				
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
                        <td><?php echo '<a href="' . $row_product['VendorUrl'] . '" target="_blank">' . $row_product['SKU'] . '</a>'; ?></td>
<!--                         <?php if($poswm>0) {?>
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
 -->						<td><?php echo $ebay_title; ?></td>
                        <td><span id="quantity_<?php echo $ebay_id;?>"><?php echo $ebay_qty; ?></span></td>
						
						
						<td><?php echo $amazon_quantity;?></td>
						
						<?php if($poswm>0) {
						  $asin=$tasin[0];
						}?>
                        
                        <!-- <td><span id="maxquantity_<?php echo $ebay_id;?>"><?php echo $max_quantity;?></span></td> -->
                        <td><span id="ebayprice_<?php echo $ebay_id;?>" ><?php echo $ebay_price; ?></span></td>
                        
            <td><?php 
                if(strpos($row_product['VendorUrl'], 'http://www.wayfair.com/') === false)
                    echo number_format($amazon_price*(1+$profit_ratio/100)/0.85, 2, '.', ''); 
                else
                    echo '---';
            ?></td>
            <td><?php echo $amazon_price; ?></td>
						
						<td><span id="profitratio_<?php echo $ebay_id;?>" ><?php echo $profit_ratio;?></td>
<!--                         <td style="width: 120px;">
                            <input type="text" id="maxqtybox_<?php
                           
							echo $active_user."_".$ebay_id."_".$asin;
						
							?>" value="<?php echo $max_quantity;?>" style="width:40px;float: left;"/>
                            <input type="submit" class="btn btn-info" value="Save" onclick="update_max_qty(maxqtybox_<?php echo $active_user."_".$ebay_id."_".$asin;?>)" style="margin-left: 5px;"/>
                        </td>
 -->                        <td style="width: 125px;">
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
