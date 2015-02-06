<?php 


function scrape_overstock($itemid) {
$result=array();

$url="http://www.overstock.com/search/".$itemid;
//echo $url;
$data=getPage($url);  
$result['itemid']=$itemid;
$html = str_get_html($data);

foreach($html->find('div[id=prod_mainCenter] h1') as $title) {
$result['title']=$title->plaintext;
break;
}

foreach($html->find('ul[class=bulleted-list]') as $description) {
$result['description']=$description->plaintext;
break;
}
 $result['description']=str_replace('more','',$result['description']);

foreach($html->find('div[id=proImageHero] div[class=proImageStack] img') as $image) {
$result['imageurl']=$image->src;
break;
}

$result['imageurl']=str_replace('_320','_1000',$result['imageurl']);

$i=0;
 foreach($html->find('div[id=proImageHero]') as $divimage) {
   //echo $divimage;die;
   
      foreach($divimage->find('img') as $img) {
   
$result['pictures'.$i]=$img->src;
$result['pictures'.$i]=str_replace('_320','_80',$result['pictures'.$i]);
$i++;
}

}

  //}

foreach($html->find('span[class=price_sale main-price-red] span[class=Ovalue main-price-red]') as $price) {
$result['price']=$price->plaintext;
break;
}

$qte=0;
foreach($html->find('div[id=addCartMain_quantity] select') as $select) {
   foreach($select->find('option') as $opt) {
       $qte++;
     }
   }
$result['quantity']=$qte;

foreach($html->find('ul[id=details_descFull]') as $features) {
$result['features']=$features;
break;
}

foreach($html->find('div[class=product-brand-name] a') as $brand) {
$result['brand']=$brand->plaintext;
break;
}
$result['prime']='Yes';
return $result;
}
function getPage($url)
{
    $ch = curl_init();
    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Pragma: "; // browsers keep this blank.
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
   // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt($ch, CURLOPT_URL, $url);
     $data = curl_exec($ch); 
	
    return $data;
}

?>