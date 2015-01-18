<?php
function scrap_wayfair($url)
{
    
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        
        $url  = "http://www.wayfair.com/keyword.php?keyword=" . $url;
        $data = getPage($url);
        $html = str_get_html($data);
        
        foreach ($html->find('div[id=sbprodgrid] a') as $item) {
            $url = $item->href;
            break;
        }
        
    }

    
    $data              = getPage($url);
    $html              = str_get_html($data);
    $result['scrapok'] = 0;
    if ($html) {
        foreach ($html->find('span[class=breadcrumb note ltbodytext] span[class=emphasis]') as $item) {
            $itemnumber        = $item->plaintext;
            $result['scrapok'] = 1;
            break;
        }

        $result['itemid'] = trim(str_replace('Part #:','',$itemnumber));

        $result['offerprice'] = floatval($html->find('#[data-id=dynamic-sku-price]',0)->plaintext);

        $qte = 0;
        foreach ($html->find('div[class=qty centertext margin_md_bottom] select') as $select) {
            foreach ($select->find('option') as $opt) {
                $qte++;
            }
        }
        
        $result['quantity'] = $qte;
        $result['prime']    = $qte?'Yes':'No';
        $result['url']    = $url;
        
        return $result;
    } else {
        return -1;
    }
}

