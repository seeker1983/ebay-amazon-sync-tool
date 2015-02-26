<?php

function scrap_overstock($itemid)
{
    $url  = "http://www.overstock.com/search/" . $itemid;

    $curl_result = get_web_page($url);

//
//    $post_url = "http://www.overstock.com";
//
//    $curl_result = curl_post($post_url, array(
//        'keywords' => $itemid,
//        'SearchType' => 'Header'
//        ));

    if($curl_result['http_code'] == 200)
    {
        $document = phpQuery::newDocument($curl_result['content']);

        $price = floatval(str_replace('$', '', trim(pq_get_first_text("span[itemprop=price]"))));

        $quantity = count(pq_get_array("#addCartMain_quantity select option"));

        if($price && $quantity)
            $result = array(
                'offerprice' => $price,
                'quantity' => $quantity,
                'prime' => 'Yes',
                'scrapok' => true
                );
        else
            $result = array(
                'offerprice' => 0,
                'quantity' => 0,
                'prime' => 'No',
                'scrapok' => true
                );
    }
    else
    {
        $result['scrapok'] = false;
    }

    $result['url'] = $url;

    return $result;
    
}

function scrap_overstock_old($itemid)
{
    $url  = "http://www.overstock.com/search/" . $itemid;
    $result = array('url' => $url);    

    $curl_result = get_web_page($url);

    if($curl_result['http_code'] == 200)
    {
        $html = str_get_html($curl_result['content']);
        xs($curl_result['content']);

        $price_string = $html->find('span[itemprop=price]');
        xd($price_string);
        $result['offerprice'] = preg_replace('%^.*?(\d+\.\d\d.).*$%', '\1', $price_string);
        
        $result['quantity'] = count($html->find('#addCartMain_quantity option'));
        $result['prime'] = $result['quantity'] && $result['price']? 'Yes' : 'No';
        $result['scrapok'] = true;
    }
    else
    {
        $result['scrapok'] = false;
    }
    
    
    return $result;
    
}

function scrap_overstock_all($url, $id)
{
    $curl_result = get_web_page($url);

    if($curl_result['http_code'] == 200)
    {
        $document = phpQuery::newDocument($curl_result['content']);

        $result['offerprice'] =str_replace('$', '', pq_get_first_text('span[itemprop=price]'));
        $result['prime'] = $result['quantity'] && $result['offerprice']? 'Yes' : 'No';
        $result['scrapok'] = true;
    }
    else
    {
        $result['scrapok'] = false;
    }
    return $result;
    
}


