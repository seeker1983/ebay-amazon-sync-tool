<?php 
//include "simple_html_dom.php";

function scrape_aliexpress($itemid) {
$result=array();

$url="http://www.aliexpress.com/wholesale?SearchText=".$itemid;
//echo $url;
$data=getExpressPage($url);  
//echo $data;die;
$result['itemid']=$itemid;
$html = str_get_html($data);

foreach($html->find('h1[class=product-name]') as $title) {
$result['title']=$title->plaintext;
break;
}
$result['description']="";
foreach($html->find('div[id=custom-description] div[class=ui-box-body]') as $description) {
 $result['description'].=$description;
}
//echo $result['description'];die;

$url="http://www.aliexpress.com/item-img/-/".$itemid.".html";
$data=getPage($url);  
$htmlimg = str_get_html($data);

foreach($htmlimg->find('ul[class=new-img-border] li img') as $image) {
$result['imageurl']=$image->src;
break;
}
$size = getimagesize($result['imageurl']);

$height=$size[1];
$width=$size[0];


if($size[1]<500) {
$diff=500-$size[1];
$height=$size[1]+$diff;
$width=$size[0]+$diff;
}
$url =$result['imageurl'];

$extension_upload = strtolower(substr(strrchr($result['imageurl'],'.'),1));

$savedpict ='uploads/aliexpress/'.$itemid.'.'.$extension_upload;
    touch($savedpict); 
    chmod($savedpict, 0777); 
	
     $img =$savedpict;
    
	 file_put_contents($img, file_get_contents($url));
  	  
	  $thumb = new Imagick($img);
     
$thumb->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);

$thumb->writeImage($img);

$thumb->destroy();
$result['imageurl']='http://ezon.org/cl/ezonlister/'.$img;

//echo $result['imageurl'];die;


$i=0;
 foreach($html->find('ul[class=image-nav util-clearfix]') as $divimage) {
 
   foreach($divimage->find('li[class=image-nav-item]') as $liimg) {
      
	 foreach($liimg->find('img') as $img) {
 

   $result['pictures'.$i]=$img->src;

$result['pictures'.$i]=str_replace('_50x50.jpg','',$result['pictures'.$i]);

$i++;

}  
}
}

  //}

foreach($html->find('span[id=sku-price]') as $price) {
$result['price']=$price->plaintext;
break;
}

foreach($html->find('div[id=product-desc] div[class=ui-box-body]') as $features) {
$result['features']=$features;
break;
}

$qte=0;
foreach($html->find('dl[id=product-info-quantity] input[id=product-info-txt-quantity]') as $quantity) {

$qte=intval($quantity->value);
break;
}
if($qte>0){$result['quantity']=$qte;}
else {$result['quantity']=1;}

$result['prime']='Yes';

return $result;
}
function getExpressPage($url)
{
    $ch = curl_init();
    $header[0] = "Accept: text/xml,application/xml,application/json,text/javascript,application/xhtml+xml,";
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
    $header[] = "Cache-Control: max-age=0";
    $header[] = "Connection: keep-alive";
    $header[] = "Keep-Alive: 300";
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $header[] = "Accept-Language: en-us,en;q=0.5";
    $header[] = "Pragma: "; // browsers keep this blank.
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
   curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
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
  