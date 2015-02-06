<?php 
require_once ('lib/config.php');
require_once('blocks/head.php');

require_once "old/phpuploader/include_phpuploader.php";
require_once "old/phpuploader/smart_resize_image.function.php" ;
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
            <a href="add_asin.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-info disabled" type="button">Add Asin</button></a>  
            <a href="grab_amazon.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-success" data-loading-text="Loading..." type="button">Fetch Asin Details</button></a>
            <a href="ebay_edit.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-inverse" type="button">Edit Ebay Settings</button></a>
            <a href="send_to_ebay.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-danger" type="button">Add to Ebay</button></a>
            <a href="view_ebay_data.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-warning" type="button">View Ebay Listings</button></a>

        </div>
        <div style="clear:both;"></div>
	
    
<div class="container-fluid span14" style="margin-top: 0px">
	
    <div class="row-fluid">
		
      <section id="global" class="span14">
          
			<div class="row-fluid">
		
        <section id="global" class="span12">
		<fieldset class="form-horizontal">
		<legend>Amazon Fetching Product </legend>

 <div class="control-group">
                  <label class="control-label" for="searchField">Upload from</label>
                  <div class="controls">
                  <?php
			$uploader=new PhpUploader();
			
			$uploader->MultipleFilesUpload=false;
			$uploader->InsertText="Upload File (Max 10M)";
			
			$uploader->MaxSizeKB=1024000;	
			$uploader->AllowedFileExtensions="xls,csv";
			
			//Where'd the files go?
			$uploader->SaveDirectory="uploads";
			
			$uploader->Render();?>
                  </div>
                </div>
			</fieldset>	
            <form id="amazonForm" class="form-horizontal" action="" method="post">
                <fieldset>

                <!-- Form Name -->
                

                <!-- Text input-->

				
				<div class="control-group">
                  <label class="control-label" for="searchField">Asin Or Amazon Url</label>
                  <div class="controls">
                    <input id="asinID" name="asin" placeholder="enter Asin Code" class="input-large" type="text">
				    <input id="filename" name="upfile" type="hidden" value="">
					
                  </div>
                </div>
					<div class="control-group">
                  <label class="control-label" for="searchField">Number of products</label>
                  <div class="controls">
                    <input id="nbprodamaz" name="numberprod" placeholder="enter Number of products" class="input-medium" type="text">
				    
                  </div>
                </div>
               
    <div class="control-group">
                  <div class="controls">
                    <button type="submit" id="searchWord" name="searchWord" class="btn btn-primary">upload</button>
                  </div>
                </div>
        </fieldset>
   </form>
       <form id="walmartForm" class="form-horizontal" action="" method="post">
               <fieldset class="form-horizontal">
		<legend>Walmart Fetching Product  </legend>
                

                <!-- Text input-->

				
				<div class="control-group">
                  <label class="control-label" for="searchField">Walmart Item Number or Walmart Url</label>
                  <div class="controls">
                    <input id="itemID" name="itemnumber" placeholder="enter Walmart url" class="input-large" type="text">
				    
                  </div>
                </div>
				<div class="control-group">
                  <label class="control-label" for="searchField">Number of products</label>
                  <div class="controls">
                    <input id="nbprodwalmart" name="numberprod" placeholder="enter Number of products" class="input-medium" type="text">
				    
                  </div>
                </div>
               
    <div class="control-group">
                  <div class="controls">
                    <button type="submit" id="searchWord" name="searchWord" class="btn btn-primary">Search</button>
                  </div>
                </div>
        </fieldset>
   </form>
     <form id="overstockForm" class="form-horizontal" action="" method="post">
               <fieldset class="form-horizontal">
		<legend>OverStock Fetching Product  </legend>
                

                <!-- Text input-->

				
				<div class="control-group">
                  <label class="control-label" for="searchField">OverStock Item Number or OverStock Url</label>
                  <div class="controls">
                    <input id="overitemid" name="itemnumber" placeholder="enter Overstock url" class="input-large" type="text">
				    
                  </div>
                </div>
				<div class="control-group">
                  <label class="control-label" for="searchField">Number of products</label>
                  <div class="controls">
                    <input id="nbprodover" name="numberprod" placeholder="enter Number of products" class="input-medium" type="text">
				    
                  </div>
                </div>
               
    <div class="control-group">
                  <div class="controls">
                    <button type="submit" id="searchWord" name="searchWord" class="btn btn-primary">Search</button>
                  </div>
                </div>
        </fieldset>
   </form>
   
    <form id="aliexpressForm" class="form-horizontal" action="" method="post">
               <fieldset class="form-horizontal">
		<legend>Aliexpress Fetching Product  </legend>
                

                <!-- Text input-->

				
				<div class="control-group">
                  <label class="control-label" for="searchField">Aliexpress Item Number or Aliexpress Url</label>
                  <div class="controls">
                    <input id="expressitemid" name="itemnumber" placeholder="enter Aliexpress url" class="input-large" type="text">
				    
                  </div>
                </div>
				<div class="control-group">
                  <label class="control-label" for="searchField">Number of products</label>
                  <div class="controls">
                    <input id="nbprodexpress" name="numberprod" placeholder="enter Number of products" class="input-medium" type="text">
				    
                  </div>
                </div>
               
    <div class="control-group">
                  <div class="controls">
                    <button type="submit" id="searchWord" name="searchWord" class="btn btn-primary">Search</button>
                  </div>
                </div>
        </fieldset>
   </form>
        
        </section>  
		
    </div>
  
                </div>
	
                 
	<div id="firstTable">
	<div class="btn-group">
				<button id="clear" class="btn btn-warning">Clear search</button>
			</div>
	<?php include('amazonasins.php'); ?>
	</div>
