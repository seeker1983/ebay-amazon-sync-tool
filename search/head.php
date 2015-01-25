<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta name="viewport" content="width=device-width"/>
        <title>Ebay Amazon Orders Management</title>
        	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" type="text/css" href="assets/fancyBox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
        <!-- include the core styles -->
		<link rel="stylesheet" href="assets/alertify/alertify.core.css" />
		<!-- include a theme, can be included into the core instead of 2 separate files -->
		<link rel="stylesheet" href="assets/alertify/alertify.default.css" />
		<link href="http://datatables.github.com/Plugins/integration/bootstrap/2/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
		<style type="text/css">
			  @import "assets/media/css/DT_bootstrap.css";
			  
				@import "assets/tableTool/media/css/TableTools.css";
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
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
        <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
		<script type="text/javascript" charset="utf-8" src="assets/media/js/jquery.dataTables.js"></script>
        <script src="assets/media/js/DT_bootstrap.js"></script>
        <script src="assets/media/js/FixedColumns.js"></script>
        <script src="assets/media/js/jquery.jeditable.js"></script>
        <script src="assets/media/js/jquery.validate.js"></script>
        <script src="assets/media/js/jquery.dataTables.editable.js"></script>
		<script src="assets/fancyBox/source/jquery.fancybox.js?v=2.1.5"></script>
		<script src="assets/alertify/alertify.min.js"></script>
		
                        <script type="text/javascript">

                            $(function()
                            {
                                $("[rel=tooltip]").tooltip();
                                $('.date_picker').datepicker({format: "yyyy-mm-dd"}).on('changeDate', function(ev)
                                {
                                    $(this).blur();
                                    $(this).datepicker('hide');
                                });
                                $('#ErrorMsg').fadeOut(3000);
                                $('#ResponceError').fadeOut(3000);
                                $('#uname').tooltip({'placement': 'bottom', 'trigger': 'hover'});
                                $('#upass').tooltip({'placement': 'bottom', 'trigger': 'hover'});
                            });

                            function checkAll()
                            {
                                var boxes = document.getElementsByTagName("input");
                                for (var i = 0; i < boxes.length; i++)
                                {
                                    myType = boxes[i].getAttribute("type");
                                    if (myType == "checkbox")
                                    {
                                        boxes[i].checked = 1;
                                    }
                                }
                            }
                            function checkNone()
                            {
                                var boxes = document.getElementsByTagName("input");
                                for (var i = 0; i < boxes.length; i++)
                                {
                                    myType = boxes[i].getAttribute("type");
                                    if (myType == "checkbox")
                                    {
                                        boxes[i].checked = 0;
                                    }
                                }
                            }

                            function preview_Template(text) {
                                var ebay_description = $('#' + text).val();

                                document.getElementById('preview_template').innerHTML = ebay_description;
                                $("#preview_template").attr("aria-hidden", "false");
                                //alert("Working 1");
                                var win = window.open(url, '_blank');
                                win.focus();
                            }


                            function show_opacity_div(text) {
                                var ebay_description = $('#' + text).val();
                                var html = '<div id="sod" style="width:700px; height: auto; z-index: 6; position: relative; top: 0px; left: 400px; padding: 50px; background: white; opacity: 0.75;float: left;margin-top: -605px;"><div style="background:url(\'images/close.png\');width: 50px;height: 50px;float: right;margin-top: -77px;margin-right: -75px;cursor:pointer;"  onClick="closeme()"></div>'+ebay_description+'</div>';
                                html += '<div id="sod_wrapper" style="width: 100%; height: 100%; position: fixed; left: 0; top: 0; background: black; opacity: 0.5; z-index: 5;"></div>';
                                document.body.innerHTML = document.body.innerHTML + html;

                            }
                            
                            function closeme(){
                                
                                $('div#sod').hide();
                                $('div#sod_wrapper').hide();
                                
                                
                            }
                            
                            function update_profit_ratio(profit_input){
                                var id = $(profit_input).attr('id');
                                var terms = id.split('_');
                                
                                var userID = terms[1];
                                var itemID = terms[2];
                                var asinID = terms[3];

                                var profitRatio = $(profit_input).val();

                                $.post("set_profit_ratio.php",{user_id:userID, ebay_item_id:itemID,
                                    asin:asinID,profit_ratio:profitRatio},
                                    function(data,status){
                                        var result_components = data.split(':');
                                        var result_size = result_components.length;

                                        if(result_size==2 && result_components[0]=='Failed'){
                                            var previousRatio = result_components[1];
                                            $(profit_input).val(previousRatio);
                                            alert('Failed');
                                        }else if(result_size==3 && result_components[0]=='Updated'){
                                            var updatedProfitRatio = result_components[1];
                                            var updatedEbayPrice = result_components[2];

                                            $("#ebayprice_"+itemID).html(updatedEbayPrice);
                                            $("#profitratio_"+itemID).html(updatedProfitRatio);
                                            alert('Updated');
                                        } else {
                                            alert(data);
                                        }

                                    });

                            }

                            function update_max_qty(max_input){
                                var id = $(max_input).attr('id');
                                var terms = id.split('_');
                                
                                var userID = terms[1];
                                var itemID = terms[2];
                                var asinID = terms[3];
                               
                                var maxQty = $(max_input).val();

                                $.post("set_max_qty.php",{user_id:userID, ebay_item_id:itemID,
                                    asin:asinID,max_qty:maxQty},
                                    function(data,status){
                                            var result_components = data.split(':');
                                        var result_size = result_components.length;

                                        if(result_size==2 && result_components[0]=='Failed'){
                                            var previousMaxQty = result_components[1];
                                            $(max_input).val(previousMaxQty);
                                            alert('Failed');
                                        }else if(result_size==3 && result_components[0]=='Updated'){
                                            var updatedMaxQty = result_components[1];
                                            var updatedQty = result_components[2];

                                            $("#quantity_"+itemID).html(updatedQty);
                                            $("#maxquantity_"+itemID).html(updatedMaxQty);
                                            alert('Updated');
                                        } else {
                                            alert(data);
                                        }
                                    });

                            }

                        </script>
                        </head>