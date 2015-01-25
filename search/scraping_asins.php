
  <?php
include "simple_html_dom.php";
require_once "phpuploader/smart_resize_image.function.php"; 
//scrape_asins('B00A850VEM');
function scrape_asins($asin) {

$result=array();

$url="http://www.amazon.com/dp/".$asin;

$data=postForm($url);
//echo $data;die;
  $html = str_get_html($data);
   
 foreach($html->find('h1[id=title] span[id=productTitle]') as $title) {
$result['title']=$title->plaintext;
break;
}
//echo $result['title'];die;
$result['asin']=$asin;


$result['description']="";

  $htmldesc=file_get_html($url);
foreach($htmldesc->find('div[class=productDescriptionWrapper]') as $description) {

$result['description']=$description->plaintext;
break;
}

$result['brand']="";
foreach($html->find('a[id=brand]') as $brand) {
$result['brand']=$brand->plaintext;
break; 
}

$result['features']="";
foreach($html->find('div[id=feature-bullets] ul[class=a-vertical a-spacing-none]') as $features)
 {
  foreach($features->find('li span') as $features_lines) 
   {
    $result['features'].='<li>'.$features_lines->plaintext.'</li>';
   }
 break;
 }
$result['imageurl']=""; 
foreach($html->find('div[id=imgTagWrapperId] img') as $image) {
$result['imageurl']=$image->src;

break;
}
$result['imageurl']=str_replace("._SY300_","",$result['imageurl']);

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

$savedpict ='uploads/amazon/'.$asin.'.'.$extension_upload;
    touch($savedpict); 
    chmod($savedpict, 0777); 
	
     $img =$savedpict;
    
	 file_put_contents($img, file_get_contents($url));
  	  
	 /* $thumb = new Imagick($img);
     
$thumb->resizeImage(800,800,Imagick::FILTER_LANCZOS,1);

$thumb->writeImage($img);

$thumb->destroy();*/
smart_resize_image($img ,null,$width,$height, false , $img , false , false ,100);
$result['imageurl']='http://ezon.org/cl/ezonlister/'.$img;
	 
//http://ecx.images-amazon.com/images/I/61bfvPIbrdL._SX700_.jpg
//$result['imageurl']=str_replace('SY300','SS700',$result['imageurl']);
//echo $result['imageurl'];die;
$result['listprice']="";
foreach($html->find('div[id=price] table[class=a-lineitem] td[class=a-span12 a-color-secondary a-size-base a-text-strike]') as $listprice) {
$result['listprice']=$listprice->plaintext;
break;
}
$url="http://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new?ie=UTF8&condition=new";
$data=postForm($url);
$htmloffer = str_get_html($data);

$result['offerprice']="";
$result['prime']="";
$prime=0;
$etat=0;
foreach($htmloffer->find('div[class=a-section a-spacing-double-large]') as $html1) {
  foreach($html1->find('div[class=a-row a-spacing-mini olpOffer]') as $html2) {
      foreach($html2->find('div[class=a-column a-span2]') as $html3) {
	    foreach($html3->find('span[class=a-size-large a-color-price olpOfferPrice a-text-bold]') as $html4) {
	     
		 $price=$html4->plaintext;
         	
		}
        foreach($html3->find('span[class=supersaver]') as $html6) {
	    $etat++;
		$prime=1;
		if($etat<=1){
		$lowestprice=$price;
		}
		 }
	   }
  	  }
	 }
	 
$result['offerprice']=$price;
if($prime>0){
$result['offerprice']=$lowestprice;
$result['prime']='Yes';
}
$result['quantity']="";
$qte=0;
 foreach($html->find('select[id=quantity]') as $quantity) {	
   foreach($quantity->find('option') as $opt) {	
$qte++;
 }
 }
 $result['quantity']=$qte;
 
$result['shippingprice']="";
foreach($html->find('div[id=price] table[class=a-lineitem] tr td[class=a-span12] span[id=ourprice_shippingmessage] span[class=a-size-base a-color-secondary]') as $shippingprice) {
$result['shippingprice']=$shippingprice->plaintext;
break;
}
$result['weight']="";
foreach($html->find('div[id=detail-bullets] td[class=bucket] div[class=content] ul') as $details) {
  foreach($details->find('li') as $weight) {
 
   if(strpos($weight->plaintext,'Weight'))
{
$result['weight']=$weight->plaintext;
break;
}
}
}
$str = $result['weight'];
preg_match_all('!\d+!', $str, $matches);
$pos1=strpos($result['weight'],$matches[0][0]);
$pos2=strpos($result['weight'],'(');
$pound=substr($result['weight'],$pos1+strlen($matches[0][0]),$pos2-18);
$result['weight']=$matches[0][0].' '.$pound;

$result['dimension']="";

foreach($html->find('div[id=detail-bullets] td[class=bucket] div[class=content] ul') as $details) {
  foreach($details->find('li') as $dimension) {
   if(strpos($dimension->plaintext,'Dimensions'))
{
$result['dimension']=$dimension->plaintext;
break;
}
}
}
$pos1=strpos($result['dimension'],':');

//$pos2=strpos($result['dimension'],'inches');

$dimension=substr($result['dimension'],$pos1+1,strlen($result['dimension']));
$dimension=str_replace('inches','',$dimension);
$result['dimension']=$dimension;
$result['mpn']="";
foreach($html->find('div[id=detail-bullets] td[class=bucket] div[class=content] ul') as $details) {
  foreach($details->find('li') as $mpn) {
   if(strpos($mpn->plaintext,'model'))
{
$result['mpn']=$mpn->plaintext;
break;
}
}
}
$pos1=strpos($result['mpn'],':');
$mpn=substr($result['mpn'],$pos1+1,strlen($result['mpn']));

$result['mpn']=$mpn;
$i=0;
 $result['pictures']="";
 $pictures=array();
 foreach($html->find('div[id=altImages] ul') as $blocthumb) {
   foreach($blocthumb->find('li img') as $liimg) {
    
$result['pictures'.$i]=$liimg->src;
$result['pictures'.$i]=str_replace('SS40','SS400',$result['pictures'.$i]);
$i++;
}
}
      $j=0;
	 
	  while(isset($result['pictures'.$j])) 
	  {
	  $pictures[]=$result['pictures'.$j];
	  $j++;
	  }
      $thumbpictures="";
	  if(count($pictures)>0) {
	  $thumbpictures=implode(',',$pictures);
	  }
	  
	   $result['pictures']=$thumbpictures;
 


return $result;


}  

