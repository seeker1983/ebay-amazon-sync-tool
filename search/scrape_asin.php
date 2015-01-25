<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:index.php");
}

$active_user = $_SESSION['user_id'];


if (isset($_POST['amazon_sub'])) {
    include 'inc.db.php';
    ini_set('display_errors', 1);
    ignore_user_abort(true);
    error_reporting(E_ALL);
    set_time_limit(0);
    ini_set("memory_limit", "-1");

    $url_post = $_POST['amazon_url'];
    $noof_asin = $_POST['no_of_asin']; // if noof_asin = 2 ---> 2*20 asin (40) - per page 20 asins


    for ($i = 1; $i <= $noof_asin; $i++) {
        //$url = "http://www.amazon.com/Best-Sellers-Kitchen-Dining/zgbs/kitchen/ref=zg_bs_unv_k_1_289668_1&pg= .$i";
        $url = $url_post . '&pg=' . $i; // &pg= is for http fox url for pagination

        $html = postForm($url);


        $pattern = '/<input(.*?)id=\"ASIN\"(.*)value=\"(.*?)\"/i'; //have changed by David
        preg_match_all($pattern, $html, $matches); //have changed by David

        $asins = trim($matches[3][0]); //have changed by David

        $select = "SELECT * FROM asins_table WHERE asins = '$asins' AND UserID = $active_user";
        $rs_select = mysql_query($select);
        $count_select = mysql_num_rows($rs_select);

        if ($count_select == 0) {
            $insert = "INSERT INTO asins_table SET UserID = '{$active_user}', asins = '{$asins}', processed = 0"; //have changed by David
            mysql_query($insert);
        }

        $url = $url_post; // set to default url post

    }

    echo '<script>
			
        window.location.href = "add_asin.php";
						
    </script>';

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