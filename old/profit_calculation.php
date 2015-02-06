<?php

function price_basic_profit_percetage($amazon_price,$profit_percentage){
    
    $ebay_price = bcmul($amazon_price,bcadd(1,bcdiv($profit_percentage,100,9),9),9);
    return round($ebay_price,2); 
}

function price_basic_amount_profit($amazon_price,$profit_amount){
    $ebay_price = bcadd($amazon_price,$profit_amount,9);
    return round($ebay_price,2);
    
}

function price_formula_profit_percentage($amazon_price,$profit_percentage){
    
    $temp_price = bcmul($amazon_price,bcadd(1,bcdiv($profit_percentage,100,9),9),9);
    $temp_price = bcadd($temp_price,0.30,9);
    $ebay_price = bcdiv($temp_price,0.871,9);
    return round($ebay_price,2);
}

function price_formula_amount_profit($amazon_price,$profit_amount){
    
    $temp_price = bcadd($amazon_price,bcdiv($profit_amount,100,9),9);
    $temp_price = bcadd($temp_price,0.30,9);
    $ebay_price = bcdiv($temp_price, 0.871,9);
    return round($ebay_price,2);
}

// BELOW IS APPENDIX USED IN SEND TO EBAY
function transform_title($title,$title_prefix) {
    

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

        return $new_title;
}
?>
