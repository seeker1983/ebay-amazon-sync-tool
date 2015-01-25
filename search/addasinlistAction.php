<?php
 session_start();
include('inc.db.php');
 
  $active_user = $_SESSION['user_id'];

include "simple_html_dom.php";
//$url="http://www.amazon.com/s/ref=nb_sb_noss_1?url=search-alias%3Delectronics&field-keywords=sleeping%20bag";
$url=$_POST['asin'];

$numberasins=$_POST['numberprod'];
$taburl=explode('/',$url);
$asin=$taburl[5];

$pattern = '/^B00/';

 $posamaz=preg_match($pattern,$asin, $matches, PREG_OFFSET_CAPTURE);
 if($numberasins==""&&$posamaz>0){
 
 $res=mysql_query("select * from asins_table where asins='".$asin."' and UserID=".$active_user."");
                    if(!mysql_num_rows($res))
                     {    
                      mysql_query("INSERT INTO asins_table(asins,UserID,processed,provider) values('".$asin."',".$active_user.",0,'Amazon')");
					 
                     }
	 $result = array("state"=>"Ok", "data"=>"");
                       echo json_encode($result);				 
 }
 else if($numberasins==""&&$posamaz==0){
 $result = array("state"=>"error", "data"=>"Please Enter number of products");
        echo json_encode($result);
        exit(200);
 }
 else {
scrape_asins($url,$numberasins);
$result = array("state"=>"Ok", "data"=>"");
echo json_encode($result);
}


function scrape_asins($url,$number) {

  $active_user = $_SESSION['user_id'];
  
$data=postForm($url);
  $html = str_get_html($data);
  $nb=0;
  $max=0;
  if($number){
  $max=$number;
  }

  $j=0;
 foreach($html->find('div[id=resultsCol]') as $bloc) {
  foreach($bloc->find('div[class=grid results  cols3]') as $item) {
     foreach($item->find('div') as $asins) {
        if($asins->name!=''&&$j<$max){
		 $res=mysql_query("select * from asins_table where asins='".$asins->name."' and UserID=".$active_user."");
        if(!mysql_num_rows($res))
        {   
		 $sql="INSERT INTO asins_table(asins,UserID,processed,provider) values('".$asins->name."',".$active_user.",0,'Amazon')";
		  mysql_query($sql)or die(mysql_error());
		}
		   $j++;
		}

 }
 }
 }
return true;
//return $result;


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


    curl_setopt($ch, CURLOPT_URL, $url);


    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

?>
