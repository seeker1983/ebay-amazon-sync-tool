<?php

include "simple_html_dom.php";

include('inc.db.php');
//include('ebayFunctions.php');


$active_user = 8;
$sql = "SELECT * FROM ebay_users WHERE user_id = $active_user ";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);

$DEVNAME = trim($row['dev_name']);
$APPNAME = trim($row['app_name']);
$CERTNAME = trim($row['cert_name']);

//$token = "AgAAAA**AQAAAA**aAAAAA**rk05VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AElYCmCJiFqQqdj6x9nY+seQ**y4ICAA**AAMAAA**I+dnHHqjbr1rjZlo8wEbyge8d1Rj73Vd3La+W4FzvDVDYl4cLgvS2pw9bttZX9kkkFTpeRtt+UDuYxU9N6O3FpzN7OklgZaQKMud9sjKb6O3WQYICnjw2KdMlEaeBKrTTmyM31hEcOiTvJB5u/0U0zFLWXvk1Qk6YAPhqTGejzYiwQtqYGtmd0rGtoS+b+46XxRov3saL5cRdOfSSTcHlWY9mtIqWerFpM4ZP0Qbj6xM4azxUa0m0BIZKf70+EbSBoFOaVo4X2k5ysHvSII+1WTbVBNvqgp9PNvgfyoMs+VZZ77Q+Qlfl+kk1pmGJqgqUY854qv0gABzSV5OEbgHIS2v0K2OhcTbo3EgvDX1SbPr/MKGFmC2aeqrVbn/9x5XZKnwPdtqoDwYJKe3sU/tzonToUKfyqldIF1GbJlvh5lgd2dPv+WS29g0wNNKGtPDyrLmQ0cktF0Ymnx5zvT7xffOtE9HweCCCILJeqFgyS7XeBgSY4d4fXcxXtaMaK9c2HFjwnF4v2WKoJ9KYYT9svSsf3ksk4fGzEv6XVPonZQxvyZESJHxQ7SbR/KE0rAB1WZY8N8x0hMu999SEJEse4lYRP7RjVxx82AVZp6MUVExfHSMQcQQL8O5O6wgjkErAHpfd7CzOmaSeyy4HNzWq3liP0WGQCfVXFDGl2YunJL9stIKaA/SrMeOvx5CSAWYUwEnx4OUwKlPpeZot+x98I+6Q1i2K1Paf6jMM6X5j4HGIrtMHgCMqWfM8IkPU+5Q";
$token=trim($row['token']);

$itemid=$_GET['itemid'];

    $sql = "SELECT * FROM user_products WHERE ItemID = $itemid";
        $rs = mysql_query($sql);
         $row = mysql_fetch_array($rs);
        $item_url=$row['ItemUrl'];
        $ebay_price=$row['Price'];
        $quantity=$row['Qty'];
        $title=$row['Title'];
        $sku=$row['SKU'];
        $image_url=$row['Image'];
        //$sql = "DELETE FROM user_products WHERE ItemID = $itemid";
        //mysql_query($sql);
 
  ebay_item($itemid,$item_url,$ebay_price,$quantity,$title,$sku,$image_url);


