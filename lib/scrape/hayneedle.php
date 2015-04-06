<?php
function scrap_hayneedle($url)
{  
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $url = "http://search.hayneedle.com/search/index.cfm?Ntt=" . $url;
    }

    
    
    $data              = getPage($url);

    $html              = str_get_html($data);
    $result['scrapok'] = 0;
    if ($html) {
        foreach ($html->find('span[class=standard-style noWrap]') as $item) {
            $itemnumber        = $item->plaintext;
            $result['scrapok'] = 1;
            break;
        }
        $result['scrapok'] = 1;
        $result['itemid']  = $itemnumber;
        
        preg_match("|<span.*?itemprop=\"price\".*?>(.*?)</span>|s", $html, $match_item_price);
        
        if (isset($match_item_price[1])) {
            $item_price = trim($match_item_price[1]);
        }
        $result['offerprice'] = $item_price;
        $result['offerprice'] = str_replace('$', '', $result['offerprice']);
        $result['offerprice'] = str_replace(',', '', $result['offerprice']);
        
        /*
        $qte=0;
        foreach($html->find('div[id=addCartMain_quantity] select') as $select) {
        foreach($select->find('option') as $opt) {
        $qte++;
        }
        }
        */
        $result['quantity'] = 100;
        $result['prime']    = 'Yes';
        $result['url']    = $url;
        return $result;
    } else
        return -1;
}