</div>
	</div>		
	<div class="spacer"></div>

<script>
//var ua = navigator.userAgent,
  //   event = (ua.match(/iPad/i)) ? "touchstart" : "click";
$("#amazonForm").bind("submit", function(ev){
			ev.preventDefault();
			var str=$("#asinID").val();  
			//var nbprod=$("#nbprodamaz").val();
			
			// alert(username);
			
					alertify.confirm("Get list products?", function (e) {
						if (e) {
						 var data = $("#amazonForm").serialize();
						  if(!ValidUrl(str)) {
							var url="addasinAction.php";
							}
							else {
							var url="addasinlistAction.php";
							}
							$.ajax({  
								url    :url,
								type   : "POST",
								dataType: "json",  
								data   : data,
								beforeSend: function() {
								  
									alertify.log("Checking provided data...", "", 3000);
								}, 
								success : function( result ) {
									if(result["state"]=="Ok") {
										      oTable.fnDraw();                                
                                          //$("#firstTable").html(result["data"]);                                              
										alertify.success("Data displayed successfully.");
                                                              
                                                                               
									} else {
										alertify.error(result["data"]);
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
						
						// form.submit();
					});
				
			})
			
					 $("#clear").live(event, function(ev){
      ev.preventDefault();
	 
	    
	     
		 alertify.confirm("Clear Search ?", function (e) {
                if (e) {
                    // user clicked "ok"
                    $.ajax({
                        url    : "clearAction.php",
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
			
		$("#walmartForm").bind("submit", function(ev){
			ev.preventDefault();
			var str=$("#itemID").val();
			var nbprod=$("#nbprodwalmart").val();
			  
     			alertify.confirm("Get Walmart products?", function (e) {
					
						if (e) {
							var data = $("#walmartForm").serialize();
						  if(!ValidUrl(str)) {
							var url="addwalmartitemAction.php";
							}
							else {
							var url="addwalmartlistitemAction.php";
							}
						
							// user clicked "ok"
							
							
							$.ajax({
								url    : url,
								type   : "POST",
								dataType: "json",  
								data   : data,
								beforeSend: function() {
								  
									alertify.log("Checking provided data...", "", 3000);
								}, 
								success : function( result ) {
									if(result["state"]=="Ok") {
										      oTable.fnDraw();                                
                                          //$("#firstTable").html(result["data"]);                                              
										alertify.success("Data displayed successfully.");
                                                              
                                                                               
									} else {
										alertify.error(result["data"]);
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
						
						// form.submit();
					});
				
			})	
      
	  	$("#overstockForm").bind("submit", function(ev){
			ev.preventDefault();
			var str=$("#overitemid").val();
			var nbprod=$("#nbprodover").val();
			
					alertify.confirm("Get OverStock products?", function (e) {
						if (e) {
							// user clicked "ok"
							var data = $("#overstockForm").serialize();
							 if(!ValidUrl(str)) {
							var url="addoveritemAction.php";
							}
							else {
							var url="addoverlistitemAction.php";
							}
							$.ajax({
								url    : url,
								type   : "POST",
								dataType: "json",  
								data   : data,
								beforeSend: function() {
								  
									alertify.log("Checking provided data...", "", 3000);
								}, 
								success : function( result ) {
									if(result["state"]=="Ok") {
										      oTable.fnDraw();                                
                                          //$("#firstTable").html(result["data"]);                                              
										alertify.success("Data displayed successfully.");
                                                              
                                                                               
									} else {
										alertify.error(result["data"]);
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
						
						// form.submit();
					});
				
			})
		$("#aliexpressForm").bind("submit", function(ev){
			ev.preventDefault();
			var str=$("#expressitemid").val();
			//var nbprod=$("#nbprodexpress").val();
					alertify.confirm("Get Aliexpress products?", function (e) {
						if (e) {
							// user clicked "ok"
							var data = $("#aliexpressForm").serialize();
							 if(!ValidUrl(str)) {
							var url="addexpressitemAction.php";
							}
							else {
							var url="addexpresslistitemAction.php";
							}
							$.ajax({
								url    : url,
								type   : "POST",
								dataType: "json",  
								data   : data,
								beforeSend: function() {
								  
									alertify.log("Checking provided data...", "", 3000);
								}, 
								success : function( result ) {
									if(result["state"]=="Ok") {
										      oTable.fnDraw();                                
                                          //$("#firstTable").html(result["data"]);                                              
										alertify.success("Data displayed successfully.");
                                                              
                                                                               
									} else {
										alertify.error(result["data"]);
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
						
						// form.submit();
					});
				
			})	
	  
	 function CuteWebUI_AjaxUploader_OnTaskComplete(task)
	{
		$("#filename").val(task.FileName);
	}
	function ValidUrl(str) {
  var pattern = new RegExp('^(http?:\\/\\/)?'+ // protocol
  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
 // '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
  '(\\?[;&a-z\\d%_.~+=-]*)?' // query string
  //'(\\#[-a-z\\d_]*)?$','i'
  ); // fragment locator
  if(!pattern.test(str)) {
    return false;
  } else {
    return true;
  }
}
	</script>	        

	
</body>
</html>