function ebay_item($item_id,$item_url,$ebay_price,$quantity,$title,$sku,$image_url) {
 
        $active_user = 8;
        
		$sku=str_replace(' ','',$sku);
        
        
		 $pattern = '/^AMZ/';
       $posamaz=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
	    $pattern = '/^WM/';
		$poswm=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
        $pattern = '/^OS/';
		$posos=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
		$pattern = '/^AL/';
		$posal=preg_match($pattern,$sku, $matches, PREG_OFFSET_CAPTURE);
	     $asin="";
	   if($posamaz>0) {
	   $asin=str_replace('AMZ','',$sku);
	  
	   }
	     elseif($poswm>0) {
	   $asin=str_replace('WM','',$sku);
       //$tasin=explode('-',$asin);
	  // $asin=$tasin[0];
	  
	    
	   }  
	   elseif($posos>0){
	   $asin=str_replace('OS','',$sku);
	  
	   }
	   elseif($posal>0){
	   $asin=str_replace('AL','',$sku);
	   
	   
	   }
	   else{
	   $asin=$sku;
	   }
       
        $sql = "SELECT * FROM ebay_asin WHERE item_id = $item_id and UserID =$active_user";
        $rs = mysql_query($sql);
          
         if (mysql_num_rows($rs)) {
         $row = mysql_fetch_array($rs);
        
          $sql = "SELECT * FROM user_products WHERE ItemID = $item_id"; 
        $rs = mysql_query($sql);
		
		if (mysql_num_rows($rs)) {
		  if($posamaz>0) {
		  
		   $result=scrap_amazon($asin);
           $counter=1;
		   while($result['scrapok']==0&&$counter<=5){
           sleep(2);
           $result=scrap_amazon($asin);
           $counter++;
		   }
		   }
			elseif($poswm>0) {
			$result=scrap_walmart($asin);
			}
			 elseif($posos>0){
			 $result=scrap_overstock($asin);
			 }
			 elseif($posal>0){
			 $result=scrap_aliexpress($asin);
		     }
			 else {
			  $result=scrap_amazon($asin);
            
			 }
		//$azonprice=scrap_apiprice($asin);
		
		$amazon_price=$result['offerprice'];
		
		preg_match_all('/\d+(\.\d+)/',$amazon_price, $matches);
       if(isset($matches[0][0])){
         $amazon_price=$matches[0][0];
           }
		//$azonprice=substr(trim($azonprice),1,strlen(trim($azonprice)));
		
       $amazon_price=number_format((float)$amazon_price, 2, '.', '');
	    $ebay_price=number_format((float)$ebay_price, 2, '.', '');
		//$azonprice=number_format((float)$azonprice, 2, '.', '');
		
		$amazon_quantity=$result['quantity'];
		$max_quantity=$quantity ;
		$profit_ratio=($ebay_price*0.87)-$amazon_price;
		//echo $profit_ratio.'='.$ebay_price.'*0.87-'.$amazon_price;die;
		if($profit_ratio<=0) {
		$profit_ratio=5.00;
		}
        $title=str_replace("'","\'",$title);
        $insert = "UPDATE  user_products 
                   SET  UserID = $active_user, 
                         
                        Qty = $quantity, 
                        Price = $ebay_price, 
                        Title = '$title', 
                        SKU = '$sku', 
                        Image = '$image_url', 
                        ItemUrl = '$item_url', 
                        AmazonPrice = $amazon_price,	
                        AmazonQty = $amazon_quantity, 
                        MaxQty = $max_quantity,
                        ProfitRatio = $profit_ratio
						WHERE ItemID = $item_id";
        $update = " UPDATE ebay_asin 
                    SET quantity = $quantity
                    WHERE asins='$asin'
                    AND UserID=$active_user
        ";
   

        $delete = "DELETE ast,aws FROM 
                    asins_table as ast INNER JOIN 
                    aws_asin as aws
                    ON ast.asins = aws.asin
                    AND ast.UserID = aws.UserID 
                    WHERE ast.asins='$asin'
                    AND ast.UserID=$active_user
                    ";
	//	$deleteeb="DELETE FROM ebay_asin WHERE asins='$asin' and UserID=$active_user ";			
	    
        mysql_query($insert); 
        mysql_query($update);
        mysql_query($delete);  
      // mysql_query($deleteeb); 
	
  }
  
  }
  
  else {
                   
       
	    if($asin!="") {
	    
		  if($posamaz>0) {
		     $result=scrap_amazon($asin);
		   $counter=1;
		   while($result['scrapok']==0&&$counter<=5){
           sleep(2);
           $result=scrap_amazon($asin);
           $counter++;
		   }
		    }
			elseif($poswm>0) {
			$result=scrap_walmart($asin);
			}
			 elseif($posos>0){
			 $result=scrap_overstock($asin);
			 }
			 elseif($posal>0){
			 $result=scrap_aliexpress($asin);
		     }
			 else {
			 $result=scrap_amazon($asin);
			 }
		$amazon_price=$result['offerprice'];
		preg_match_all('/\d+(\.\d+)/',$amazon_price, $matches);
       if(isset($matches[0][0])){
         $amazon_price=$matches[0][0];
           }
		
		//$azonprice=substr(trim($azonprice),1,strlen(trim($azonprice)));
		
       $amazon_price=number_format((float)$amazon_price, 2, '.', '');
	    $ebay_price=number_format((float)$ebay_price, 2, '.', '');
		//$azonprice=number_format((float)$azonprice, 2, '.', '');
	   
		$amazon_quantity=$result['quantity'];
		$profit=($ebay_price*0.87)-$amazon_price;
		if($profit<=0){
		$profit=5.00;
		}
		 $sql = "SELECT * FROM ebay_asin WHERE asins='$asin'"; 
        $rs = mysql_query($sql);
		echo $asin;die;
		if (!mysql_num_rows($rs)) {
        $insert="INSERT INTO ebay_asin SET UserID=$active_user,asins='$asin',item_id=$item_id,ebay_title='$title',ebay_price= $ebay_price,amazon_price=$amazon_price,profit_ratio=$profit,quantity=$quantity,amazon_quantity=$amazon_quantity,max_quantity=$quantity,in_ebay=1,in_amazon=1";
         mysql_query($insert); 
		}
         $sql = "SELECT * FROM user_products WHERE SKU='$sku'"; 
        $rs = mysql_query($sql);
		if (!mysql_num_rows($rs)) {
		
		 $insert="INSERT INTO user_products SET UserID=$active_user,ItemID='$item_id',Qty=$quantity,Price=$ebay_price,Title='$title',SKU='$sku',Image='$image_url',ItemUrl='$item_url',AmazonPrice=$amazon_price,ProfitRatio=$profit,AmazonQty=$amazon_quantity,MaxQty=$quantity";
        mysql_query($insert); 
		
		}
	
  }
  }
  
  //sleep(1);
   unset($insert);	
	}
  	
  
      



