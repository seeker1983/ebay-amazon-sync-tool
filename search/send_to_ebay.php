<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location:index.php");
}

$active_user = $_SESSION['user_id'];
//echo 'xxxxxx'.$active_user;
//return;

require_once('head.php');
set_time_limit(0);

include('inc.db.php');
include('ebayFunctions.php');
require_once('profit_calculation.php');
/*
 $remove_request=end_item(121479500758);
           $xml= simplexml_load_string($remove_request);

        //print_r($xml);

        if ($xml->Ack == 'Success' || $xml->Ack == 'Warning') {
         echo 'removed';          
		  }
		
die;
*/
		
//$sql = "SELECT am.*,eb.* FROM aws_asin am, ebay_asin eb WHERE eb.in_ebay = 0 AND am.quantity > 0 AND am.prime = 'Yes' AND eb.asins = am.asin";
//$sql = "SELECT am.*,eb.* FROM aws_asin am, ebay_asin eb WHERE eb.in_ebay = 0 AND am.quantity > 0 AND am.prime = 'Yes' AND eb.asins = am.asin AND eb.UserID = am.UserID AND eb.UserID = $active_user ";
$sql = "SELECT am.*,eb.*,eu.* FROM aws_asin am, ebay_asin eb,ebay_users eu WHERE eb.in_ebay = 0 AND eb.asins = am.asin AND eb.UserID = am.UserID AND eb.UserID=eu.user_id AND eb.UserID = $active_user ";
$rs = mysql_query($sql) or die(mysql_error());
$count = mysql_num_rows($rs);



