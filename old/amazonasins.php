<?php 


$htmlTable  = '
    
		<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="itemsTable" style="height:850px">
                <thead>
                  <tr> 
				    <th class="span2">ASIN</th>
                     <th class="span2">Provider</th>
				
					
					
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <br>
              <br>
              <script>
	 var oTable;
                 oTable = $("#itemsTable").dataTable({
				 
				    "bProcessing": true,
                    "bServerSide": true,  
                     "sAjaxSource": "server_processing_asins.php",					
					"bCustomFilter":true,
                    "aaSorting": [[ 0, "desc" ]],
				    "sScrollX": "99%",
			 	    "bAutoWidth": false,
				"bScrollCollapse": true,
				 "sRowSelect": "multi",
                "sDom": "<\'row-fluid\'<\'span6\'T><\'span6\'f>r>l<\'clearfix\'>t<\'row-fluid\'ip>",
				
                    "oTableTools": {
                        "sSwfPath": "assets/media/swf/copy_csv_xls_pdf.swf",
                        "aButtons": [
                            "copy",
                             {
                                "sExtends":    "collection",
                                "sButtonText": \'Save <span class="caret" />\',
                                "aButtons":    [ 
								{   
                                "sExtends":    "csv",
                                "sButtonText": \'CSV\',
                                  "sFileName": "amazon_asin_"+Math.floor((Math.random() * 100) + 1)+".csv",
                                   "mColumns": [0, 1, 2, 3, 4, 5, 8, 9 ]
								  },
								  {   
                                "sExtends":    "xls",
                                "sButtonText": \'XLS\',
                                  "sFileName": "amazon_asin_"+Math.floor((Math.random() * 100) + 1)+".xls",
                                   "mColumns": [0, 1, 2, 3, 4, 5, 8, 9 ]
								  }
						
						
								   ]
                            }
						]
                    },
				"bPaginate": true,
              
                })
           </script> ';
		   echo $htmlTable;
