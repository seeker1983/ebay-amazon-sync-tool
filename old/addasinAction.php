<?php
require_once ('parsecsv.lib.php');

require_once ('Excel/reader.php');
session_start();
include('inc.db.php');
  $active_user = $_SESSION['user_id'];
  if(isset($_POST['upfile'])&&$_POST['upfile']!="") {

        $allowedExts = array("csv", "xls");
        $temp = explode(".",$_POST['upfile']);
        $extension = end($temp);
       
        if($extension=="csv") {
		 
            $csv = new parseCSV();
			$csv->auto('uploads/'.$_POST['upfile']);
			
            foreach ($csv->data as $key => $row):
               $asin=$row['asin'];
			   
	    $res=mysql_query("select * from asins_table where asins='".$asin."' and UserID=".$active_user."");
        if(!mysql_num_rows($res))
        {    
           mysql_query("INSERT INTO asins_table(asins,UserID,processed,provider) values('".$asin."',".$active_user.",0,'Amazon')");
        }	
				
				
            endforeach;
        }
        else

            if($extension=="xls") {

                $data = new Spreadsheet_Excel_Reader();
                //$data->setOutputEncoding('CP1251');
                $data->read('uploads/'.$_POST['upfile']);



                for ($i = 2; $i <=$data->sheets[0]['numRows']; $i++) {
                    $asin=rawurlencode($data->sheets[0]['cells'][$i][1]);
		          
					$res=mysql_query("select * from asins_table where asins='".$asin."' and UserID=".$active_user."");
                    if(!mysql_num_rows($res))
                     {    
                      mysql_query("INSERT INTO asins_table(asins,UserID,processed,provider) values('".$asin."',".$active_user.",0,'Amazon')");
                     }	        		
                }
            }
          $result = array("state"=>"Ok", "data"=>"");
                       echo json_encode($result);
	}
    else {
		$asin=rawurlencode($_POST['asin']);
                    $res=mysql_query("select * from asins_table where asins='".$asin."' and UserID=".$active_user."");
                    if(!mysql_num_rows($res))
                     {    
                      mysql_query("INSERT INTO asins_table(asins,UserID,processed,provider) values('".$asin."',".$active_user.",0,'Amazon')");
					  $result = array("state"=>"Ok", "data"=>"");
                       echo json_encode($result);
                     }
		
		else {
		$result = array("state"=>"error", "data"=>"Product already exist in the list");
        echo json_encode($result);
        exit(200);
		}

    }

