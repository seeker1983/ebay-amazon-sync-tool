<?php

function scrap_amazon($asin)
{
//    $asin="B005G4HT6I"; // Not prime
//    $asin="B000237C9M"; // Prime;
//    $asin="B00P9UPVY6"; // Out of stock;
//    $asin="B00CSY9KAW"; // Prime only


    $url  = "http://www.amazon.com/dp/" . $asin;
    $result['url']    = $url;
    //$url="http://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new&condition=new";
    $data = getPage($url);
    
    $html = str_get_html($data);
    $ok   = 0;
    foreach ($html->find('h1[id=title]') as $title) {
        $ok = 1;
        
    }
    
    if ($ok == 1) {
        $result['scrapok'] = 1;
    } else {
        $result['scrapok'] = 0;
        return $result;
    }

    $price  = floatval(str_replace('$', '', $html->find('#priceblock_ourprice', 0)->plaintext));
    $q  = count((array) $html->find('#quantity option'));
    $prime = (!$price or @$html->find('#soldByThirdParty', 0)->plaintext)?'No':'Yes';
  
    $result = array('quantity' => intval($q),
        'offerprice' => $price,
        'prime' => $prime,
        'url' => $url
        );
    return $result;

}