function sign_query($param,$itemid,$response) {
    //sanity check
    if($param=='UPC') {
	
	$parameters = array( 'Operation'     =>'ItemLookup',
        'ResponseGroup' =>$response,
        'Condition'   =>'All',
		'SearchIndex'=>'All',
        'IdType'=>'UPC',
        'ItemId'=>$itemid,
		
    );
  }
  else if($param=='ASIN') {
  $parameters = array( 'Operation'     =>'ItemLookup',
        'ResponseGroup' =>$response,
        'Condition'   =>'All',
        'IdType'=>'ASIN',
        'ItemId'=>$itemid,
		
    );
  }
    if(! $parameters) return '';

    /* create an array that contains url encoded values
       like "parameter=encoded%20value"
       USE rawurlencode !!! */
    $encoded_values = array();
    foreach($parameters as $key=>$val) {
        $encoded_values[$key] = rawurlencode($key) . '=' . rawurlencode($val);
    }

    /* add the parameters that are needed for every query
       if they do not already exist */
    if(! $encoded_values['AssociateTag'])
        $encoded_values['AssociateTag']= 'AssociateTag='.rawurlencode('amazoninvento-20');
    if(! $encoded_values['AWSAccessKeyId'])
        $encoded_values['AWSAccessKeyId'] = 'AWSAccessKeyId='.rawurlencode('AKIAI7TLPEZHY2P3EUYA');
    if(! $encoded_values['Service'])
        $encoded_values['Service'] = 'Service=AWSECommerceService';
    if(! $encoded_values['Timestamp'])
        $encoded_values['Timestamp'] = 'Timestamp=2016-08-25T18%3A01%3A21.000Z';
    if(! $encoded_values['Version'])
        $encoded_values['Version'] = 'Version=2011-08-01';

    /* sort the array by key before generating the signature */
    ksort($encoded_values);


    /* set the server, uri, and method in variables to ensure that the
       same strings are used to create the URL and to generate the signature */
    $server = 'webservices.amazon.com';
    $uri = '/onca/xml'; //used in $sig and $url
    $method = 'GET'; //used in $sig


    /* implode the encoded values and generate signature
       depending on PHP version, tildes need to be decoded
       note the method, server, uri, and query string are separated by a newline */
    $query_string = str_replace("%7E", "~", implode('&',$encoded_values));
    $sig = base64_encode(hash_hmac('sha256', "{$method}\n{$server}\n{$uri}\n{$query_string}",'pyCL8svd2InFmpPgOgf9J6YXp2fDD6r5dB12EZCB', true));

    /* build the URL string with the pieces defined above
       and add the signature as the last parameter */
    $url = "http://{$server}{$uri}?{$query_string}&Signature=" . str_replace("%7E", "~", rawurlencode($sig));
 // $url="http://webservices.amazon.co.uk/onca/xml?AWSAccessKeyId=AKIAI7TLPEZHY2P3EUYA&AssociateTag=amazoninvento-20&Condition=All&IdType=UPC&ItemId=074182262549&Operation=ItemLookup&ResponseGroup=ItemAttributes&SearchIndex=All&Service=AWSECommerceService&Timestamp=2014-09-25T16%3A03%3A50.000Z&Version=2011-08-01&Signature=oeBhQ4Iqud%2BuCqiJIDlZte1q%2FWqR3h99AD5fLf9va5Q%3D";
  // echo $url;die;
  return $url;
	
}

function postForm($url)
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
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