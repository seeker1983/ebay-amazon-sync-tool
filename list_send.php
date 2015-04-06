<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/scrape.php');
require_once('lib/image/watermark.php');
require_once('blocks/head.php');
require_once('blocks/menu.php');
require_once('lib/ebay/upload_picture.php');
require_once('lib/ebay/item.php');

if(!isset($_POST['do']))
  Send404();

$item = $_POST;

$item['desc'] = str_replace(array('®','©'),'', $item['desc']);


foreach ($_POST['img'] as $img) {
    $blob = base64_decode(substr($img, strlen('data:image/jpeg;base64,')));
    print $img;
    $item['gallery'][] = ebay_upload_picture($item['keywords'], $blob);
}

//$item['gallery'] = array('http://i.ebayimg.sandbox.ebay.com/00/s/MzAwWDI2Mg==/z/MbcAAOSwX-hUzs2F/$_1.JPG?set_id=8800004005');
$item['verify'] = false;
//$item['test'] = true;


$response = Ebay::add_item($item);

show_response_errors($response);

if ($response->Ack !== 'Failure') {
    $href = $user['sandbox']? 'http://cgi.sandbox.ebay.com/ws/eBayISAPI.dll?ViewItem&item=' . $response->ItemID :
                              'http://www.ebay.com/itm/' . $response->ItemID;
    ?>
    <p>
    Item created successfully. Redirecting to ... <br>
    <a href="<?php echo $href; ?>"> <?php echo $href; ?> </a> 
    <script>
    setTimeout(function(){
      location.href="<?php echo $href; ?>";

    }, 3000)
    </script>
    <?
}



//xp($response);



