<?php
function postForm($url) {	
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
//include 'inc.db.php';
ini_set('display_errors',1);
ignore_user_abort(true);
error_reporting(E_ALL);
set_time_limit(0);
ini_set("memory_limit","-1");

$i = 1;
while(true){
$url = "http://www.amazon.com/Best-Sellers-Kitchen-Dining/zgbs/kitchen/ref=zg_bs_unv_k_1_289668_1&pg=".$i;
		
echo $url.'<br>';
$html = postForm($url);

preg_match_all('/(<div class="zg_title">)([^`]*?)<\/div>/',$html,$match);

foreach($match[2] as $top){
preg_match('/(dp\/)([^`]*?)(\/ref)/',$top,$match);
print $match[2]."<br/>";
}


$i++;

if($i == 6){break;}
}






?>