<?php

function scrap_amazon($asin)
{
    return scrap_amazon_pq($asin);

//    $asin="B005G4HT6I"; // Not prime
//    $asin="B000237C9M"; // Prime;
//    $asin="B00P9UPVY6"; // Out of stock;
    $asin="B00FLYWNYQ"; 


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

function scrap_amazon_pq($asin)
{
    set_time_limit(10);
    $url  = "http://www.amazon.com/dp/" . $asin;
    $result['url']    = $url;
    $data = getPage($url);

    $document = phpQuery::newDocument($data);

    if (pq_get_first_text('h1[id=title]') == 1) {
        $result['scrapok'] = 1;
    } else {
        $result['scrapok'] = 0;
        return $result;
    }

//    $price  = floatval(str_replace('$', '', $html->find('#priceblock_ourprice', 0)->plaintext));
//    $q  = count((array) $html->find('#quantity option'));
//    $prime = (!$price or @$html->find('#soldByThirdParty', 0)->plaintext)?'No':'Yes';
  
    $price  = floatval(str_replace('$', '', pq_get_first_text('#priceblock_ourprice')));
    $q  = count((array) pq_get_array('#quantity option'));
    $prime = (!$price or @$pq_get_first_text('#soldByThirdParty', 0)->plaintext)?'No':'Yes';
  
    $result = array('quantity' => intval($q),
        'offerprice' => $price,
        'prime' => $prime,
        'url' => $url
        );

    xp($result);
    return $result;

}


function scrap_amazon_all($asin)
{
    $url  = "http://www.amazon.com/dp/" . $asin;
    $result['url']    = $url;
    //$url="http://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new&condition=new";
    $data = getPage($url);

    preg_match_all("%http://ecx.images-amazon.com/images/I/\w+.jpg%", $data, $matches);
    $imgs=array_unique($matches[0]);
    
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

    $price  = floatval(str_replace('$', '', $html->find('#priceblock_saleprice,#priceblock_ourprice', 0)->plaintext));
    $q  = count((array) $html->find('#quantity option'));
    $prime = (!$price or @$html->find('#soldByThirdParty', 0)->plaintext)?'No':'Yes';

    $features = array_map(function($el){return $el->plaintext;}, (array)$html->find('#feature-bullets li'));

    $desc = @$html->find('#productDescription', 0)->plaintext || '';


    $result = array('quantity' => intval($q),
        'offerprice' => $price,
        'sku' => $asin,
        'prime' => $prime,
        'url' => $url,
        'title' => $html->find('#productTitle', 0)->plaintext,
        'features' => $features,
        'desc' => $desc,
        'img' => $imgs
        );

  
    return $result;

}

