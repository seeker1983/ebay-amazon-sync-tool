
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Ebay listings</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <?php
        foreach($itemTypes as $type)
            {
        ?>
            <li ebay-type="<?php echo $type; ?>"><a href="#"><?php echo rtrim($type, 'List') . '(' . count($items[$type]) . ')'; ?></a></li>
        <?php
            }
        ?>
            <li ebay-type="Log"><a href="#">Log</a></li>
            <li><a href="main.php?refresh">Refresh</a></li>

<!--          <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li class="dropdown-header">Nav header</li>
            <li><a href="#">Separated link</a></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
 -->       </ul>
       <ul class="nav navbar-nav navbar-right">
        <? if ($GLOBALS['user']['group'] == 'admin') { ?>
        <li><a href="dashboard.php">Dashboard</a></li>
        <? } ?>
        <li><a href="profile.php"">Profile<?php //echo $_SESSION['username']; ?></a></li>
        <li><a href="logout.php">Logout</a></li>
      </ul> 
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</nav>
            
<script>
$(function()
{
  $('li[ebay-type]').click(function(el) {
    $('li.active[ebay-type]').removeClass('active')
    $('tbody[ebay-type-container]').hide()
    $('tbody[ebay-type-container=' + $(this).attr('ebay-type') + ']').show()
  })
  $('li[ebay-type=ActiveList]').click()

})
</script>