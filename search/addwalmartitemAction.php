<?php 
session_start();
include "simple_html_dom.php";
include('inc.db.php');
  $active_user = $_SESSION['user_id'];
  $itemnumber2=$_POST['itemnumber'];
    $url="http://www.walmart.com/search/?query=". $itemnumber2;
                 $data=postForm($url);
          $html = str_get_html($data);
//echo $data;die;
  
 foreach($html->find('div[id=tile-container]') as $bloc) {
    foreach($bloc->find('div[class=js-tile tile-landscape] a[class=js-product-title]') as $item) {
        $itemlink=$item->href;
		$taburl=explode('/',$itemlink);
        $itemnumber=$taburl[count($taburl)-1];
		
		  $res=mysql_query("select * from asins_table where asins='".$itemnumber2."'-'".$itemnumber."' and UserID=".$active_user."");
                    if(!mysql_num_rows($res))
                     {$sql="INSERT INTO asins_table(asins,UserID,processed,provider) values('$itemnumber2-$itemnumber',".$active_user.",0,'Walmart')";    
                     
					  mysql_query($sql);
					  $result = array("state"=>"Ok", "data"=>"");
                       echo json_encode($result);
                     }
						else {
		$result = array("state"=>"error", "data"=>"Product already exist in the list");
        echo json_encode($result);
        exit(200);
		}
	
	   }
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