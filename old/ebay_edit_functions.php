<?php

function transform_title($title_prefix) {
    //
    $active_user = $_SESSION['user_id'];
    require_once 'inc.db.php';


    $sql_aws_asin = "SELECT title,
                            asin,
                            description,
                            features,
                            large_image_url,
                            medium_image_url,
                            small_image_url,
                            thumb_img,
                            swatch_image_url,
                            tiny_image_url 
                                            FROM aws_asin WHERE UserID = $active_user";
    $result_aws_asin = mysql_query($sql_aws_asin) or die(mysql_error());

    $template_file = "./templates/template_user_" . $active_user . ".txt";
    if (file_exists($template_file))
        $file_content = " " . file_get_contents($template_file) . " ";

    while ($row = mysql_fetch_array($result_aws_asin)) {
        $title = $row['title'];
        $asin = $row['asin'];
        $description = $row['description'];
        $features = $row['features'];
        //
        $image_1 = $row['large_image_url'];
        $image_2 = $row['medium_image_url'];
        $image_3 = $row['small_image_url'];
        $image_4 = $row['thumb_img'];
        $image_5 = $row['swatch_image_url'];
        $image_6 = $row['tiny_image_url'];
        //
        if (trim($title) == '')
            break;

        $title = str_replace(',', ' ', $title);

        $title_words = explode(' ', $title);
        $new_title = $title_prefix;

        foreach ($title_words as $title_word) {
            $title_word = trim($title_word);

            if ($title_word == '' || empty($title_word))
                continue;
            if ((strlen($new_title) + strlen($title_word)) >= 80)
                break;

            if ($new_title != '') {
                $new_title .= " " . $title_word;
            } elseif ($new_title == '') {
                $new_title = $title_word;
            }
        }

        if (strlen(trim($new_title)) > 80)
            die("String greater than 80 characters");

        //
        //
        $update = "UPDATE ebay_asin SET ebay_title = '$new_title', prefix = '$title_prefix' WHERE asins = '$asin' AND UserID = $active_user AND in_ebay = 0 ";

        if (isset($file_content)) {
            $find_terms = array('[TITLE]', '[AmazonDescription]', '[AmazonFeatures]',
                '[IMAGE1]', '[IMAGE2]', '[IMAGE3]',
                '[IMAGE4]', '[IMAGE5]', '[IMAGE6]'
            );

            $replace_terms = array($new_title, $description, $features,
                $image_1, $image_2, $image_3,
                $image_4, $image_5, $image_6
            );

            if (sizeof($find_terms) != sizeof($replace_terms))
                die("Unequal String Arrays");

            $ebay_description = trim(str_replace($find_terms, $replace_terms, $file_content));

            $update = "UPDATE ebay_asin SET ebay_title = '$new_title', prefix = '$title_prefix',ebay_description='$ebay_description' WHERE asins = '$asin' AND UserID = $active_user AND in_ebay = 0 ";
        }

        mysql_query($update) or die(mysql_error());
    }
}

function update_paypal_address($paypal_adddress) {
    require_once 'inc.db.php';
    $active_user = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $sql_ebay_users = "UPDATE ebay_users SET paypal_address='$paypal_adddress' WHERE user_id=$active_user AND username='$username'";

    mysql_query($sql_ebay_users) or die(mysql_error());
}

function update_packaging_options($handling_days, $shipping_option, $return_option) {
    require_once 'inc.db.php';
    $active_user = $_SESSION['user_id'];
    $sql_ebay_asin = "UPDATE ebay_asin SET  handling_time = '$handling_days', shipping_option = '$shipping_option',return_option='$return_option' WHERE  UserID = $active_user AND in_ebay = 0 ";
    mysql_query($sql_ebay_asin) or die(mysql_error());
}

function amazon_to_ebayprice_with_profit($profit_percentage) {

    $profit_percentage = trim($profit_percentage);

    if (!preg_match("/^[0-9]{1,3}$/", $profit_percentage) and !preg_match("/[0-9]{1,3}\.[0-9]{2}$/", $profit_percentage))
        return;
    if (!is_double(floatval($profit_percentage)))
        return;

    $active_user = $_SESSION['user_id'];
    require_once 'inc.db.php';
    $sql_aws_asin = "SELECT offer_price,asin FROM aws_asin WHERE UserID = $active_user";
    $result_aws_asin = mysql_query($sql_aws_asin) or die(mysql_error());


    while ($row = mysql_fetch_array($result_aws_asin)) {
        $offer_price = $row['offer_price'];
        $asin = $row['asin'];
        $temp_price = bcadd(bcmul($offer_price, 100), bcmul($offer_price, floatval($profit_percentage)));
        $ebay_price = bcadd(bcmul($temp_price, 0.01, 4), 0.01, 2);

        $update = "UPDATE ebay_asin SET profit_percent = '$profit_percentage', ebay_price = $ebay_price WHERE asins = '$asin' AND UserID = $active_user";
        mysql_query($update) or die(mysql_error());
    }
}

function update_template_format($template_format) {
    $active_user = $_SESSION['user_id'];
    require_once 'inc.db.php';

    $sql = "SELECT aws.*,eb.ebay_title FROM aws_asin aws,ebay_asin eb WHERE aws.UserID = $active_user AND eb.in_ebay=0";
    $rs = mysql_query($sql) or die(mysql_error());



    while ($row = mysql_fetch_array($rs)) {

        $asin = $row['asin'];
        $title_db = $row['ebay_title'];
        $desc_db = $row['description'];
        $features_db = $row['features'];

        $image_1 = $row['large_image_url'];
        $image_2 = $row['medium_image_url'];
        $image_3 = $row['small_image_url'];
        $image_4 = $row['thumb_img'];
        $image_5 = $row['swatch_image_url'];
        $image_6 = $row['tiny_image_url'];

        $find_terms = array('[TITLE]', '[AmazonDescription]', '[AmazonFeatures]',
            '[IMAGE1]', '[IMAGE2]', '[IMAGE3]',
            '[IMAGE4]', '[IMAGE5]', '[IMAGE6]'
        );

        $replace_terms = array($title_db, $desc_db, $features_db,
            $image_1, $image_2, $image_3,
            $image_4, $image_5, $image_6
        );

        if (sizeof($find_terms) != sizeof($replace_terms))
            die("Unequal String Arrays");

        $ebay_desc = trim(str_replace($find_terms, $replace_terms, $template_format));
        $template_url = "ebay_template_desc.php?desc_asin=" . $asin . "&user_id=" . $active_user;


        $update = "UPDATE ebay_asin SET ebay_description = '$ebay_desc', ebay_description_url = '$template_url' WHERE asins = '$asin' AND UserID = $active_user AND in_ebay = 0";
        mysql_query($update) or die(mysql_error);
    }

    return 1;
}

?>