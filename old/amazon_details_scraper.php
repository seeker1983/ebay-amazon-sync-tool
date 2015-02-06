<?php

class Amazon_Details_Scraper {

    private $progress_status;
    private $processed_details;

    public function __construct() {
        $this->progress_status = false;
        $this->processed_details = false;
		  
        require_once 'inc.db.php';
    }
	

    public function asins_from_source_file($file_path, $file_type) {
        //$public_key = "AKIAIAZ56753EOMICJHA";
        //$private_key = "V3JgOlg07LFs2Xu2jn7NudugbBs+MGO+Y6jEcaA2";

        require_once 'redirect.php';
        //require_once 'inc.db.php';
        require_once 'aws_signed_request.php';
        require_once 'xml_to_database_updater.php';
      

        $active_user = $_SESSION['user_id'];
		
        $asin_count = 0;

        if (file_exists($file_path) && is_readable($file_path)) {
            $read_fp = fopen($file_path, "r");
        } else {
            die("Error in Processing $file_path");
        }

        $xml_db_updater = new XML_to_Database_Updater();


        $bulk_count = 0;
        $asin_list = '';


        while (!feof($read_fp)) {
            if ($file_type) {
                $columns = fgetcsv($read_fp);
            } else {
                $buffer = fgets($read_fp);
                $columns = explode("\t", $buffer);
            }

            if (empty($columns))
                continue;

            foreach ($columns as $column) {

                if (preg_match("/^[0-9A-Z]{10}$/", trim($column))) {
                    $asin_count++;
                    $asin = trim($column);

                    $check_ups = "SELECT * FROM user_products WHERE SKU='$asin' AND UserID=$active_user";
                    $result_ups = mysql_query($check_ups) or die(mysql_error());
                    if (mysql_num_rows($result_ups))
                        continue;
                    
                    $check_ebay_asin = "SELECT * FROM ebay_asin WHERE asins='$asin' AND UserID=$active_user AND in_ebay=0";
                    $result_ebay_asin = mysql_query($check_ebay_asin) or die(mysql_error());
                    if (mysql_num_rows($result_ebay_asin))
                        continue;

                    $duplicate_product_sql = "SELECT asin FROM aws_asin WHERE asin='$asin' AND UserID=$active_user";
                    $dp_result = mysql_query($duplicate_product_sql) or die(mysql_error());
                    if (mysql_num_rows($dp_result))
                        continue;

                    $duplicate_asin_sql = "SELECT asins FROM asins_table WHERE asins='$asin' AND UserID=$active_user";
                    $dp_asin_result = mysql_query($duplicate_asin_sql) or die(mysql_error());
                    if (mysql_num_rows($dp_asin_result))
                        continue;

                    $insert_asin_sql = "INSERT INTO asins_table(UserID,asins) VALUES ($active_user,'$asin')";
                    mysql_query($insert_asin_sql) or die(mysql_error());
                    
                    /*
                      $bulk_count++;

                      if ($bulk_count == 1) {
                      $asin_list = trim($column);
                      } elseif ($bulk_count < 10) {
                      $asin_list .= "," . trim($column);
                      } elseif ($bulk_count == 10) {
                      $asin_list .= "," . trim($column);
                      $requestparams = array("Operation" => "ItemLookup", "Condition" => "All", "ItemId" => $asin_list, "IdType" => "ASIN", "Availability" => "Available",
                      "ResponseGroup" => "Large");

                      $product_xml = aws_signed_request("com", $requestparams, $public_key, $private_key);
                      $xml_db_updater->insert_into__aws_asin($product_xml);
                      sleep(5);
                      $asin_list = '';
                      $bulk_count = 0;
                      }
                     */
                }
            }
        }

        /*
          if ($bulk_count > 0) {
          $requestparams = array("Operation" => "ItemLookup", "Condition" => "All", "ItemId" => $asin_list, "IdType" => "ASIN", "Availability" => "Available",
          "ResponseGroup" => "Large");
          echo "Passing 1<br/>";

          $product_xml = aws_signed_request("com", $requestparams, $public_key, $private_key);
          $xml_db_updater->insert_into__aws_asin($product_xml);
          sleep(5);

          $asin_list = '';
          $bulk_count = 0;
          }

          if ($asin_count) {
          $processed_details = $xml_db_updater->get_inserted_asins() . " Out of " . $asin_count . "  ASINs Inserted ";
          if ($asin_count == 1)
          $processed_details = $xml_db_updater->get_inserted_asins() . " Out of " . $asin_count . "  ASIN Inserted ";
          $this->processed_details = $processed_details;
          $asin_count = 0;
          }

         */
  }

