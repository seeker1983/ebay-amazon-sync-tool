<?php
 session_start();
include('inc.db.php');
include "simple_html_dom.php";
  $active_user = $_SESSION['user_id'];
//$url="http://www.overstock.com/search?refinebrand=HP&searchtype=Header";
$url=$_POST['itemnumber'];
$numberitems=$_POST['numberprod'];
// $numberitems=10;
$taburl=explode('/',$url);
$itemnumber=$taburl[count($taburl)-1];
preg_match_all('!\d+!', $itemnumber, $matches);
$itemnumber=$matches[0][0];

if($numberitems==""&&$itemnumber>0){
$res=mysql_query("select * from asins_table where asins='".$itemnumber."' and UserID=".$active_user."");
                    if(!mysql_num_rows($res))
                     {    
                      mysql_query("INSERT INTO asins_table(asins,UserID,processed,provider) values('".$itemnumber."',".$active_user.",0,'Aliexpress')");
					  $result = array("state"=>"Ok", "data"=>"");
                       echo json_encode($result);
                     }
}
elseif($numberitems==""&&$itemnumber==0){
 $result = array("state"=>"error", "data"=>"Please Enter number of products");
        echo json_encode($result);
        exit(200);
}
 else {
scrape_items($url,$numberitems);
$result = array("state"=>"Ok", "data"=>"");
echo json_encode($result);
}
function scrape_items($url,$number) {

  $active_user = $_SESSION['user_id'];
  
$data=postForm($url);
  $html = str_get_html($data);
//echo $data;die;
  $max=0;
  if($number){
  $max=$number;
  }

  $j=0;
 foreach($html->find('ul[id=hs-list-items]') as $bloc) {
    foreach($bloc->find('li div[class=img] a') as $item) {
       
		$url=$item->href;
		$tmp=explode('/',$url);
		
		$itemn=$tmp[count($tmp)-1];
		 preg_match_all('!\d+!', $itemn, $matches);
		 $itemnumber=$matches[0][0];
		
		 if($itemnumber!=''&&$j<$max){
		
		 $sql="INSERT INTO asins_table(asins,UserID,processed,provider) values('".$itemnumber."',".$active_user.",0,'Aliexpress')";
	
		  mysql_query($sql)or die(mysql_error());
		   $j++;
		}
	   } 
	   }  
	  foreach($html->find('div[id=hs-below-list-items]') as $bloc) {
	   
    foreach($bloc->find('li div[class=img] a') as $item) {
       
		$url=$item->href;
		$tmp=explode('/',$url);
		$itemn=$tmp[count($tmp)-1];
		
		 preg_match_all('!\d+!', $itemn, $matches);
		 $itemnumber=$matches[0][0];
		 
		 if($itemnumber!=''&&$j<$max){
		
		 $sql="INSERT INTO asins_table(asins,UserID,processed,provider) values('".$itemnumber."',".$active_user.",0,'Aliexpress')";
	
		  mysql_query($sql)or die(mysql_error());
		   $j++;
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
