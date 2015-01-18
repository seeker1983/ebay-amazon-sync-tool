<?php

// functions

function file_shift($fname)
{
    $lines = file(QUERY_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $line = array_shift($lines);

    file_put_contents(QUERY_FILE, implode("\n", $lines));
    return $line;
}

function file_push($fname, $line)
{
    $fid = fopen($fname, 'a');
    fwrite($fid, $line);
    fclose($fid);
    return 0;
}

function query($asin) {
    global $public_key, $private_key;

    /* $requestparams	=	array("Operation"=>"ItemLookup", "Condition"=>"All", "ItemId"=>$asin, "IdType"=>"ASIN", "Availability"=>"Available", 
      "ResponseGroup"=>"Offers,OfferSummary,ItemAttributes"); */

    $requestparams = array("Operation" => "ItemLookup", "Condition" => "All", "ItemId" => $asin, "IdType" => "ASIN", "Availability" => "Available",
        "ResponseGroup" => "Large","MerchantId"=>"Amazon");

    $pxml = aws_signed_request("com", $requestparams, $public_key, $private_key);

    //print_r($pxml);
    //die();
    if ($pxml === False) {
        echo "No response.\n";
    } else {
        if ($pxml) {
            store_results($pxml);
            //unset($pxml);
        } else {
            echo "Query returned no results.\n";
        }
    }
}

function store_results($pxml) {
    global $active_user;
    print_r($pxml);
    $i = 0;
    //print "------------------------------------------------------------------------------------------<br/>";		
    if (isset($pxml->Items->Item)) {
        while ($i < count($pxml->Items->Item)) {
            $item = $pxml->Items->Item[$i];

            //requirments
            $asin = $item->ASIN;
            if (isset($item->ItemAttributes->Title)) {
                $title = $item->ItemAttributes->Title;
                $title = str_replace("'", "`", $title);
            } else {
                $title = '';
            }

            if (isset($item->EditorialReviews->EditorialReview->Content)) {
                $description = $item->EditorialReviews->EditorialReview->Content;
                $description = str_replace(array("'", '"'), " ", $description);

                //$description_url = "description_text.php?desc_asin=".$asin."&user_id=".$active_user;

                $description_url = '<a href="description_text.php?desc_asin=' . $asin . '&user_id=' . $active_user . '" target="_blank"> click here </a>';
            } else {
                $description = '';
                $description_url = '';
            }

            if (isset($item->ImageSets->ImageSet->LargeImage->URL)) {
                $large_img_url = $item->ImageSets->ImageSet->LargeImage->URL;
            } else {
                $large_img_url = '';
            }

            if (isset($item->ImageSets->ImageSet->MediumImage->URL)) {
                $medium_img_url = $item->ImageSets->ImageSet->MediumImage->URL;
            } else {
                $medium_img_url = '';
            }

            if (isset($item->ImageSets->ImageSet->SmallImage->URL)) {
                $small_img_url = $item->ImageSets->ImageSet->SmallImage->URL;
            } else {
                $small_img_url = '';
            }

            if (isset($item->ImageSets->ImageSet->ThumbnailImage->URL)) {
                $thumb_img = $item->ImageSets->ImageSet->ThumbnailImage->URL;
            } else {
                $thumb_img = '';
            }

            if (isset($item->ItemAttributes->ItemDimensions->Weight)) {
                $weight = $item->ItemAttributes->ItemDimensions->Weight;
            } else {
                $weight = 0;
            }

            if (isset($item->ItemAttributes->ItemDimensions->Height)) {
                $height = $item->ItemAttributes->ItemDimensions->Height;
            } else {
                $height = 0;
            }

            if (isset($item->ItemAttributes->ItemDimensions->Width)) {
                $width = $item->ItemAttributes->ItemDimensions->Width;
            } else {
                $width = 0;
            }

            if (isset($item->ItemAttributes->ItemDimensions->Length)) {
                $length = $item->ItemAttributes->ItemDimensions->Length;
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
            } else {
                $list_price = '';
            }

            /* if(isset($item->OfferSummary->LowestNewPrice->FormattedPrice)) {
              $offer_price = $item->OfferSummary->LowestNewPrice->FormattedPrice;
              }
              else {
              $offer_price = '';
              } */

            if (isset($item->ItemAttributes->PackageQuantity)) {
                $quantity = $item->ItemAttributes->PackageQuantity;
            } else {
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

            $weight_string = num_to_pounds($weight);

            $dimensions = num_to_inch($length) . " x " . num_to_inch($width) . " x " . num_to_inch($height);
            $dimensions = mysql_real_escape_string($dimensions);

            $size = $item->ItemAttributes->Size;
            $size = str_replace(array("'", '"'), "", $size);

            $mpn = $item->ItemAttributes->MPN;
            $currency_code = $item->ItemAttributes->ListPrice->CurrencyCode;
            $sku = $item->ItemAttributes->SKU;
            $upc = $item->ItemAttributes->UPC;
            $ean = $item->ItemAttributes->EAN;

            
            // Using preg match to scrape the offer price in the paticular ASIN's item url.
            //$item_url = $item->DetailPageURL;
            $item_url = "http://www.amazon.com/s/keywords=" . $asin;

            $html = get_data($item_url);

            //Get Offer Price
            preg_match('/<span class="bld lrg red">([^`]*?)<\/span>/', $html, $match);  //print_r($match);
            $offer_price = $match[1];

            //See the prime product or not (See the prime span tag is available or not. If available set prime to Yes)
            $prime = "No";
            if (preg_match('/<span class="srSprite sprPrime">/', $html, $match)) {
                $prime = "Yes";
            }


            //$ebay_description = $description."\r\n".$features;
            //Deafault Template....
            $ebay_description = '<p> <b> <u> Description </u> : </b> </p>
									 <p style="color: #033;">' . $description . '</p>
									 
									 <hr>
									
									 <p> <b> <u> Features </u> : </b> </p>
									 <p style="color:#CC6600;">' . $features . '</p>
											
									 <hr>';


            $update_db = "UPDATE aws_asin SET   title = '$title',
                                                description_url = '$description_url' ,
                                                description = '$description', 
                                                features = '$features' ,
                                                large_image_url = '$large_img_url',
                                                medium_image_url = '$medium_img_url', 
                                                small_image_url = '$small_img_url',
                                                weight = $weight,
                                                length = $length,
                                                width = $width,
                                                height =$height,
                                                weight_string = '$weight_string', 
                                                dimensions = '$dimensions',                                                 
                                                brand = '$brand',
                                                ean = '$ean', 
                                                list_price = '$list_price',
                                                offer_price = '$offer_price',
                                                currency_code = '$currency_code',
                                                size = '$size', 
                                                sku = '$sku', 
                                                upc = '$upc',
                                                quantity = $quantity,
                                                mpn = '$mpn' ,
                                                prime = '$prime',
                                                thumb_img = '$thumb_img'
                                                                                 WHERE asin = '$asin' AND UserID = $active_user ";
           // echo $update_db;
            mysql_query($update_db) or die(mysql_error());

            // set the ebay price b4 put to ebay_asin table.
            $offer_price = str_replace(array('$', ','), '', $offer_price);
            $offer_price = floatval($offer_price);

            $sql_ebay = "SELECT * FROM ebay_asin WHERE asins = '$asin' AND UserID = $active_user ";
            $rs_ebay = mysql_query($sql_ebay) or die(mysql_error());
            $count_ebay = mysql_num_rows($rs_ebay);


            if ($count_ebay == 0) {
                $insert_ebay_asin = "INSERT INTO ebay_asin SET UserID = $active_user , asins = '$asin', ebay_title = '$title', ebay_description = '$ebay_description' , ebay_description_url = '$description_url' , ebay_price = $offer_price, amazon_price = $offer_price ";
                //echo $insert_ebay_asin.'<br>';
                mysql_query($insert_ebay_asin) or die(mysql_error());
            }



            $i++;
        }
    }

    //print "------------------------------------------------------------------------------------------<br/>";
    flush();
}

function get_data($url) {

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

function curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);
}

function encrypt_decrypt($Action, $String) {
    $output = false;
    $key = 'My Strong Random Secret Key';
    $iv = md5(md5($key));
    if ($Action == 'encrypt') {
        $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $String, MCRYPT_MODE_CBC, $iv);
        $output = base64_encode($output);
    } else if ($Action == 'decrypt') {
        $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($String), MCRYPT_MODE_CBC, $iv);
        //$output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), $String, MCRYPT_MODE_CBC, $iv);
        $output = rtrim($output);
    }
    return $output;
}