    public function details_scraping_source_database() {
       
//        $public_key_old_encrypt_sam = "BZWj+dU+DMlhS++qeRf78Jgtlk2kftXaBunDcXekHqI=";
//        $private_key_old_encrypt_sam = "sPLASMgc6JmawkVJFCV2cVSbNAjAa98dU3oIDlo8n+XTfSwEnojpVrva/e/QhBNbltnFDTmbdPKNnD+mxlJxpQ==";
//        $public_key = "AKIAIAZ56753EOMICJHA";
//        $private_key = "V3JgOlg07LFs2Xu2jn7NudugbBs+MGO+Y6jEcaA2";
          
        require_once 'redirect.php';
        //require_once 'inc.db.php';
        require_once 'aws_signed_request.php';
        require_once 'xml_to_database_updater.php';
           require_once 'scraping_asins.php';
		   require_once 'scraping_walmart.php';
		   require_once 'scraping_overstock.php';
		   require_once 'scraping_aliexpress.php';
		   

        $active_user = $_SESSION['user_id'];

        $xml_db_updater = new XML_to_Database_Updater();

        //----------------------------------------------------------------------------------
        $sql_ebay_users = "SELECT amazon_publickey,amazon_privatekey FROM ebay_users WHERE user_id=$active_user";

        $rs_ebay_users = mysql_query($sql_ebay_users) or die(mysql_error());

        if (mysql_num_rows($rs_ebay_users) != 1)
            die('Error in ebay_users');

        $row_user = mysql_fetch_array($rs_ebay_users);

        if ((strlen(trim($row_user['amazon_publickey'])) or !empty($row_user['amazon_publickey']) or !is_null($row_user['amazon_publickey'])) and (strlen(trim($row_user['amazon_privatekey'])) or !empty($row_user['amazon_privatekey']) or !is_null($row_user['amazon_privatekey']))
        ) {
            require_once 'functions.php';
            $public_key = encrypt_decrypt('decrypt',$row_user['amazon_publickey']);
            $private_key = encrypt_decrypt('decrypt',$row_user['amazon_privatekey']);
        }
        else
            header("Location:profile.php");
        //------------------------------------------------------------------------------------
       
        $sql_select = "SELECT * FROM asins_table WHERE processed = 0 AND UserID = $active_user ";
        $rs_select = mysql_query($sql_select) or die(mysql_error());
        //

        $asin_count = 0;
        $bulk_count = 0;
        $asin_list = '';

        if (!mysql_num_rows($rs_select)) {
            $progress_status = '<div id="ErrorMsg" class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								Sorry! No ASIN to Process...
					  </div>';
            //
            $this->progress_status = $progress_status;
            //
        } elseif (mysql_num_rows($rs_select)) {

            $progress_status = '<div id="ErrorMsg" class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								Fetching products on Process...
					  </div>';
            $this->progress_status = $progress_status;
            
            while ($row = mysql_fetch_array($rs_select)) {
              
                if (empty($row['asins']))
                   continue;// {die('Error in asins Records');}
                $asin = trim($row['asins']);
                $asin_count++;

                $duplicate_product_sql = "SELECT asin FROM aws_asin WHERE asin='$asin' AND UserID=$active_user";
                $dp_result = mysql_query($duplicate_product_sql);
                if (mysql_num_rows($dp_result))
                    continue;

                $bulk_count++;
                
               /* if ($bulk_count == 1) {
                    $asin_list = $asin;
                } elseif ($bulk_count < 10) {
                    $asin_list .= "," . $asin;
                } elseif ($bulk_count == 10) {
                    $asin_list .= "," . $asin;
                    $requestparams = array("Operation" => "ItemLookup", "Condition" => "All", "ItemId" => $asin_list, "IdType" => "ASIN", "Availability" => "Available",
                        "ResponseGroup" => "Large", "MerchantId" => "Amazon");
                        

                    $product_xml = aws_signed_request("com", $requestparams, $public_key, $private_key);
                        */
					
					if($row['provider']=='Walmart'){ 
					$product_data = scrape_walmart($asin);
					
					$xml_db_updater->insert_into__scrape_number($product_data);
					 
					}
                    else if($row['provider']=='Amazon') {					
					$product_data = scrape_asins($asin);
                    $xml_db_updater->insert_into__scrape_asin($product_data);
					
					}
                    else if($row['provider']=='Overstock') {
					
					$product_data =scrape_overstock($asin); 
				
                    $xml_db_updater->insert_into__scrape_overnumber($product_data);
					}
					else if($row['provider']=='Aliexpress') {
					
					$product_data =scrape_aliexpress($asin); 
				
                    $xml_db_updater->insert_into__scrape_aliexpress($product_data);
					}
					
					//print_r($product_data);die;
                    //
                    /*if (isset($product_xml->Error->Code)) {
                        $code_array = (array) $product_xml->Error->Code;
                        if (trim(strtoupper($code_array[0])) == 'INVALIDCLIENTTOKENID') {
                            echo '<script> window.location.href = "profile.php";</script>';
                            return;
                        }
                    }
                    */
                    
                  /*  sleep(5);
                    $asin_list = '';
                    $bulk_count = 0;*/
                } 
            }

           /* if ($bulk_count > 0) {

                $requestparams = array("Operation" => "ItemLookup", "Condition" => "All", "ItemId" => $asin_list, "IdType" => "ASIN", "Availability" => "Available",
                    "ResponseGroup" => "Large", "MerchantId" => "Amazon");

                $product_xml = aws_signed_request("com", $requestparams, $public_key, $private_key);
//                var_dump($product_xml);      exit('4444444444'); //mytest
                //
                if (isset($product_xml->Error->Code)) {
                    $code_array = (array) $product_xml->Error->Code;
                    if (trim(strtoupper($code_array[0])) == 'INVALIDCLIENTTOKENID') {
                        echo '<script> window.location.href = "profile.php";</script>';
                        return;
                    }
                }
                //
                
                $xml_db_updater->insert_into__aws_asin($product_xml);
                sleep(5);
                $asin_list = '';
                $bulk_count = 0;
            }*/
        }
      /*
        if ($asin_count) {
            $processed_details = $xml_db_updater->get_inserted_asins() . " Out of " . $asin_count . "  ASINs Inserted ";
            if ($asin_count == 1)
                $processed_details = $xml_db_updater->get_inserted_asins() . " Out of " . $asin_count . "  ASIN Inserted ";
            $this->processed_details = $processed_details;
            $asin_count = 0;
        }
		*/
   

    

    public function get_Processing_Result() {
        return $this->progress_status;
    }

}

?>
