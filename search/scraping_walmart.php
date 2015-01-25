
  <?php
//include "simple_html_dom.php";
//scrape_walmart(38043712);
//scrape_walmart(551523928);
function scrape_walmart($itemid) {

$result=array();

$itemidtab=explode('-',$itemid);
$itemid1=$itemidtab[0];
$url="http://www.walmart.com/search/?query=".$itemid1;


$data=getData($url);
 

$result['itemid']=$itemid;
  $htmlb = str_get_html($data);
  foreach($htmlb->find('h4[class=tile-heading] a') as $link){ 
    
   $url='http://www.walmart.com/'.$link->href;
   $data=getData($url);
  
   $html = str_get_html($data);
  break;
  }
  
foreach($html->find('h1[class=heading-b product-name product-heading js-product-heading]') as $title) {
$result['title']=$title->plaintext;
break;
}
//echo $result['title'];die;
foreach($html->find('div[class=js-ellipsis module]') as $description) {
$result['description']=$description->children(0)->plaintext;

break;
}
$result['features']="";
foreach($html->find('div[class=js-ellipsis module] ul') as $description) {
   foreach($description->find('li') as $features) {
   $result['features'].=$features;

}
}

//echo $result['features'];die;

$result['imageurl']=""; 
foreach($html->find('img[class=product-image js-product-image js-product-primary-image]') as $image) {
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


$savedpict ='uploads/walmart/'.$itemid.'.'.$extension_upload;
    touch($savedpict); 
    chmod($savedpict, 0777); 
	
     $img =$savedpict;
    
	 file_put_contents($img, file_get_contents($url));
  	  
	  $thumb = new Imagick($img);
       
$thumb->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);

$thumb->writeImage($img);

$thumb->destroy();
$result['imageurl']='http://ezon.org/cl/ezonlister/'.$img;

foreach($html->find('div[class=js-price-display price price-display]') as $price) {
$result['price']=$price->plaintext;
break;
}

$result['quantity']=0;
$qte=0;
foreach($html->find('div[class=col6 form-inline form-inline-mini]') as $quantity) {

  foreach($quantity->find('select') as $select) {
	 foreach($select->find('option') as $opt) {
       $qte++;
 }
 }
 }
$result['quantity']=$qte;

$i=0;
foreach($html->find('div[class=product-carousel-wrapper] ol[class=product-thumbs js-carousel-items animate-vertical]') as $images) {
   foreach($images->find('li') as $liimages) {
      foreach($liimages->find('a') as $thumb) {
       $result['pictures'.$i]=$thumb->href;       
      $i++;
   }
 }
}

$result['prime']="Yes";

return $result;


}  


function getData($url)
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
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    curl_setopt($ch, CURLOPT_URL, $url);
     $data = curl_exec($ch); 
	
    return $data;
}

?>