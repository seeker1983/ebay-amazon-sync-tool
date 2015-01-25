<?php

function scrap_overstock($itemid)
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