function scrap_apiprice($asin){
$response=sign_query('ASIN',$asin,'Offers');
$listprice=simplexml_load_file($response);
$prime=$listprice->Items->Item->Offers->Offer->OfferListing->IsEligibleForSuperSaverShipping;
$price=0;
if($prime==1){
$amzprice=$listprice->Items->Item->Offers->Offer->OfferListing->SalePrice->FormattedPrice;

if(isset($amzprice)&&$amzprice!=""){
$price=$amzprice;
}
else{
$price=$listprice->Items->Item->Offers->Offer->OfferListing->Price->FormattedPrice;
}

}
return $price;
}

function scrap_price($asin){
$url="https://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new&condition=new";

$data=getPage($url);

$htmloffer = str_get_html($data);

$price=0;
if($htmloffer){

foreach($htmloffer->find('div[class=a-section a-spacing-double-large]') as $html1) {
  foreach($html1->find('div[class=a-row a-spacing-mini olpOffer]') as $html2) {
      foreach($html2->find('div[class=a-column a-span2]') as $html3) {
	    foreach($html3->find('span[class=a-size-large a-color-price olpOfferPrice a-text-bold]') as $html4) {
	     
		 $price1=$html4->plaintext;
         	
		}
        foreach($html3->find('span[class=supersaver]') as $html6) {
		return $price1;
		}
		
		 }
	   }
  	  }
	 unset($htmloffer);
	 unset($data);
	} 
	 return $price;
	 }


function scrap_amazon($asin) {
//$asin="B002KW3OQS";
$url="http://www.amazon.com/dp/".$asin;
//$url="http://www.amazon.com/gp/offer-listing/".$asin."/ref=dp_olp_new&condition=new";
$data=getPage($url);

  $html = str_get_html($data);
  $ok=0;
	   foreach($html->find('h1[id=title]') as $title) {	
       $ok=1;
	   
	 }
	 
	 if($ok==1){
	 $result['scrapok']=1;
	 }
	 else{
	 $result['scrapok']=0;
	 }
	 
	 $result['offerprice']=scrap_price($asin);
	 $counter=1;
      while($result['offerprice']==0&&$counter<=5){
      sleep(2);
	 $result['offerprice']=scrap_price($asin); 
	  $counter++;
	  }
	  

$qte=0;
 foreach($html->find('select[id=quantity]') as $quantity) {	
   foreach($quantity->find('option') as $opt) {	
$qte++;
 }
 }
 if($qte==0){
 foreach($html->find('div[id=availability] span') as $quantity) {	
 preg_match_all('/\d+(\.\d+)/',$quantity->plaintext, $matches);
 $qte=$matches[0][0];
 if(trim($quantity->plaintext)=='In Stock.')
 $qte=1;
 else 
 if(trim($quantity->plaintext)=='Currently unavailable.')
 $qte=0;
 break;
 }
 }
 $result['quantity']=$qte;
unset($html);
 unset($data);
 
return $result;
}

