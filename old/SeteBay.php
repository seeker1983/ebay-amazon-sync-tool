<?php
session_start();
if(!isset($_SESSION['username'])) {
	header("Location:index.php");
}

include('inc.db.php');
require('ebayFunctions.php');

$active_user = $_SESSION['user_id'];
$session_user = $_GET['userid'];
$ru_name = 'Imara_Software_-ImaraSof-1464-4-bqvzwop';

	if($active_user == $session_user && $_GET['Setup'] == 'eBay') {
		
			$session = GetSessionID();
			$xml_session = simplexml_load_string($session);
			//print_r($xml_session);
			//return;
			
			if($xml_session->Ack == 'Success') {
					
				$session_id = $xml_session->SessionID;
					
				// sanbox url	
				$ebay_auth_url = 'https://signin.sandbox.ebay.com/ws/eBayISAPI.dll?SignIn&runame='.$ru_name.'&SessID='.$session_id.'&ruparams='.urlencode('isAuthSuccessful=true&blzsid='.$session_id.'&blzuid='.$active_user);
									
					//header('Location:'.$ebay_auth_url);
				echo '<script>  
							window.location.href = "'.$ebay_auth_url.'"
						
					  </script>';	
						
			}	
	
	}
	
	
	if($_GET['isAuthSuccessful'] == 'true')	{
					
					$fetch_token = FetchToken($_GET['blzsid']);
					$xml_token = simplexml_load_string($fetch_token);
					
					//print_r($xml_token);
					//return;
					
					if($xml_token->Ack == 'Success') {
						
						$token = $xml_token->eBayAuthToken;
						//$token = encrypt_decrypt('encrypt', $token);
						$exp_time = $xml_token->HardExpirationTime;
						
						$update = "UPDATE ebay_users SET eBayReady = 'Yes', token = '$token', Token_exp_date = '$exp_time' WHERE user_id = '".$_GET['blzuid']."' ";
						 $rs = mysql_query($update);
						 
						 if($rs) {
							echo '<script>  
										window.location.href = "index.php"
						
					  			</script>'; 
						 }
											
				
					}
				
	}

	
	

?>
