<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');
require_once('lib/scrape.php');
require_once('blocks/head.php');

require_once('lib/item.php');

if(!empty($_GET['action']) && !empty($_GET['id']))
{
  $item = new Item($_GET['id']);
    switch ($_GET['action']) {
      case 'drop':
          $response = $item->drop();
      break;
      
      case 'relist':
          $response = $item->relist();
      break;
      
      default:
        exit;
    }
}

show_response_errors($response);

if ($response->Ack != 'Failure') {
    $href = "/main.php?refresh";
    ?>
    <p>
    Item <?php echo $_GET['action'] ?> successful. Redirecting back to main page... <br>
    <a href="<?php echo $href; ?>"> Back... </a> 
    <script>
    setTimeout(function(){
      location.href="<?php echo $href; ?>";

    }, 500)
    </script>
    <?
}



