<?php
set_time_limit(0);
$start_time = time("now");
require('inc.db.php');
require_once('functions.php');
require_once('aws_signed_request.php');

$cron_job = true;
require_once('ebayFunctions.php');

$error = array();

//reset the 'product_active' flag
$sql_reset_flag = "UPDATE ebay_asin,user_products SET 
                   ebay_asin.product_active = 0, 
                   user_products.product_active = 0
                  ";

mysql_query($sql_reset_flag) or die(mysql_error());
/*
 * Get all the users from DB, with their amazon & ebay public, private keys & environment settings
 */
$sql_users = "SELECT  eu.user_id,
				              eu.dev_name,
				              eu.app_name,
				              eu.cert_name,
				              eu.token,
				              eu.amazon_publickey,
				              eu.amazon_privatekey,
                      ec.paypal_address,
                      ec.return_accept_option,
                      ec.refund_option,
                      ec.listing_duration,
                      ec.return_days
                FROM  ebay_users eu,
                      ebay_config ec
                WHERE
                      eu.user_id = ec.user_id
                ";

$rs_users = mysql_query($sql_users) or die(mysql_error());

$user_list = array();

while ($row_user = mysql_fetch_assoc($rs_users))
    array_push($user_list, $row_user); // push each user details into user_list 

$user_list = array_reverse($user_list);