function scrap_walmart($itemid) {

$result=array();
$url="http://www.walmart.com/search/?query=".$itemid;


$data=PostForm($url);
 

$result['itemid']=$itemid;
  $htmlb = str_get_html($data);
  foreach($htmlb->find('h4[class=tile-heading] a') as $link){ 
    
   $url='http://www.walmart.com/'.$link->href;
   $data=PostForm($url);
  
   $html = str_get_html($data);
  break;
  }

 foreach($html->find('div[class=js-price-display price price-display price-display-oos-color]') as $price) {
$result['offerprice']=$price->plaintext;
break;
} 
  
foreach($html->find('div[class=js-price-display price price-display]') as $price) {
$result['offerprice']=$price->plaintext;
break;
}
$result['offerprice']=substr(trim($result['offerprice']),1,strlen($result['offerprice']));
$result['offerprice']=str_replace(" ","",$result['offerprice']);
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

$result['prime']="Yes";


return $result;
}

function scrap_overstock($itemid) {
$result=array();

$url="http://www.overstock.com/search/".$itemid;
//echo $url;
$data=postForm($url);
  $html = str_get_html($data);
  
$result['itemid']=$itemid;



foreach($html->find('span[class=price_sale main-price-red] span[class=Ovalue main-price-red]') as $price) {
$result['offerprice']=$price->plaintext;
break;
}

$qte=0;
foreach($html->find('div[id=addCartMain_quantity] select') as $select) {
   foreach($select->find('option') as $opt) {
       $qte++;
     }
   }
$result['quantity']=$qte;

$result['prime']='Yes';

return $result;

}

function scrap_aliexpress($itemid) {
$result=array();

$url="http://www.aliexpress.com/wholesale?SearchText=".$itemid;
//echo $url;
  $data=postForm($url);
  //echo $data;die;
  $html = str_get_html($data);
  $result['itemid']=$itemid;

preg_match("|<span.*?itemprop=\"price\".*?>(.*?)</span>|s", $html, $match_item_price);
						
						if(isset($match_item_price[1]))
						{
							$item_price =$match_item_price[1];
						}
$result['offerprice']=$item_price;

 $offerprice=explode('-',trim($result['offerprice']));
 $result['offerprice']='$'.$offerprice[0];

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
  //echo $url;die;
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

    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   /* $proxyfile = dirname(__FILE__)."/pro/pro.txt";
        $file = fopen($proxyfile, "r");
        $i = 0;
        while (!feof($file)) {
       $proxies_array[] = fgets($file);
}
      fclose($file); 
      $random_key   = array_rand($proxies_array);
$random_proxy   = $proxies_array[$random_key];
$random_proxy   = trim(str_replace("<br />","",$random_proxy)); 
   $new_proxy = explode(":", $random_proxy); 
   //$random_proxy="192.69.250.210:24084";   
  //$loginpassw = $new_proxy[2].":".$new_proxy[3];
   curl_setopt($ch, CURLOPT_PROXY, $random_proxy);
  */
    curl_setopt($ch, CURLOPT_URL, $url);
     $result = curl_exec($ch);
    $info = curl_getinfo($ch);
        curl_close($ch);
		
		if($info['http_code']!=200){$result=false;}
       // $result = ($info['http_code']!=200) ? false : $result;
		sleep(2);
        return $result;
}
function getPage($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 20 );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
    curl_setopt($ch, CURLOPT_TIMEOUT, 500);
    $ua = "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0";
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);

    $str = curl_exec($ch);
   
    curl_close($ch);
    sleep(2);

    return $str;

}


?>