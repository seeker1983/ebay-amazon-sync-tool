<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');

if(@$user['group'] !=='admin')
  header("Location: main.php");

if(isset($_GET['delete']) && !empty($_GET['user_id']))
{
  $user_id = intval($_GET['user_id']);
  DB::query("DELETE FROM `ebay_users` where `user_id` = '$user_id'");
}


require_once('lib/ebay.php');
require_once('lib/scrape.php');
require_once('blocks/head.php');
require_once('blocks/menu.php');

?>
    <body>
    

<!--     <div id="ShowResults" style="margin:auto; width:98%;">
        <div style="height:50px;">
            <a href="add_asin.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-info disabled" type="button">Add Asin</button></a>  
            <a href="grab_amazon.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-success" data-loading-text="Loading..." type="button">Fetch Asin Details</button></a>
            <a href="ebay_edit.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-inverse" type="button">Edit Ebay Settings</button></a>
            <a href="send_to_ebay.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-danger" type="button">Add to Ebay</button></a>
            <a href="view_ebay_data.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-warning" type="button">View Ebay Listings</button></a>

        </div>
        <div style="clear:both;"></div>
 -->    
    
<div class="container-fluid span14" style="margin-top: 0px">
    
    <div class="row-fluid">
        
      <section id="global" class="span14">
          
            <div class="row-fluid">
        
        <section id="global" class="span12">
        <fieldset class="form-horizontal">
        <legend>Amazon Fetching Product </legend>

            <form id="amazonForm" class="form-horizontal" action="" method="post">
                <fieldset>
<table id="table">
    <thead>
    <tr>
        <th>Id</th>
        <th>Login</th>
        <th>Name</th>
        <th>Email</th>
        <th>Sandbox</th>
        <th></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? foreach (DB::query_rows("select * from ebay_users") as $user) { ?>
    <tr>
      <td> <?php echo $user['user_id'] ?> </td>
      <td> <?php echo $user['username'] ?> </td>
      <td> <?php echo $user['name'] ?> </td>
      <td> <?php echo $user['email'] ?> </td>
      <td> <?php echo $user['sandbox']? '<font color=blue> Sandbox </font>' : '<font color=green> Real </font>' ?> </td>
      <td> <a href='profile.php?user_id=<?php echo $user['user_id'] ?>'>         
              <button class="btn btn-primary" type="button">Edit</button>
           </a>
      </td>
      <td> <a href='dashboard.php?delete&user_id=<?php echo $user['user_id'] ?>'>
              <button class="btn btn-warning" type="button">Delete</button>
           </a>
      </td>
    </tr>
    <? } ?>
    </tbody>
</table>
                <script>
                 $($('#table').bootstrapTable())
                </script>
                <br/>
             
                <div class="control-group">
                  <div class="controls">
                    <a href='profile.php?add'>         
                    <button class="btn btn-primary" type="button">Add new user</button>
                    </a>                  
                  </div>
                </div>
        </fieldset>
   </form>
    <div class="spacer"></div>
       

    
</body>
</html>