if ($count == 0) {


    echo '<script>  
					window.location.href = "view_ebay_data.php?error=no_added";
				  </script>';
}// end if count =0
else {

    $sql_ebay_config = "SELECT * FROM ebay_config WHERE user_id=$active_user";
    $rs_config = mysql_query($sql_ebay_config) or die('Something Wrong...!');

    if (mysql_num_rows($rs_config) != 1)
        die('Someting Wrong..!');


    $row_config = mysql_fetch_assoc($rs_config);

    $duration = $row_config['listing_duration'];
    $dispatch_time_max = $row_config['dispatch_time'];
    $skuformat=$row_config['sku'];
	
    $condition_id = $row_config['condition_id'];

    $refund_option = $row_config['refund_option'];

    $shipping_service = $row_config['shipping_service'];
    $shipping_type = $row_config['shipping_type'];
    $shipping_cost = $row_config['shipping_cost'];

    $listing_type = $row_config['listing_type'];

    $return_within = $row_config['return_days'];

    $title_prefix = $row_config['title_prefix'];
    $max_quantity = $row_config['max_quantity'];

    $payment_method = $row_config['payment_method'];
    $paypal_address = $row_config['paypal_address'];


    $price_formula = $row_config['price_formula'];
 
    $profit_percentage = $row_config['profit_percentage'];
    $return_option = trim($row_config['return_accept_option']);


    $template_format = file_get_contents(dirname(__FILE__) . "/templates/template_user_$active_user.txt");


    while ($row = mysql_fetch_array($rs)) {

        $asin = trim($row['asin']);

        if (empty($row['large_image_url']) || $row['large_image_url'] == '' || $row['large_image_url'] == null) {

            $image_url = 'http://nahas.imarasoft.info/IMARASOFT/WIGGLE_ADD_EBAY/images/default.jpg';
        } else {

            $image_url = $row['large_image_url'];
        }
        $weight = $row['weight'];
        $height = $row['height'];
        $width = $row['width'];
        $length = $row['length'];
        $brand = trim($row['brand']);
        //$mpn = $row['mpn'];
        //$ean = $row['ean'];
        $amazon_price = $row['offer_price'];
        if (!empty($row['currency_code']) || $row['currency_code'] != '') {
            $currency_code = $row['currency_code'];
        } else {
            $currency_code = 'USD';
        }

        if (!empty($row['asins']) || trim($row['asins']) != '') {
		    $rs_config = mysql_query("SELECT * FROM asins_table where asins='".$row['asins']."' and UserID=".$active_user."");
			 $row_provider = mysql_fetch_assoc($rs_config);
            $skuprefix=str_replace('$ASIN','',$skuformat);
			if($row_provider['provider']=='Walmart'){
			
			$sku = 'WM'.$row['asins'];
			}
			elseif($row_provider['provider']=='Overstock'){
			$sku = 'OS'.$row['asins'];  
			}
			elseif($row_provider['provider']=='Aliexpress'){
			$sku = 'AL'.$row['asins'];  
			}
			  
			elseif($row_provider['provider']=='Amazon'){
			$sku = 'AMZ'.$row['asins'];
			}
        } else  
            die('Error in asins of aws_asin');
       
        $upc = $row['upc'];
       
        if (!empty($row['amazon_quantity']) or !is_null($row['amazon_quantity']))
            $quantity = $row['amazon_quantity'];
        else
            die("Quantity Null");



        if (trim($price_formula) == "001") {
            $ebay_price = price_basic_profit_percetage($amazon_price, $profit_percentage);
        } elseif (trim($price_formula) == "002") {
            $ebay_price = price_basic_amount_profit($amazon_price, $profit_percentage);
        } elseif (trim($price_formula) == "003") {
            $ebay_price = price_formula_profit_percentage($amazon_price, $profit_percentage);
        } elseif (trim($price_formula) == "004") {
            $ebay_price = price_basic_amount_profit($amazon_price, $profit_percentage);
        } else {
            die('Something Gone Wrong on Profit Calculation');
        }


        $profit_ratio = $profit_percentage;
        $in_amazon = true;
		$prime=$row['prime'];
		$ratio=$row['profit_ratio'];
		
        if ($prime=='Yes'&&$ratio< 2)
        $ebay_price=$amazon_price* (1+ $ratio)/(0.87);
        else if ($prime =='Yes' && $ratio>=2)
        $ebay_price = ($amazon_price+$ratio)/0.87;
	  
        if ($quantity > $max_quantity) {
            $quantity = $max_quantity;
        }
		
            if ($quantity == 0) {
                $in_amazon = false;
                $ebay_price = round(bcmul($ebay_price,10,8),2);
                $quantity = $max_quantity;
            }

   
        $title = transform_title($row['title'], $title_prefix);
        $desc = $row['description'];
        $features = $row['features'];

        $image_1 = $row['large_image_url'];
		$image_2='';
		$image_3='';
        $pictures=explode(',',$row['thumb_img']);
        if($pictures[0]){$image_2=$pictures[0];}
		
		if($pictures[1]){$image_3=$pictures[1];}
		
		 
        $find_terms = array('[Title]', '[Description]', '[Features]',
            '[IMAGE1]', '[IMAGE2]', '[IMAGE3]'
            
        );

        $replace_terms = array($title, $desc, $features,
            $image_1,$image_2, $image_3,
           
        );

        if (sizeof($find_terms) != sizeof($replace_terms))
            die("Unequal String Arrays");

        $ebay_description = trim(str_replace($find_terms, $replace_terms, $template_format));
 

        $uuid = md5(uniqid(rand(), true));
        $site = "US";

        $title = str_replace(array('&', '< and >'), 'n', $title);
        $ebay_description = str_replace(array('&', '< and >'), 'n', $ebay_description);
       
     
        $cat_xml = new SimpleXMLElement(get_suggested_category($title));

		/* ---- Category id added manually please remove it and uncomment below code for getting right working code ------*/
		//$cat_id = 79654;
        if (isset($cat_xml->SuggestedCategoryArray->SuggestedCategory->Category->CategoryID)) {
            $cat_id_array = (array) $cat_xml->SuggestedCategoryArray->SuggestedCategory->Category->CategoryID;
            if (sizeof($cat_id_array)) {
                $cat_id = $cat_id_array[0];
            } else {
                print_r($cat_id_array);
                die();
            }
        } else {
            print_r($cat_xml);
            die();
        }
		//echo $cat_id;die;
        
  
        $add_request = add_fixed_price_item();
        //var_dump($add_request); 
		
        //$xml = new SimpleXMLElement($add_request);
           $xml= simplexml_load_string($add_request);

        //print_r($xml);

        if ($xml->Ack == 'Success' || $xml->Ack == 'Warning') {
          //print_r($xml);die;
            $item_id = $xml->ItemID;
			
            if ($in_amazon) {
                $update = "UPDATE ebay_asin SET 
                              item_id = $item_id,
                               product_active = 1,
                               profit_ratio = $profit_ratio,
                               in_ebay = 1,
                               in_amazon = 1
                        WHERE asins = '$asin' AND 
                        UserID = $active_user ";
            } elseif (!$in_amazon) {
                $update = "UPDATE ebay_asin SET 
                              item_id = $item_id,
                               product_active = 1,
                               profit_ratio = $profit_ratio,
                               in_ebay = 1,
                               in_amazon = 0
                        WHERE asins = '$asin' AND 
                        UserID = $active_user ";
            }

             if (isset($xml->Errors->ShortMessage) and str_replace('', '', $xml->Errors->ShortMessage) == 'Auction ended.')
                    continue;

            if(isset($update))
            $rs_udt = mysql_query($update) or die(mysql_error());

            unset($update);
			//unlink('uploads/'.$asin.'.jpg');
            //unlink($asin.'.jpg');

         //   $insert_repricer_tool = "INSERT INTO user_products SET UserID = $active_user, ItemID = $item_id, Qty = $quantity, Price = $ebay_price, Title = '$title', SKU = '$sku', Image = '$image_url', ItemUrl = '', AmazonPrice = '$amazon_price'  ";						
     
		} else {
		     // echo "<h3>The AddItem called failed due to the following error(s):<br/>";
                foreach ($xml->Errors as $error) {
                    $errCode = $error->ErrorCode;
                    $errLongMsg = htmlentities($error->LongMessage);
                    $errSeverity = $error->SeverityCode;
                    echo $errSeverity . ": [" . $errCode . "] " . $errLongMsg . "<br/>";
                }
                //echo "</h3>";
            //$file_content = file_get_contents("special_error.txt");
            //$file_content .= $add_request;
            //file_put_contents("special_error.txt", $file_content);
        }
    } 
  /*
	echo '<script>  
              window.location.href = "ebay_items.php";
           </script>';
*/
      
}

?>
