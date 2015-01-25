<?php
require_once 'redirect.php';
set_time_limit(0);
//error_reporting(0);

require_once('head.php');
require_once 'amazon_details_scraper.php';

$active_user = $_SESSION['user_id'];

$database_scraper = new Amazon_Details_Scraper();
$database_scraper->details_scraping_source_database();
$msg = $database_scraper->get_Processing_Result();

?>

<body>
    <div class="page-header" style="background-color:#000000;">
        <h2 style="text-align:center; color:#FFFFFF; margin-top:0px; padding-top:20px; font-family:Georgia, 'Times New Roman', Times, serif;">Ebay - Amazon Tool</h2>
        <p style="color:#FFF; margin:auto; text-align:center; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;"><?php
            echo 'Hello ';
            if (isset($_SESSION['username'])) {
                ?>
                <strong><a href="profile.php"><?php echo $_SESSION['username']; ?></a></strong>
                <?php
            } else {
                ?>
                <strong><a href="profile.php"><?php echo 'Admin !'; ?></a></strong>
                <?php
            }
            ?> | <a href="logout.php" style="color:#FFFFFF; font-weight:bold;">Logout</a></p>
    </div>
    <div class="navbar">
        <div class="navbar-inner"> <a class="brand" href="dashboard.php">Ebay - Amazon Tool</a>
        </div>
    </div>

    <div id="ShowResults" style="margin:auto; width:98%;">
        <div style="height:50px;">
            <a href="add_asin.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-info" type="button">Add Asin</button></a>  
            <a href="grab_amazon.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-success disabled" data-loading-text="Loading..." type="button">Fetch Asin Details</button></a>
            <a href="ebay_edit.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-inverse" type="button">Edit Ebay Settings</button></a>
            <a href="send_to_ebay.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-danger" type="button">Add to Ebay</button></a>
            <a href="view_ebay_data.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-warning" type="button">View Ebay Listings</button></a>

        </div>
        <div style="clear:both;"></div>
        <?php
    if (isset($msg)) {
        echo $msg;
    }
    ?>
    </div>

    <div>
      <?php $htmlTable  = '
		
       <div class="btn-group">
				<button id="clear" class="btn btn-warning">Clear fetch table</button>
			</div>
			<br>
			<br>
		 <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="usersTable">
            <thead>
              <tr>
				<th class="span5">Title</th>
				<th class="span2">Brand</th>
				<th class="span3">Features</th>
				<th class="span3">Description</th>
				<th class="span3">Image</th>
				<th class="span2">Price</th>
				<th class="span2">Prime</th>
				<th class="span2">Quantity</th>
				
				
				
                
			  </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <br>
		<br>
	  
		  <br>
		  <br>
		  <script>
		  $(".fancybox").fancybox();
            
			var aSelected = [];
			var oTable;
            defaultEditable =  {
                tooltip: "Click to edit field",
                onblur: "cancel",
                submit:"Save changes",
                cssclass: "required",
                fnOnCellUpdated: function(sStatus, sValue, settings){
                    alert("(Cell Callback): Cell is updated with value " + sValue);
                }
            };
	         oTable = $("#usersTable").dataTable({
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
						if ( jQuery.inArray(aData.DT_RowId, aSelected) !== -1 ) {
							$("td:eq(0)", nRow).parent().addClass("my_row_selected");
						}
				},
                "aoColumnDefs": [
                    { "sClass": "update_class", "aTargets": [ 0,1,2,3,4,5,6,7 ] },
                ],
                "fnDrawCallback": function () {
                    $("td.update_class").editable( "UpdateProduct.php", {
                        "callback": function( sValue, y ) {
                            var aPos = oTable.fnGetPosition( this );
                            oTable.fnUpdate( sValue, aPos[0], aPos[1] );
                            if(sValue=="Ok") {
                                    //alertify.success("User updated successfully");
                                    oTable.fnDraw(false);
                                } else {
                                    //alertify.error(sValue);
                                }
                        },
                        "submitdata": function ( value, settings ) {
                            
                            return {
                                "row_id": this.parentNode.getAttribute("id"),
                                "column": oTable.fnGetPosition( this )[2]
                            };
                        },
                        "aoColumns": [
                            null,
                            $.extend({ }, defaultEditable, { indicator: "Saving Username..." }),
                            $.extend({ }, defaultEditable, { indicator: "Saving Email @..." }),
                            null,
                        ],
                        "height": "20px"
                    } );
                },
                "bProcessing": true,
                "bServerSide": true,
                 "sAjaxSource": "server_processing_products.php",
				"bCustomFilter":true,
				"aaSorting": [[ 3, "desc" ]],
				"sScrollX": "99%",
				"bAutoWidth": false,
				"sScrollY": "500px",
				"bScrollCollapse": true,
				"bPaginate": true,
			})
            // .makeEditable({
                    // sUpdateURL: "UpdateProduct.php",
                    // SelectedRowClass: "fake",
                    // "aoColumns": [
                        // null,
                        // $.extend({ }, defaultEditable, { indicator: "Saving Username..." }),
                        // $.extend({ }, defaultEditable, { indicator: "Saving Email @..." }),
                        // null,
                    // ],
                    // });
                    
			var ua = navigator.userAgent,
             event = (ua.match(/iPad/i)) ? "touchstart" : "click";
             $("#usersTable_wrapper table tbody tr").live(event, function () {
                    var id = this.id;
                    var index = jQuery.inArray(id, aSelected);
                    if ( index === -1 ) {
                        aSelected.push( id );
                    } else {
                        aSelected.splice( index, 1 );
                    }
                    // alert(aSelected);
                    if(aSelected.length>0){
                        $("#menu").show();
                    } else {
                        $("#menu").hide();
                    }
                    $(this).toggleClass("my_row_selected");
                } );
                $("#uncheck").live(event, function (ev) {
					alertify.confirm("This will clear all previous selections. Click &quot;OK&quot; to continue.", function (e) {
						if (e) {
							// user clicked "ok"
							aSelected = [];
							oTable.fnDraw(false);
							$("#menu").hide();
							alertify.success("Selection cleared successfully.");
						}
						 else {
							// user clicked "cancel"
							alertify.error("You hit Cancel Button");
						}
					})
				});
                $("#selection").live(event, function (ev) {
					ev.preventDefault();
					var target = aSelected;
					alertify.confirm("Delete selected users", function (e) {
					if (e) {
						// user clicked "ok"
						$.ajax({
							url    : "../Controllers/dashboardRemoveUserAction.php",
							type   : "POST",
							datType: "html",
							data   : "userArray="+target+"&action=removeSelection",
							beforeSend: function() {
								alertify.log("Removing token, please be patient...", "", 3000);
							}, 
							success : function( result ) {
                                if(result=="Ok") {
                                    alertify.success("Selected users deleted successfully");
                                    aSelected = [];
                                    oTable.fnDraw(false);
                                } else {
                                    alertify.error(result);
                                }
							},
							error: function() {
								var msg = "Sorry but an error has occurred; Try again.";
								alertify.error(msg);
							}
						}); 
					} else {
						// user clicked "cancel"
						alertify.error("Deletion was cancelled.");
					}
				})
				})
				 $("#clear").live(event, function(ev){
      ev.preventDefault();
	 
	    
	     
		 alertify.confirm("Clear Search ?", function (e) {
                if (e) {
                    // user clicked "ok"
                    $.ajax({
                        url    : "clearfetchAction.php",
                        type   : "POST",
                        dataType: "json",
                        //data   : "cost="+cost+"&asin="+asin,
                        beforeSend: function() {
                           alertify.log("Checking provided data...", "", 3000);
                            
                        }, 
                        success : function( result) {
                            if(result["state"]=="Ok") {
                                alertify.success("List cleared successfully");
                                oTable.fnDraw();
                            } else {
                                alertify.error(result["data"]);
                                 oTable.fnDraw();
								 }
                        },
                        error: function() {
                            var msg = "Sorry but an error has occurred; Try again.";
                            alertify.error(msg);
                            }
                    }); 
                } else {
                    // user clicked "cancel"
                    alertify.error("Action was cancelled.");
                }
            
        })
       

			})
				
                $(document).ready(function() {
                     $("#menu").hide();
                })
			
			</script>
			
			';
		 echo $htmlTable;
?>
    </div>

    <!--script>window.location.reload();</script-->

    <?php
   $sql_asin = "UPDATE asins_table SET processed=1 WHERE UserID=$active_user";

     mysql_query($sql_asin) or die(mysql_error());
    ?>