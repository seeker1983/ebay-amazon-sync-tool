<?php

class XML_to_Database_Updater {

    private $no_inserted_asins;

    public function insert_into__aws_asin($product_xml) {
        //
        //print_r($product_xml);

        require_once 'redirect.php';
        require_once 'profit_calculation.php';

        $product_counter = 0;
        $active_user = $_SESSION['user_id'];

        $sql_ebay_config = "SELECT 
                            max_quantity,
                            price_formula,
                            profit_percentage
         FROM   ebay_config 
         WHERE 
                user_id=$active_user";
        $rs_config = mysql_query($sql_ebay_config) or die('Something Wrong...!');

        if (mysql_num_rows($rs_config) != 1)
            die('Someting Wrong..!');


        $row_config = mysql_fetch_assoc($rs_config);
        $max_quantity = $row_config['max_quantity'];
        $price_formula = $row_config['price_formula'];
        $profit_percentage = $row_config['profit_percentage'];

// Removing Error Code Returned asins 
        if (isset($product_xml->Items->Request->Errors->Error->Code)) {
            $error_code = (array) $product_xml->Items->Request->Errors->Error->Code;

            $error_itemIDs = $product_xml->Items->Request->ItemLookupRequest->ItemId;

            $error_asins_string = '';
            foreach ($error_itemIDs as $error_itemID) {
                $error_asin_array = (array) $error_itemID;
                $error_asin = trim($error_asin_array[0]);
                $sql_asins_table = "DELETE FROM asins_table WHERE asins='$error_asin'";
                mysql_query($sql_asins_table) or die(mysql_error());

                if (trim($error_asins_string) == '')
                    $error_asins_string = $error_asin;
                else
                    $error_asins_string .= "," . $error_asin;
            }
            $error_code_file = "./logs/error_code.txt";
            $file_content = file_get_contents($error_code_file);
            //
            if (strlen(trim($file_content)))
                $file_content .= "\n" . trim($error_code[0]) . " : " . $error_asins_string;
            else
                $file_content = trim($error_code[0]) . " : " . $error_asins_string;
            //
            file_put_contents($error_code_file, $file_content);
            return;
        }

//************ Getting the Requested ASINs into array **********//
        if (isset($product_xml->Items->Request->ItemLookupRequest->ItemId)) {
            $itemIDs = $product_xml->Items->Request->ItemLookupRequest->ItemId;

            $request_asins = array('LIST OF ASINS');

            foreach ($itemIDs as $itemID) {
                $single_asin = (array) $itemID;
                array_push($request_asins, trim($single_asin[0]));
            }
        }

        // ****************** Product insertion options ******//
        if (isset($product_xml->Items->Item)) {
            while ($product_counter < count($product_xml->Items->Item)) {
                $item = $product_xml->Items->Item[$product_counter];

                //requirments
                $asin = $item->ASIN;

                $duplicate_product_sql = "SELECT asin FROM aws_asin WHERE asin='$asin' AND UserID=$active_user";
                $dp_result = mysql_query($duplicate_product_sql) or die(mysql_error());
                if (mysql_num_rows($dp_result))
                    continue;


// **************** Remove Kindle Edition ASIN ****************************//
                if (isset($item->ItemAttributes->Binding)) {
                    $temp_binding = (array) $item->ItemAttributes->Binding;
                    $binding = " " . strtoupper(trim($temp_binding[0]));

                    $search_terms = array('KINDLE', 'EDITION');
                    $search_count = 0;


                    foreach ($search_terms as $search_term) {

                        if (strpos($binding, $search_term) != false)
                            $search_count++;
                    }

                    if ($search_count == sizeof($search_terms)) {
                        $sql_asin_table_remove = "DELETE FROM asins_table WHERE asins='$asin' AND UserID=$active_user";
                        mysql_query($sql_asin_table_remove) or die(mysql_error());
                        $product_counter++;
                        continue;
                    }
                }
                // *******************************************************************//               
                if (isset($item->ItemAttributes->Title)) {
                    $title = $item->ItemAttributes->Title;
                    $title = str_replace("'", "`", $title);
                } else {
                    $title = '';
                }

                $title = str_replace('&', 'and', $title);

                if (isset($item->EditorialReviews->EditorialReview->Content)) {
                    $description = $item->EditorialReviews->EditorialReview->Content;
                    $description = str_replace(array("'", '"'), " ", $description);

                    $description_url = '<a href="description_text.php?desc_asin=' . $asin . '&user_id=' . $active_user . '" target="_blank"> click here </a>';
                } else {
                    $description = '';
                    $description_url = '';
                }
                if (isset($description))
                    $description = str_replace('&', 'and', $description);

                if (isset($item->ImageSets->ImageSet[0]->LargeImage->URL)) {
                    $large_img_url = $item->ImageSets->ImageSet[0]->LargeImage->URL;
                } else {
                    $large_img_url = '';
                }

                if (isset($item->ImageSets->ImageSet[1]->LargeImage->URL)) {
                    $medium_img_url = $item->ImageSets->ImageSet[1]->LargeImage->URL;
                } else {
                    $medium_img_url = '';
                }

                if (isset($item->ImageSets->ImageSet[2]->LargeImage->URL)) {
                    $small_img_url = $item->ImageSets->ImageSet[2]->LargeImage->URL;
                } else {
                    $small_img_url = '';
                }

                if (isset($item->ImageSets->ImageSet[3]->LargeImage->URL)) {
                    $thumb_img_url = $item->ImageSets->ImageSet[3]->LargeImage->URL;
                } else {
                    $thumb_img_url = '';
                }

                if (isset($item->ImageSets->ImageSet[4]->LargeImage->URL)) {
                    $tiny_imag_url = $item->ImageSets->ImageSet[4]->LargeImage->URL;
                } else {
                    $tiny_imag_url = '';
                }

                if (isset($item->ImageSets->ImageSet[5]->LargeImage->URL)) {
                    $swatch_img_url = $item->ImageSets->ImageSet[5]->LargeImage->URL;
                } else {
                    $swatch_img_url = '';
                }


                if (isset($item->ItemAttributes->ItemDimensions->Weight)) {
                    $weight = $item->ItemAttributes->ItemDimensions->Weight;
                } elseif (isset($item->ItemAttributes->PackageDimensions->Weight)) {
                    $weight = $item->ItemAttributes->PackageDimensions->Weight;
                } else {
                    $weight = 0;
                }

                if (isset($item->ItemAttributes->ItemDimensions->Height)) {
                    $height = $item->ItemAttributes->ItemDimensions->Height;
                } elseif (isset($item->ItemAttributes->PackageDimensions->Height)) {
                    $height = $item->ItemAttributes->PackageDimensions->Height;
                } else {
                    $height = 0;
                }

                if (isset($item->ItemAttributes->ItemDimensions->Width)) {
                    $width = $item->ItemAttributes->ItemDimensions->Width;
                } elseif (isset($item->ItemAttributes->PackageDimensions->Width)) {
                    $width = $item->ItemAttributes->PackageDimensions->Width;
                } else {
                    $width = 0;
                }

                if (isset($item->ItemAttributes->ItemDimensions->Length)) {
                    $length = $item->ItemAttributes->ItemDimensions->Length;
                } elseif (isset($item->ItemAttributes->PackageDimensions->Length)) {
                    $length = $item->ItemAttributes->PackageDimensions->Length;
                } else {
                    $length = 0;
                }

                if (isset($item->ItemAttributes->Brand)) {
                    $brand = $item->ItemAttributes->Brand;
                    $brand = str_replace("'", "`", $brand);
                } else {
                    $brand = '';
                }

                if (isset($item->ItemAttributes->ListPrice->FormattedPrice)) {
                    $list_price = $item->ItemAttributes->ListPrice->FormattedPrice;
                    $list_price = str_replace(array('$', ','), '', $list_price);
                } else {
                    $list_price = '';
                }

                if (isset($item->ItemAttributes->PackageQuantity)) {
                    $quantity = $item->ItemAttributes->PackageQuantity;
                } elseif (isset($item->ItemAttributes->NumberOfItems)) {
                    $quantity_array = (array) $item->ItemAttributes->NumberOfItems;
                    if (sizeof($quantity_array))
                        $quantity = str_replace('', '', $quantity_array[0]);
                    else
                        $quantity = 0; //dump code , if something goes wrong
                }
                // elseif(Offers){
                //}
                else {
                    $quantity = 0;
                }


                $features = '';
                if (isset($item->ItemAttributes->Feature)) {
                    $count_fe = count($item->ItemAttributes->Feature);
                    for ($x = 0; $x <= $count_fe - 1; $x++) {
                        $features_all = str_replace(array("'", '"'), "", $item->ItemAttributes->Feature[$x]);
                        $features .= "<li>" . $features_all . "</li>";
                    }
                }

                $weight_string = $this->num_to_pounds($weight);

                $dimensions = $this->num_to_inch($length) . " x " . $this->num_to_inch($width) . " x " . $this->num_to_inch($height);
                $dimensions = mysql_real_escape_string($dimensions);

                $size = $item->ItemAttributes->Size;
                $size = str_replace(array("'", '"'), "", $size);

                $mpn = $item->ItemAttributes->MPN;
                $currency_code = $item->ItemAttributes->ListPrice->CurrencyCode;
                $sku = $item->ItemAttributes->SKU;
                $upc = $item->ItemAttributes->UPC;
                $ean = $item->ItemAttributes->EAN;
                if (isset($item->OfferSummary->LowestNewPrice->FormattedPrice)) {
                    $price_array = $item->OfferSummary->LowestNewPrice->FormattedPrice;
                    if (sizeof($price_array))
                        $offer_price = str_replace(array('$', ''), '', $price_array[0]);
                }elseif (isset($item->Offers->Offer->OfferListing->Price->FormattedPrice)) {
                    $price_array = $item->Offers->Offer->OfferListing->Price->FormattedPrice;
                    if (sizeof($price_array))
                        $offer_price = str_replace(array('$', ''), '', $price_array[0]);
                }


                if (!isset($offer_price) or strlen(trim($offer_price)) >= 10)
                    $offer_price = $list_price;
//                
//**************** Processing Keywords html ********************//                
                $item_url = "http://www.amazon.com/s/keywords=" . $asin;


                $html = $this->get_data($item_url);
                $match = array();

                if (preg_match('/<span class="srSprite sprPrime">/', $html, $match)) {
                    $prime = "Yes";
                } else {
                    $prime = "No";
                }

// ************* Processing ASIN html ************************//                

                $asin_specific_url = "http://www.amazon.com/dp/" . $asin;
                $asin_html = $this->get_data($asin_specific_url);

                $shippping_cost = "";



                if ($quantity == 0 and preg_match('/<span class="a-size-medium a-color-success">([^`]*?) <\/span>/', $asin_html))
                    $quantity = 1;

                //<span class="a-size-medium a-color-error">Out of stock </span>
                if (preg_match('/<span class="a-size-medium a-color-error">([^`]*?) <\/span>/', $asin_html, $match)) {

                    foreach ($match as $matched_string) {
                        $matched_string = strtoupper($matched_string);

                        $search_terms = array('OUT', 'OF', 'STOCK');
                        $search_count = 0;
                        //
                        foreach ($search_terms as $search_term) {
                            if (strpos($matched_string, $search_term) != false)
                                $search_count++;
                        }

                        if ($search_count == 3) {
                            $quantity = 0;
                            break;
                        }
                    }
                }

                if ($prime == "No") {

                    if (preg_match('/<span class="a-size-small a-color-secondary shipping3P">([^`]*?)<\/span>/', $asin_html, $match)) {
                        foreach ($match as $term) {
                            $number = floatval(trim(str_replace(array('+', '$', 'shipping'), '', $term)));

                            if (is_double($number) && $number > 0) {
                                $shippping_cost = $number;
                                break;
                            }
                        }
                    }
                }

                $regex_price = '/<span id="priceblock_ourprice" class="a-size-medium a-color-price">([^`]*?)<\/span>/';
                preg_match($regex_price, $asin_html, $price_matches);
                if (sizeof($price_matches) == 2) {
                    $offer_price = str_replace('$', '', $price_matches[1]);
                }

                $regex_qty = '/<select name="quantity" autocomplete="[offn]*" id="quantity" class="a-native-dropdown">([^`]*?)<\/select>/';
                preg_match($regex_qty, $asin_html, $qty_matches);
                if (sizeof($qty_matches) == 2) {
                    $options = $qty_matches[1];
                    $regex = '/<option value="[0-9]{1,3}">[0-9]{1,3}<\/option>/';
                    preg_match_all($regex, $options, $option_matches);
                    if (sizeof($option_matches))
                        $quantity = 1 + sizeof($option_matches[0]);
                }

                $amazon_quantity = $quantity;
                


                /// DATABASE INSERTION

                $aws_asin_insert_sql = "INSERT INTO aws_asin(UserId,asin,title,description_url,description,
                                                    features,large_image_url,medium_image_url,
                                                    small_image_url,tiny_image_url,swatch_image_url,
                                                    weight,length,width,height,
                                                    weight_string,dimensions,brand,ean,list_price,
                                                    prime,offer_price,shipping_cost,
                                                    currency_code,size,sku,upc,quantity,mpn,thumb_img,processed) 
                                                    
                                VALUES 
                                                    ($active_user,'$asin','$title','$description_url','$description',
                                                     '$features','$large_img_url','$medium_img_url',
                                                     '$small_img_url','$tiny_imag_url','$swatch_img_url',
                                                      $weight,$length,$width,$height,
                                                     '$weight_string','$dimensions','$brand','$ean','$list_price',
                                                     '$prime','$offer_price','$shippping_cost',
                                                     '$currency_code','$size','$sku','$upc',$quantity,'$mpn','$thumb_img_url',2)";




                $asins_table_update_sql = "UPDATE asins_table SET processed=1 WHERE asins='$asin' AND UserID = $active_user";

                (mysql_query($aws_asin_insert_sql) and mysql_query($asins_table_update_sql)) or die(mysql_error());

                if (($key = array_search($asin, $request_asins)) != false)
                    unset($request_asins[$key]);



                $sql_success = "SELECT asin FROM aws_asin WHERE asin='$asin' AND UserID='$active_user'";
                $rs_sucess = mysql_query($sql_success) or die(mysql_error());
                //
                if (mysql_num_rows($rs_sucess)) {
                    $offer_price = str_replace(array('$' . ','), '', $offer_price);
                    $offer_price = floatval($offer_price);

                    $ebay_select_sql = "SELECT asins FROM ebay_asin WHERE asins='$asin' AND UserID='$active_user'";
                    $ebay_asin_result = mysql_query($ebay_select_sql) or die(mysql_error());

                    if (!mysql_num_rows($ebay_asin_result)) {
                        //
                        if ($offer_price == 'Not Available' or !isset($offer_price)) {
                            $offer_price = $list_price;
                        }
                        //

                        $temp_title = str_replace(',', ' ', $title);

                        $title_words = explode(' ', $temp_title);
                        $new_title = trim("New ");

                        foreach ($title_words as $title_word) {
                            $title_word = trim($title_word);

                            if ($title_word == '' || empty($title_word))
                                continue;
                            if ((strlen($new_title) + strlen($title_word)) >= 80)
                                break;

                            if (trim($new_title) != '') {
                                $new_title .= " " . $title_word;
                            }
                        }
                        // New Title got...
                        //
                        if (trim($price_formula) == "001") {
                            $ebay_price = price_basic_profit_percetage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "002") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "003") {
                            $ebay_price = price_formula_profit_percentage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "004") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } else {
                            die('Something Gone Wrong on Profit Calculation');
                        }


                        $profit_ratio = round(bcsub(bcdiv($ebay_price, $offer_price, 8), 1, 8),2);


                        //$temp_price = bcadd(bcmul($offer_price, 100), bcmul($offer_price, 25));
                        //$ebay_price = bcadd(bcmul($temp_price, 0.01, 4), 0.01, 2);
                        //
                        $template_file = dirname(__FILE__) . "/templates/template_user_" . $active_user . ".txt";
                        if (file_exists($template_file)) {
                            $file_content = " " . file_get_contents($template_file) . " ";
                            $find_terms = array('[TITLE]', '[AmazonDescription]', '[AmazonFeatures]',
                                '[IMAGE1]', '[IMAGE2]', '[IMAGE3]',
                                '[IMAGE4]', '[IMAGE5]', '[IMAGE6]'
                            );

                            $replace_terms = array($new_title, $description, $features,
                                $large_img_url, $medium_img_url, $small_img_url,
                                $thumb_img_url, $tiny_imag_url, $swatch_img_url
                            );

                            if (sizeof($find_terms) != sizeof($replace_terms))
                                die("Unequal String Arrays");

                            $ebay_description = trim(str_replace($find_terms, $replace_terms, $file_content));
                        }
                        else {
                            $ebay_description = '<p> <b> <u> Description </u> : </b> </p>
									 <p style="color: #033;">' . $description . '</p>
									 
									 <hr>
									
									 <p> <b> <u> Features </u> : </b> </p>
									 <p style="color:#CC6600;">' . $features . '</p>
											
									 <hr>';
                        }

                        //
                        $insert_ebay_asin_sql = "INSERT INTO ebay_asin(
                                                 UserID,asins,ebay_title,prefix,ebay_description,
                                                 ebay_description_url,ebay_price,amazon_price,shipping_charge,
                                                 handling_time,shipping_option,return_option,profit_percent,
                                                 profit_ratio,amazon_quantity,max_quantity
                                                 ) 
                            
                                                VALUES (
                                                $active_user,'$asin','$new_title','New ','$ebay_description',
                                                 '$description_url',$ebay_price, $offer_price ,'$shippping_cost',
                                                 1,'free','ReturnsAccepted',$profit_percentage,
                                                 $profit_ratio,$amazon_quantity,$max_quantity
                        )";
                        mysql_query($insert_ebay_asin_sql) or die(mysql_error());
                    }