$no_synced_products = 0;
$no_failed_products = 0;
while (sizeof($user_list)) {
    /*
     * For each user, using respective(his) public & private keys fetch the ebay,amazon listings.
     */
    $user = array_pop($user_list);

    $user_id = $user['user_id'];

    $DEVNAME = trim($user['dev_name']);
    $APPNAME = trim($user['app_name']);
    $CERTNAME = trim($user['cert_name']);
    $token = encrypt_decrypt('decrypt', $user['token']);


    $paypal_email = trim($user['paypal_address']);
    $return_accept_option = trim($user['return_accept_option']);
    $refund_option = trim($user['refund_option']);
    $return_within_days = trim($user['return_days']);
    $duration = trim($user['listing_duration']);

    $public_key = encrypt_decrypt('decrypt', $user['amazon_publickey']);
    $private_key = encrypt_decrypt('decrypt', $user['amazon_privatekey']);




    $page = 1;
    $is_error = false;

    do {
        $my_selling_pages = get_my_ebay_selling_ActiveList($page); /* fetch ebay active list for the user first time, continue
          if there are many pages DO WHILE
         */
        $xml_page = new SimpleXMLElement($my_selling_pages);
        //print_r($xml_page);


       if(isset($xml_page->Errors->ErrorCode) and (str_replace('','',($xml_page->Errors->ErrorCode))=='931' or str_replace('','',($xml_page->Errors->ErrorCode))=='5'))
       {
          $is_error = true;
          break;
        }elseif(isset($xml_page->Errors)){
          array_push($error, ($xml_page->Errors));
        }


        if (!isset($pages))
            $pages = floatval(str_replace('', '', $xml_page->ActiveList->PaginationResult->TotalNumberOfPages)); // number of pages

        $xml_active_data = $xml_page->ActiveList->ItemArray->Item; // active products list in page $num
        $no_active_products = count($xml_active_data); // number of active products per page.


        for ($num = 0; $num <= ($no_active_products - 1); $num++) {

            //for each active product in ebay, fetch item_id, qty,price & sku

            $ebay_item_id = str_replace('', '', $xml_active_data[$num]->ItemID);
            $ebay_price = floatval(str_replace('', '', $xml_active_data[$num]->BuyItNowPrice));
            $ebay_quantity = floatval(str_replace('', '', $xml_active_data[$num]->QuantityAvailable));
            $ebay_sku = str_replace('', '', $xml_active_data[$num]->SKU);



            $product_db = get_product_details_db($ebay_item_id, $user_id);
            if (!$product_db)
                continue;

            /* scrape the amazon page, for relavant SKU (where SKU = ASIN in Amazon)
             * return values are amazon price & qty */
            $amazon_info = get_amazon_price_qty($ebay_sku);


            if ($ebay_sku != $amazon_info['amazon_asin']) {
                print("Mismatched SKU = ASIN : " . $ebay_sku);
                continue;
            }

            $amazon_price = $amazon_info['amazon_price'];
            $amazon_qty = $amazon_info['amazon_quantity'];

            $max_quantity = $product_db['max_quantity'];
            $profit_ratio = $product_db['profit_ratio'];
            $quantity = $product_db['quantity'];


            if (!$amazon_qty) {
                if ($ebay_quantity == $quantity and $quantity == $max_quantity and $amazon_price == $product_db['amazon_price']) {
                    product_in_active($ebay_item_id, $user_id);
                    continue;
                }
            } elseif ($amazon_qty > 0 and $amazon_qty < $max_quantity) {
                if ($ebay_quantity == $quantity and $amazon_price == $product_db['amazon_price']) {
                    product_in_active($ebay_item_id, $user_id);
                    continue;
                }
            } elseif ($amazon_qty >= $max_quantity) {
                if ($ebay_quantity == $quantity and $ebay_quantity == $max_quantity and $amazon_price == $product_db['amazon_price']) {
                    product_in_active($ebay_item_id, $user_id);
                    continue;
                }
            }

            /*
            * This is the separate border, to understand filtering & the client's requirements.
            * same if -elseif conditions are seperatly written
            */
            /*
             * Customer's Logic Requirements
             */
            $in_amazon = true;
            if (!$amazon_qty) {
                $ebay_revise_price = profit_formula_price($profit_ratio, $amazon_price, 10);
                $ebay_revise_qty = $max_quantity;
                $in_amazon = false;
            } elseif ($amazon_qty > 0 and $amazon_qty < $max_quantity) {
                $ebay_revise_price = profit_formula_price($profit_ratio, $amazon_price);
                $ebay_revise_qty = $amazon_qty;
            } elseif ($amazon_qty >= $max_quantity) {
                $ebay_revise_price = profit_formula_price($profit_ratio, $amazon_price);
                $ebay_revise_qty = $max_quantity;
            }

            if (!isset($ebay_revise_price) or !isset($ebay_revise_qty))
                die("ESCAPED LOGIC :>> ITEM ID : " . $ebay_item_id . " ASIN :" . $ebay_sku . " USER : " . $user_id);


            if (!isset($token) or !isset($DEVNAME) or !isset($APPNAME) or !isset($CERTNAME))
                die("UNSET EBAY ENVIRONMENT VALUES");

            if (!isset($paypal_email) or !isset($return_accept_option) or !isset($refund_option) or !isset($return_within_days) or !isset($duration))
                die("UNSET PRICE REVISE VARIABLES");


            $result_xml_page = ebay_revise_item($ebay_item_id, $ebay_revise_price, $ebay_revise_qty);

            $result_xml_elements = new SimpleXMLElement($result_xml_page);



            if (($result_xml_elements->Ack) == "Success" or ($result_xml_elements->Ack) == "Warning") {

                $no_synced_products++;
                if ($in_amazon) {
                    $sql_revise = "UPDATE ebay_asin eb, 
                                      user_products ups
                      SET 
                      eb.ebay_price = $ebay_revise_price,
                      eb.quantity = $ebay_revise_qty,
                      eb.amazon_price = $amazon_price,
                      eb.amazon_quantity = $amazon_qty,
                      eb.product_active = 1,
                      eb.in_amazon = 1,
                      ups.Price = $ebay_revise_price,
                      ups.Qty = $ebay_revise_qty,
                      ups.AmazonPrice = $amazon_price,
                      ups.AmazonQty = $amazon_qty,
                      ups.product_active = 1
                      WHERE
                      eb.item_id = ups.ItemID AND
                      eb.asins = ups.SKU AND
                      eb.UserID = ups.UserID AND
                      eb.in_ebay = 1 AND 
                      eb.item_id = '$ebay_item_id' AND 
                      eb.UserID = $user_id 
                      ";
                } elseif (!$in_amazon) {
                    $sql_revise = "UPDATE ebay_asin eb, 
                                      user_products ups
                      SET 
                      eb.ebay_price = $ebay_revise_price,
                      eb.quantity = $ebay_revise_qty,
                      eb.amazon_price = $amazon_price,
                      eb.amazon_quantity = $amazon_qty,
                      eb.product_active = 1,
                      eb.in_amazon = 0,
                      ups.Price = $ebay_revise_price,
                      ups.Qty = $ebay_revise_qty,
                      ups.AmazonPrice = $amazon_price,
                      ups.AmazonQty = $amazon_qty,
                      ups.product_active = 1
                      WHERE
                      eb.item_id = ups.ItemID AND
                      eb.asins = ups.SKU AND
                      eb.UserID = ups.UserID AND
                      eb.in_ebay = 1 AND 
                      eb.item_id = '$ebay_item_id' AND 
                      eb.UserID = $user_id 
                      ";
                }
            } elseif (($result_xml_elements->Ack) == "Failure") {
                $no_failed_products++;
                if ($in_amazon) {
                    $sql_revise = "UPDATE ebay_asin eb,
                                            user_products ups
                                   SET
                                     eb.product_active = 1,
                                     eb.in_amazon = 1,
                                     ups.product_active = 1,
                                     WHERE
                                     eb.item_id = ups.ItemID AND
                                     eb.asins = ups.SKU AND
                                     eb.UserID = ups.UserID AND
                                     eb.in_ebay = 1 AND 
                                     eb.item_id = '$item_id' AND
                                     eb.UserID = $user_id
                      ";
                } elseif (!$in_amazon) {
                    $sql_revise = "UPDATE ebay_asin eb,
                                            user_products ups
                                   SET
                                     eb.product_active = 1,
                                     eb.in_amazon = 0,
                                     ups.product_active = 1,
                                     WHERE
                                     eb.item_id = ups.ItemID AND
                                     eb.asins = ups.SKU AND
                                     eb.UserID = ups.UserID AND
                                     eb.in_ebay = 1 AND 
                                     eb.item_id = '$item_id' AND
                                     eb.UserID = $user_id
                      ";
                }
            }

            /*
            if (isset($result_xml_elements->Errors))
                print_r($result_xml_elements->Errors);
            */
            if (isset($sql_revise)) {
                mysql_query($sql_revise) or die(mysql_error());
                unset($sql_revise);
            }

            unset($amazon_info);
            unset($product_db);
            unset($result_xml_page);
            unset($result_xml_elements);
        }
        $page++;


        unset($my_selling_pages);
        unset($xml_page);
        unset($xml_active_data);
    } while ($page <= $pages);

    $sql_ending = "DELETE eb,ups FROM 
                      ebay_asin as eb INNER JOIN 
                      user_products as ups 
                      ON 
                      eb.item_id = ups.ItemID AND
                      eb.product_active = ups.product_active AND
                      eb.UserID = ups.UserID AND
                      eb.in_ebay = 1 AND
                      eb.product_active = 0 AND
                      eb.UserID = $user_id
                      ";
    if(!is_error)
    mysql_query($sql_ending) or die($sql_ending);


    unset($sql_ending);
    unset($is_error);
    unset($pages);
    unset($user_id);
    unset($DEVNAME);
    unset($APPNAME);
    unset($CERTNAME);
    unset($token);
}

