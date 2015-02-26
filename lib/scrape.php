<?php

foreach(glob('lib/' .pathinfo(__FILE__,PATHINFO_FILENAME).  '/*.php') as $lib)
    require_once($lib);

function scrap_item($sku)
{
    $sku = trim($sku);
    if(preg_match('%(AMZ|WM|OS|AL|HN|WF|B)(.*)%', $sku, $matches))
    {
        switch ($matches[1]) 
        {
            case 'B':
                $result = scrap_amazon('B' . $matches[2]);
                break;

            case 'WM':
                $result = scrap_walmart($matches[2]);
                break;

            case 'OS':
                $result = scrap_overstock($matches[2]);
                break;

            case 'AL':
                $result = scrap_aliexpress($matches[2]);
                break;

            case 'HN':
                $result = scrap_hayneedle($matches[2]);
                break;

            case 'WF':
                $result = scrap_wayfair($matches[2]);
                break;

            default:
                die("Unknown item '$sku'");
                break;
        }   
    }

    return $result;
}
function scrap_item_url($url)
{
    if(preg_match('%^http://www.amazon.com.*/dp/(.*?)(/.*)?$%', $url, $matches))
        return scrap_amazon_all($matches[1]);
    if(preg_match('%^http://www.amazon.com.*/gp/product/(.*?)(/.*)?$%', $url, $matches))
        return scrap_amazon_all($matches[1]);
    if(preg_match('%^http://www.overstock.com.*/(\d+)/product.html%', $url, $matches))
        return scrap_overstock_all($url, $matches[1]);

    die("Unknown url $url");
}