                    $this->no_inserted_asins++;
                }

                $product_counter++;
            }
        }

        $invalid_asins_string = '';
        foreach ($request_asins as $request_asin) {
            $request_asin = trim($request_asin);
            unset($sql_asin_table_remove);
            $sql_asin_table_remove = "DELETE FROM asins_table WHERE asins='$request_asin'";
            mysql_query($sql_asin_table_remove) or die(mysql_error());

            $sql = "SELECT asins FROM asins_table WHERE asins='$request_asin'";
            $result = mysql_query($sql) or die(mysql_error());
            if (mysql_num_rows($result) != 0)
                die($request_asin);
            if (strlen(trim($invalid_asins_string)) == 0)
                $invalid_asins_string = $request_asin;
            else
                $invalid_asins_string .= "," . $request_asin;
        }

        unset($file_content);
        $invalid_asins_file = "./logs/invalid_asins.txt";
        $file_content = file_get_contents($invalid_asins_file);
        $file_content .= "\n" . $invalid_asins_string;
        file_put_contents($invalid_asins_file, $file_content);
    }

    /* -------------------------------------------------------------------------

     * 
     * 
     * 
     * 
     * 
     * ------------------------------------------------------------------------
     */

	 
	 function insert_into__scrape_asin($data)
	 {
	     require_once 'redirect.php';
        require_once 'profit_calculation.php';
		
	  $active_user = $_SESSION['user_id'];
	  
	  $sql_ebay_config = "SELECT 
                            max_quantity,
                            price_formula,
                            profit_percentage
         FROM   ebay_config 
         WHERE 
                user_id=$active_user";
        $rs_config = mysql_query($sql_ebay_config) or die('Something Wrong...!');

        if (mysql_num_rows($rs_config) != 1)
            die('Someting Wrong..!');


        $row_config = mysql_fetch_assoc($rs_config);
        $max_quantity = $row_config['max_quantity'];
        $price_formula = $row_config['price_formula'];
        $profit_percentage = $row_config['profit_percentage'];
	  
	  $asin=$data['asin'];
	  $title=addslashes($data['title']);
	  $title=str_replace("&#x27;","\'",$title);
	 // echo $title;die;
	  $description=str_replace("'","\'",$data['description']);
	 
	  $features=str_replace("'","\'",$data['features']);
	  $j=0;
	  while($data['pictures'.$j]!="") 
	  {
	  $pictures[]=$data['pictures'.$j];
	  $j++;
	  }
	  $thumbpictures="";
	  if(count($pictures)>0) {
	  $thumbpictures=implode(',',$pictures);
	  }
	  //echo $pictures;die;
	  $image=$data['imageurl'];
	  $listprice=$result['listprice'];
	  $price=$data['offerprice'];
	 $offer_price=substr(trim($price),1,strlen($price));
	  
	  $prime=$data['prime'];
	  $brand=$data['brand'];
	   $brand=addslashes($data['brand']);
	  $brand=str_replace("&#x27;","\'",$brand);
	  $shippping_cost=$data['shippingprice'];
	  $amazon_quantity=$data['quantity'];
	  $currency_code="USD";
	  if($prime=='Yes'&&$price!=''){
	    $aws_asin_insert_sql = "INSERT INTO aws_asin(UserId,asin,title,description,
                                                    features,large_image_url,thumb_img,brand,list_price,
                                                    prime,offer_price,shipping_cost,
                                                    currency_code,quantity,processed) 
                                                    
                                VALUES 
                                                    ($active_user,'$asin','$title','$description',
                                                     '$features','$image','$thumbpictures','$brand','$list_price',
                                                     '$prime','$offer_price','$shippping_cost',
                                                     '$currency_code',$amazon_quantity,2)";


                 //echo  $aws_asin_insert_sql;die;

                $asins_table_update_sql = "UPDATE asins_table SET processed=1 WHERE asins='$asin' AND UserID = $active_user";
				  (mysql_query($aws_asin_insert_sql) and mysql_query($asins_table_update_sql)) or die(mysql_error());
	        }
	
     $sql_success = "SELECT asin FROM aws_asin WHERE asin='$asin' AND UserID='$active_user'";
                $rs_sucess = mysql_query($sql_success) or die(mysql_error());
                //
                if (mysql_num_rows($rs_sucess)) {
                    $offer_price = str_replace(array('$' . ','), '', $offer_price);
                    $offer_price = floatval($offer_price);

                    $ebay_select_sql = "SELECT asins FROM ebay_asin WHERE asins='$asin' AND UserID='$active_user'";
                    $ebay_asin_result = mysql_query($ebay_select_sql) or die(mysql_error());

                    if (!mysql_num_rows($ebay_asin_result)) {
                        //
                        if ($offer_price == 'Not Available' or !isset($offer_price)) {
                            $offer_price = $list_price;
                        }
                        //

                        $temp_title = str_replace(',', ' ', $title);

                        $title_words = explode(' ', $temp_title);
                        $new_title = trim("New ");

                        foreach ($title_words as $title_word) {
                            $title_word = trim($title_word);

                            if ($title_word == '' || empty($title_word))
                                continue;
                            if ((strlen($new_title) + strlen($title_word)) >= 80)
                                break;

                            if (trim($new_title) != '') {
                                $new_title .= " " . $title_word;
                            }
                        }
                        // New Title got...
                        //
                        if (trim($price_formula) == "001") {
                            $ebay_price = price_basic_profit_percetage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "002") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "003") {
                            $ebay_price = price_formula_profit_percentage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "004") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } else {
                            die('Something Gone Wrong on Profit Calculation');
                        }


                        $profit_ratio =$profit_percentage;

                        //$temp_price = bcadd(bcmul($offer_price, 100), bcmul($offer_price, 25));
                        //$ebay_price = bcadd(bcmul($temp_price, 0.01, 4), 0.01, 2);
                        //
                       

                             

                        //
                        $insert_ebay_asin_sql = "INSERT INTO ebay_asin(
                                                 UserID,asins,ebay_title,prefix,
                                                 ebay_price,amazon_price,shipping_charge,
                                                 handling_time,shipping_option,return_option,profit_percent,
                                                 profit_ratio,amazon_quantity,max_quantity
                                                 ) 
                            
                                                VALUES (
                                                $active_user,'$asin','$new_title','New ',
                                                 $ebay_price, $offer_price ,'$shippping_cost',
                                                 1,'free','ReturnsAccepted',$profit_percentage,
                                                 $profit_ratio,$amazon_quantity,$max_quantity
                        )";
                        mysql_query($insert_ebay_asin_sql) or die(mysql_error());
                    }

                    $this->no_inserted_asins++;
                }	
			

	}
	
	   public function insert_into__scrape_number($data) {
	require_once 'redirect.php';
        require_once 'profit_calculation.php';
		
	  $active_user = $_SESSION['user_id'];
	  
	  $sql_ebay_config = "SELECT 
                            max_quantity,
                            price_formula,
                            profit_percentage
         FROM   ebay_config 
         WHERE 
                user_id=$active_user";
        $rs_config = mysql_query($sql_ebay_config) or die('Something Wrong...!');

        if (mysql_num_rows($rs_config) != 1)
            die('Someting Wrong..!');


        $row_config = mysql_fetch_assoc($rs_config);
        $max_quantity = $row_config['max_quantity'];
        $price_formula = $row_config['price_formula'];
        $profit_percentage = $row_config['profit_percentage'];
	  
	  $asin=$data['itemid'];
	  $title=str_replace("'","\'",$data['title']);
	  $description=str_replace("'","\'",$data['description']);
	 
	  $features=str_replace("'","\'",$data['features']);
	  $j=0;
	  while($data['pictures'.$j]!="") 
	  {
	  $pictures[]=$data['pictures'.$j];
	  $j++;
	  }
	  $thumbpictures="";
	  if(count($pictures)>0) {
	  $thumbpictures=implode(',',$pictures);
	  }
	  //echo $pictures;die;
	  $image=$data['imageurl'];
	  $price=$data['price'];
	   $offer_price=substr(trim($price),1,strlen($price));
	    $offer_price=number_format((float)$offer_price, 2, '.', '');
	//  echo $offer_price;die;
	  $prime=$data['prime'];
	  $walmart_quantity=$data['quantity'];
	  $currency_code="USD";
	    $aws_asin_insert_sql = "INSERT INTO aws_asin(UserId,asin,title,description,
                                                    features,large_image_url,thumb_img,
                                                    prime,offer_price,
                                                    currency_code,quantity,processed) 
                                                    
                                VALUES 
                                                    ($active_user,'$asin','".$title."','$description',
                                                     '$features','$image','$thumbpictures',
                                                     '$prime','$offer_price',
                                                     '$currency_code',$walmart_quantity,2)";


                // echo  $aws_asin_insert_sql;die;

                $asins_table_update_sql = "UPDATE asins_table SET processed=1 WHERE asins='$asin' AND UserID = $active_user";
				  (mysql_query($aws_asin_insert_sql) and mysql_query($asins_table_update_sql)) or die(mysql_error());
	  
     $sql_success = "SELECT asin FROM aws_asin WHERE asin='$asin' AND UserID='$active_user'";
                $rs_sucess = mysql_query($sql_success) or die(mysql_error());
                //
                if (mysql_num_rows($rs_sucess)) {
                    $offer_price = str_replace(array('$' . ','), '', $offer_price);
                    $offer_price = floatval($offer_price);

                    $ebay_select_sql = "SELECT asins FROM ebay_asin WHERE asins='$asin' AND UserID='$active_user'";
                    $ebay_asin_result = mysql_query($ebay_select_sql) or die(mysql_error());

                    if (!mysql_num_rows($ebay_asin_result)) {
                        //
                        if ($offer_price == 'Not Available' or !isset($offer_price)) {
                            $offer_price = $list_price;
                        }
                        //

                        $temp_title = str_replace(',', ' ', $title);

                        $title_words = explode(' ', $temp_title);
                        $new_title = trim("New ");

                        foreach ($title_words as $title_word) {
                            $title_word = trim($title_word);

                            if ($title_word == '' || empty($title_word))
                                continue;
                            if ((strlen($new_title) + strlen($title_word)) >= 80)
                                break;

                            if (trim($new_title) != '') {
                                $new_title .= " " . $title_word;
                            }
                        }
                        // New Title got...
                        //
                        if (trim($price_formula) == "001") {
                            $ebay_price = price_basic_profit_percetage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "002") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "003") {
                            $ebay_price = price_formula_profit_percentage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "004") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } else {
                            die('Something Gone Wrong on Profit Calculation');
                        }


                        $profit_ratio = $profit_percentage;


                        //$temp_price = bcadd(bcmul($offer_price, 100), bcmul($offer_price, 25));
                        //$ebay_price = bcadd(bcmul($temp_price, 0.01, 4), 0.01, 2);
                        //
                       

                           

                        //
                        $insert_ebay_asin_sql = "INSERT INTO ebay_asin(
                                                 UserID,asins,ebay_title,prefix,
                                                 ebay_price,amazon_price,
                                                 handling_time,shipping_option,return_option,profit_percent,
                                                 profit_ratio,amazon_quantity,max_quantity
                                                 ) 
                            
                                                VALUES (
                                                $active_user,'$asin','$new_title','New ',
                                                 $ebay_price, $offer_price ,
                                                 1,'free','ReturnsAccepted',$profit_percentage,
                                                 $profit_ratio,$walmart_quantity,$max_quantity
                        )";
                        mysql_query($insert_ebay_asin_sql) or die(mysql_error());
                    }

                    $this->no_inserted_asins++;
                }	
			
	
	}
	 
	  public function insert_into__scrape_overnumber($data) {
	require_once 'redirect.php';
        require_once 'profit_calculation.php';
		
	  $active_user = $_SESSION['user_id'];
	  
	  $sql_ebay_config = "SELECT 
                            max_quantity,
                            price_formula,
                            profit_percentage
         FROM   ebay_config 
         WHERE 
                user_id=$active_user";
        $rs_config = mysql_query($sql_ebay_config) or die('Something Wrong...!');

        if (mysql_num_rows($rs_config) != 1)
            die('Someting Wrong..!');


        $row_config = mysql_fetch_assoc($rs_config);
        $max_quantity = $row_config['max_quantity'];
        $price_formula = $row_config['price_formula'];
        $profit_percentage = $row_config['profit_percentage'];
	  
	  $asin=$data['itemid'];
	  //$title=str_replace("'","\'",$data['title']);
	  $title=addslashes($data['title']);
	  $title=str_replace("&#x27;","\'",$title);
	  
	  $description=str_replace("'","\'",$data['description']);
	 
	  $features=str_replace("'","\'",$data['features']);
	  $j=0;
	
	  while($data['pictures'.$j]!="") 
	  {
	  $pictures[]=$data['pictures'.$j];
	  $j++;
	  }
	  $thumbpictures="";
	  if(count($pictures)>0) {
	  $thumbpictures=implode(',',$pictures);
	  }
	  //echo $pictures;die;
	  $image=$data['imageurl'];
	  $price=$data['price'];

	   $offer_price=substr(trim($price),1,strlen($price));
	    $offer_price=number_format((float)$offer_price, 2, '.', '');
	//  echo $offer_price;die;
	  $prime=$data['prime'];
	  $brand=$data['brand'];
	  $overstock_quantity=$data['quantity'];
	  $currency_code="USD";
	
	    $aws_asin_insert_sql = "INSERT INTO aws_asin(UserId,asin,title,description,
                                                    features,large_image_url,thumb_img,brand,
                                                    prime,offer_price,
                                                    currency_code,quantity,processed) 
                                                    
                                VALUES 
                                                    ($active_user,'$asin','".$title."','$description',
                                                     '$features','$image','$thumbpictures','$brand',
                                                     '$prime','$offer_price',
                                                     '$currency_code',$overstock_quantity,2)";


                // echo  $aws_asin_insert_sql;die;

                $asins_table_update_sql = "UPDATE asins_table SET processed=1 WHERE asins='$asin' AND UserID = $active_user";
				  (mysql_query($aws_asin_insert_sql) and mysql_query($asins_table_update_sql)) or die(mysql_error());
	  
     $sql_success = "SELECT asin FROM aws_asin WHERE asin='$asin' AND UserID='$active_user'";
                $rs_sucess = mysql_query($sql_success) or die(mysql_error());
                //
                if (mysql_num_rows($rs_sucess)) {
                    $offer_price = str_replace(array('$' . ','), '', $offer_price);
                    $offer_price = floatval($offer_price);

                    $ebay_select_sql = "SELECT asins FROM ebay_asin WHERE asins='$asin' AND UserID='$active_user'";
                    $ebay_asin_result = mysql_query($ebay_select_sql) or die(mysql_error());

                    if (!mysql_num_rows($ebay_asin_result)) {
                        //
                        if ($offer_price == 'Not Available' or !isset($offer_price)) {
                            $offer_price = $list_price;
                        }
                        //

                        $temp_title = str_replace(',', ' ', $title);

                        $title_words = explode(' ', $temp_title);
                        $new_title = trim("New ");

                        foreach ($title_words as $title_word) {
                            $title_word = trim($title_word);

                            if ($title_word == '' || empty($title_word))
                                continue;
                            if ((strlen($new_title) + strlen($title_word)) >= 80)
                                break;

                            if (trim($new_title) != '') {
                                $new_title .= " " . $title_word;
                            }
                        }
                        // New Title got...
                        //
                        if (trim($price_formula) == "001") {
                            $ebay_price = price_basic_profit_percetage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "002") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "003") {
                            $ebay_price = price_formula_profit_percentage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "004") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } else {
                            die('Something Gone Wrong on Profit Calculation');
                        }


                       // $profit_ratio = round(bcsub(bcdiv($ebay_price, $offer_price, 8), 1, 8),2);
                            $profit_ratio=$profit_percentage;

                        //$temp_price = bcadd(bcmul($offer_price, 100), bcmul($offer_price, 25));
                        //$ebay_price = bcadd(bcmul($temp_price, 0.01, 4), 0.01, 2);
                        //
                       
                        //$title=addslashes($data['title']);
	                    //$title=str_replace("&#x27;","\'",$title);
                          
                           
                        //   
                        $insert_ebay_asin_sql = "INSERT INTO ebay_asin(
                                                 UserID,asins,ebay_title,prefix,
                                                 ebay_price,amazon_price,
                                                 handling_time,shipping_option,return_option,profit_percent,
                                                 profit_ratio,amazon_quantity,max_quantity
                                                 ) 
                            
                                                VALUES (
                                                $active_user,'$asin','$new_title','New ',
                                                 $ebay_price, $offer_price ,
                                                 1,'free','ReturnsAccepted',$profit_percentage,
                                                 $profit_ratio,$overstock_quantity,$max_quantity
                        )";
                        mysql_query($insert_ebay_asin_sql) or die(mysql_error());
                    }

                    $this->no_inserted_asins++;
                }	 
			
	
	}
	 
	 function insert_into__scrape_aliexpress($data) {
	require_once 'redirect.php';
        require_once 'profit_calculation.php';
		
	  $active_user = $_SESSION['user_id'];
	  
	  $sql_ebay_config = "SELECT 
                            max_quantity,
                            price_formula,
                            profit_percentage
         FROM   ebay_config 
         WHERE 
                user_id=$active_user";
        $rs_config = mysql_query($sql_ebay_config) or die('Something Wrong...!');

        if (mysql_num_rows($rs_config) != 1)
            die('Someting Wrong..!');


        $row_config = mysql_fetch_assoc($rs_config);
        $max_quantity = $row_config['max_quantity'];
        $price_formula = $row_config['price_formula'];
        $profit_percentage = $row_config['profit_percentage'];
	  
	  $asin=$data['itemid'];
	  //$title=str_replace("'","\'",$data['title']);
	  $title=addslashes($data['title']);
	  $title=str_replace("&#x27;","\'",$title);
	  
	  $description=str_replace("'","\'",$data['description']);
	 
	  $features=str_replace("'","\'",$data['features']);
	  $j=0;
	
	  while($data['pictures'.$j]!="") 
	  {
	  $pictures[]=$data['pictures'.$j];
	  $j++;
	  }
	  $thumbpictures="";
	  if(count($pictures)>0) {
	  $thumbpictures=implode(',',$pictures);
	  }
	  //echo $pictures;die;
	  $image=$data['imageurl'];
	  $price=$data['price'];
	 
	   $offer_price=explode('-',trim($price));
	    $offer_price=number_format((float)$offer_price[0], 2, '.', '');
	  
	  $prime=$data['prime'];
	 // $brand=$data['brand'];
	  $aliexpress_quantity=$data['quantity'];
	  $currency_code="USD";
	
	    $aws_asin_insert_sql = "INSERT INTO aws_asin(UserId,asin,title,description,
                                                    features,large_image_url,thumb_img,
                                                    prime,offer_price,
                                                    currency_code,quantity,processed) 
                                                    
                                VALUES 
                                                    ($active_user,'$asin','".$title."','$description',
                                                     '$features','$image','$thumbpictures',
                                                     '$prime','$offer_price',
                                                     '$currency_code',$aliexpress_quantity,2)";


                // echo  $aws_asin_insert_sql;die;

                $asins_table_update_sql = "UPDATE asins_table SET processed=1 WHERE asins='$asin' AND UserID = $active_user";
				  (mysql_query($aws_asin_insert_sql) and mysql_query($asins_table_update_sql)) or die(mysql_error());
	  
     $sql_success = "SELECT asin FROM aws_asin WHERE asin='$asin' AND UserID='$active_user'";
                $rs_sucess = mysql_query($sql_success) or die(mysql_error());
                //
                if (mysql_num_rows($rs_sucess)) {
                    $offer_price = str_replace(array('$' . ','), '', $offer_price);
                    $offer_price = floatval($offer_price);

                    $ebay_select_sql = "SELECT asins FROM ebay_asin WHERE asins='$asin' AND UserID='$active_user'";
                    $ebay_asin_result = mysql_query($ebay_select_sql) or die(mysql_error());

                    if (!mysql_num_rows($ebay_asin_result)) {
                        //
                        if ($offer_price == 'Not Available' or !isset($offer_price)) {
                            $offer_price = $list_price;
                        }
                        //

                        $temp_title = str_replace(',', ' ', $title);

                        $title_words = explode(' ', $temp_title);
                        $new_title = trim("New ");

                        foreach ($title_words as $title_word) {
                            $title_word = trim($title_word);

                            if ($title_word == '' || empty($title_word))
                                continue;
                            if ((strlen($new_title) + strlen($title_word)) >= 80)
                                break;

                            if (trim($new_title) != '') {
                                $new_title .= " " . $title_word;
                            }
                        }
                        // New Title got...
                        //
                        if (trim($price_formula) == "001") {
                            $ebay_price = price_basic_profit_percetage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "002") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "003") {
                            $ebay_price = price_formula_profit_percentage($offer_price, $profit_percentage);
                        } elseif (trim($price_formula) == "004") {
                            $ebay_price = price_basic_amount_profit($offer_price, $profit_percentage);
                        } else {
                            die('Something Gone Wrong on Profit Calculation');
                        }


                       // $profit_ratio = round(bcsub(bcdiv($ebay_price, $offer_price, 8), 1, 8),2);
                            $profit_ratio=$profit_percentage;

                        //$temp_price = bcadd(bcmul($offer_price, 100), bcmul($offer_price, 25));
                        //$ebay_price = bcadd(bcmul($temp_price, 0.01, 4), 0.01, 2);
                        //
                       
                        //$title=addslashes($data['title']);
	                    //$title=str_replace("&#x27;","\'",$title);
                          
                           
                        //   
                        $insert_ebay_asin_sql = "INSERT INTO ebay_asin(
                                                 UserID,asins,ebay_title,prefix,
                                                 ebay_price,amazon_price,
                                                 handling_time,shipping_option,return_option,profit_percent,
                                                 profit_ratio,amazon_quantity,max_quantity
                                                 ) 
                            
                                                VALUES (
                                                $active_user,'$asin','$new_title','New ',
                                                 $ebay_price, $offer_price ,
                                                 1,'free','ReturnsAccepted',$profit_percentage,
                                                 $profit_ratio,$aliexpress_quantity,$max_quantity
                        )";
                        mysql_query($insert_ebay_asin_sql) or die(mysql_error());
                    }

                    $this->no_inserted_asins++;
                }	 
			
	
	}
	 
    public function get_inserted_asins() {
        return $this->no_inserted_asins;
    }

    private function num_to_inch($num) {

        if (!is_numeric(trim($num))) {
            die("Check the exection routine 1 YYY: $num");
        }

        $digit_length = strlen($num);

        if ($digit_length > 2) {
            $decimal = substr($num, ($digit_length - 2), ($digit_length - 1));

            $integer = substr($num, 0, ($digit_length - 2));

            return $integer . "." . $decimal . "\"";
        } elseif ($digit_length == 2) {
            $decimal = substr($num, ($digit_length - 2), ($digit_length - 1));
            return "0." . strval($decimal) . "\"";
        } elseif ($digit_length == 1) {
            return "0.0" . strval($num) . "\"";
        } elseif ($digit_length == 0) {
            die("Check the exection routine 2: $num");
        }
    }

    private function num_to_pounds($num) {

        if (!is_numeric(trim($num))) {
            die("Check the exection routine 1 ZZZ: $num");
        }

        $digit_length = strlen($num);

        if ($digit_length > 2) {
            $decimal = substr($num, ($digit_length - 2), ($digit_length - 1));

            $integer = substr($num, 0, ($digit_length - 2));

            return $integer . "." . $decimal . " pounds";
        } elseif ($digit_length == 2) {
            $decimal = substr($num, ($digit_length - 2), ($digit_length - 1));
            return "0." . strval($decimal) . " pounds";
        } elseif ($digit_length == 1) {
            return "0.0" . strval($num) . " pounds";
        } elseif ($digit_length == 0) {
            die("Check the exection routine 2: $num");
        }
    }

    private function get_data($url) {

        $ch = curl_init();


        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: "; // browsers keep this blank.
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.152 Safari/537.22');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies_asin.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies_asin.txt");
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

}

?>
