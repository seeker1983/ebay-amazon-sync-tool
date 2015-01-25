<?php

require_once 'redirect.php';
set_time_limit(0);
require_once 'amazon_details_scraper.php';
// A list of permitted file extensions
$allowed = array('csv', 'txt');

if (isset($_FILES['upl']) && $_FILES['upl']['error'] == 0) {

    $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

    if (!in_array(strtolower($extension), $allowed)) {
        echo '{"status":"error"}';
        exit;
    }

    if (move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/' . $_FILES['upl']['name'])) {
        $file_path = 'uploads/' . $_FILES['upl']['name'];
        //
        if($extension=='csv') $csv_enable = 1;
        elseif($extension=='txt') $csv_enable =0;
        
        $detail_scrapper = new Amazon_Details_Scraper();
        $detail_scrapper->asins_from_source_file($file_path, $csv_enable);
        //
        echo '{"status":"success"}';
        exit;
    }
}

echo '{"status":"error"}';
exit;