$end_time = time("now");
$elasped_time = ($end_time - $start_time);

$hours = ($elasped_time - $elasped_time%3600)/3600;
$mintues = ($elasped_time%3600 - $elasped_time%60)/60;
$seconds = $elasped_time%60;

if (!sizeof($user_list)){
    print_r("Sucess...!!! $hours Hours $mintues Mintues $seconds Seconds <br>");
    print_r("No of Synchronized Products : $no_synced_products <br>");
    print_r("No of Failed Products : $no_failed_products <br>");
}
elseif(sizeof($user_list))
    die("Please Report to Developer");

//print_r($error);

//************************************************************************//

function get_amazon_price_qty($asin) {

    $amazon_url = "http://www.amazon.com/dp/$asin";

    $curl_handler = curl_init();
    curl_setopt($curl_handler, CURLOPT_URL, $amazon_url);
    curl_setopt($curl_handler, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl_handler, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handler, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.152 Safari/537.22');

    $web_page = curl_exec($curl_handler);

    $regex_price = '/<span id="priceblock_ourprice" class="a-size-medium a-color-price">([^`]*?)<\/span>/';
    if (preg_match($regex_price, $web_page, $price_matches) and sizeof($price_matches) == 2)
        $offer_price = floatval(str_replace('$', '', $price_matches[1]));

    unset($price_matches);

    $regex_price = '/<span id="priceblock_saleprice" class="a-size-medium a-color-price">([^`]*?)<\/span>/';
    if (preg_match($regex_price, $web_page, $price_matches) and sizeof($price_matches) == 2)
        $offer_price = floatval(str_replace('$', '', $price_matches[1]));



    $regex_qty = '/<select name="quantity" autocomplete="[offn]*" id="quantity" class="a-native-dropdown">([^`]*?)<\/select>/';

    if (preg_match($regex_qty, $web_page, $qty_matches) and sizeof($qty_matches) == 2) {
        $options = $qty_matches[1];
        $regex_option = '/<option value="[0-9]{1,3}">[0-9]{1,3}<\/option>/';

        if (preg_match_all($regex_option, $options, $option_matches) and sizeof($option_matches))
            $quantity = 1 + sizeof($option_matches[0]);
    }

    if (!isset($quantity)) {
        $regex_qty = '/<span class="a-size-medium a-color-success">([^`]*?)<\/span>/';

        if (preg_match($regex_qty, $web_page, $qty_matches) and sizeof($qty_matches) == 2) {
            if ($str_num = trim(str_replace(array('LEFT', 'ONLY', 'IN', 'STOCK', '.'), '', strtoupper($qty_matches[1]))) and is_numeric($str_num))
                $quantity = floatval($str_num);
        }
    }


    if (!isset($quantity)) {
        $regex_qty = '/<span class="a-size-medium a-color-error">([^`]*?)<\/span>/';

        if (preg_match($regex_qty, $web_page, $qty_matches) and sizeof($qty_matches))
            $quantity = 0;
    }


    if (!isset($offer_price) or !isset($quantity)){
        //die("UNSET VALUES FOR ASIN : " . $asin);
        $offer_price = 1000;
        $quantity = 100;
        print_r($asin);
        echo "<br>";
    }

    unset($web_page);
    unset($amazon_url);
    unset($qty_matches);
    unset($price_matches);

    return array("amazon_asin" => $asin,
        "amazon_price" => $offer_price,
        "amazon_quantity" => $quantity
    );
}

function get_product_details_db($item_id, $user_id) {

    $sql_prod_details = "SELECT eb.max_quantity, 
                              eb.quantity,
                              eb.ebay_price,
                              eb.profit_ratio,
                              eb.amazon_price,
                              ec.max_quantity 
                        FROM  ebay_asin eb, 
                              ebay_config ec 
                        WHERE 
                              eb.UserID = ec.user_id AND 
                              eb.item_id = '$item_id' AND
                              eb.UserID = $user_id
                      ";

    $rs_prod_details = mysql_query($sql_prod_details) or die(mysql_error());

    $no_records = mysql_num_rows($rs_prod_details);

    //echo $sql_prod_details;
    // echo "<br>";

    if ($no_records > 1)
        die("DUPLICATE RECORDS");
    elseif (!$no_records)
        return false;
    elseif ($no_records == 1) {
        $product = mysql_fetch_array($rs_prod_details);

        if ($product[0] == end($product) or $product[0] == 0)
            $max_quantity = end($product);
        elseif ($product[0] != end($product))
            $max_quantity = $product[0];

        $quantity = floatval($product['quantity']);
        $ebay_price = floatval($product['ebay_price']);
        $profit_ratio = floatval($product['profit_ratio']);
        $amazon_price = floatval($product['amazon_price']);

        return array("max_quantity" => $max_quantity,
            "ebay_price" => $ebay_price,
            "quantity" => $quantity,
            "profit_ratio" => $profit_ratio,
            "amazon_price" => $amazon_price
        );
    }
}

function calculate_profit_ratio($ebay_price, $amazon_price) {
    return round(bcsub(bcdiv($ebay_price, $amazon_price, 8), 1, 8), 2);
}

function profit_formula_price($ratio, $price, $scale = 1) {
    return round(bcmul(bcmul(bcadd($ratio, 1, 8), $price, 8), $scale, 8), 2);
}

function compare_prices_tolerance($new_price, $old_price, $tolerance = 0.05) {
    if ($new_price > $old_price)
        $diff = bcsub($new_price, $old_price, 2);
    else
        $diff = bcsub($old_price, $new_price, 2);

    if ($tolerance >= $diff)
        return true;
    else
        return false;
}

function ebay_revise_item($item_id, $revise_price, $revise_qty) {

    global $token, $paypal_email, $return_accept_option, $refund_option, $return_within_days, $duration;

    $post_data = '<?xml version="1.0" encoding="utf-8"?>
  <ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <ErrorLanguage>en_US</ErrorLanguage>
  <Item>
    <ItemID>' . $item_id . '</ItemID>
    <StartPrice>' . $revise_price . '</StartPrice>
    <Quantity>' . $revise_qty . '</Quantity>
        <PayPalEmailAddress>' . $paypal_email . '</PayPalEmailAddress>
        <ListingDuration>' . $duration . '</ListingDuration>
	<ReturnPolicy>
            <ReturnsAcceptedOption>' . $return_accept_option . '</ReturnsAcceptedOption>
            <RefundOption>' . $refund_option . '</RefundOption>
            <ReturnsWithinOption>' . $return_within_days . '</ReturnsWithinOption>
            <ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
	</ReturnPolicy>
  </Item>
  <RequesterCredentials>
  <eBayAuthToken>' . $token . '</eBayAuthToken>
  </RequesterCredentials>
  <WarningLevel>High</WarningLevel>
  </ReviseItemRequest>';

    $body = callapi($post_data, 'ReviseItem');
    return $body;
}

function product_in_active($item_id, $user_id) {
    $sql_in_active = "UPDATE ebay_asin eb,
                                     user_products ups
                                     SET
                                     eb.product_active = 1,
                                     ups.product_active = 1
                                     WHERE
                                     eb.item_id = ups.ItemID AND
                                     eb.asins = ups.SKU AND
                                     eb.UserID = ups.UserID AND
                                     eb.in_ebay = 1 AND 
                                     eb.item_id = '$item_id' AND
                                     eb.UserID = $user_id
                      ";

    mysql_query($sql_in_active) or die(mysql_error());
}

?>