function num_to_inch($num) {

    if (!is_numeric(trim($num))) {
        die("Check the exection routine 1: $num");
    }

    $digit_length = strlen($num);

    if ($digit_length > 2) {
        $decimal = substr($num, ($digit_length - 2), ($digit_length - 1));

        $integer = substr($num, 0, ($digit_length - 2));

        return $integer . "." . $decimal . "\"";
    } elseif ($digit_length == 2) {
        $decimal = substr($num, ($digit_length - 2), ($digit_length - 1));
        return "0." . $decimal . "\"";
    } elseif ($digit_length == 1) {
        return "0.0" . $num . "\"";
    } elseif ($digit_length == 0) {
        die("Check the exection routine 2: $num");
    }
}

function num_to_pounds($num) {

    if (!is_numeric(trim($num))) {
        die("Check the exection routine 1: $num");
    }

    $digit_length = strlen($num);

    if ($digit_length > 2) {
        $decimal = substr($num, ($digit_length - 2), ($digit_length - 1));

        $integer = substr($num, 0, ($digit_length - 2));

        return $integer . "." . $decimal . " pounds";
    } elseif ($digit_length == 2) {
        $decimal = substr($num, ($digit_length - 2), ($digit_length - 1));
        return "0." . $decimal . " pounds";
    } elseif ($digit_length == 1) {
        return "0.0" . $num . " pounds";
    } elseif ($digit_length == 0) {
        die("Check the exection routine 2: $num");
    }
}

?>