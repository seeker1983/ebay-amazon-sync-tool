<?
if (!isset($_SESSION['user_id']) && !preg_match('%index.php$%', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) )) 
{
    header("Location:index.php");
    exit;
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="viewport" content="width=device-width"/>
        <title>Ebay Amazon Orders Management</title>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
         <link href="css/bootstrap-combined.min.css" rel="stylesheet" type="text/css" />
 		<!-- <link rel="stylesheet" type="text/css" href="assets/fancyBox/source/jquery.fancybox.css?v=2.1.5" media="screen" /> -->
        <!-- include the core styles -->
		<!-- <link rel="stylesheet" href="assets/alertify/alertify.core.css" /> -->
		<!-- include a theme, can be included into the core instead of 2 separate files -->
		<!-- <link rel="stylesheet" href="assets/alertify/alertify.default.css" /> -->
		<link href="css/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			  /*@import "assets/media/css/DT_bootstrap.css";*/
			  
				/*@import "assets/tableTool/media/css/TableTools.css";*/
				table.table thead .sorting,
				table.table thead .sorting_asc,
				table.table thead .sorting_desc,
				table.table thead .sorting_asc_disabled,
				table.table thead .sorting_desc_disabled {
					cursor: pointer;
					*cursor: hand;
				}
				 
				table.table thead .sorting { background: url('assets/media/images/sort_both.png') no-repeat center right; }
				table.table thead .sorting_asc { background: url('assets/media/images/sort_asc.png') no-repeat center right; }
				table.table thead .sorting_desc { background: url('assets/media/images/sort_desc.png') no-repeat center right; }
				 
				table.table thead .sorting_asc_disabled { background: url('assets/media/images/sort_asc_disabled.png') no-repeat center right; }
				table.table thead .sorting_desc_disabled { background: url('assets/media/images/sort_desc_disabled.png') no-repeat center right; }
				.odd {
				font-size:12px;
				height:10px;
				}
			.even {
				font-size:12px;
				height:10px;
				}
				
			
				table  {
				
					height:30px !important;
					table-layout: fixed; // ***********add this
					word-wrap:break-word; // ***********and this 
					-ms-word-break: break-all;
					-ms-word-wrap: break-all;
					-webkit-word-break: break-word;
					-webkit-word-wrap: break-word;
					word-break: break-word;
					word-wrap: break-word;
					-webkit-hyphens: auto;
					-moz-hyphens: auto;
					hyphens: auto; 
				}	
				.fancybox-custom .fancybox-skin {
					box-shadow: 0 0 50px #222;
				}
				.tooltip {
					overflow: auto !important;
					position: fixed;
				}
				.tooltip-inner {
					background-color : #D6D630 !important;
					color : #000033 ;
					width:400px !important;
					white-space:pre-wrap;
					max-width:none;
				}
                .row_selected {
					background-color:#FFF973  !important;
				}input {
					min-height:30px !important;
				}
		</style>
        <script src="js/jquery-2.1.3.min.js"></script>
        <script src="js/jquery-plugins.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/main.js"></script>
        
</head>                    