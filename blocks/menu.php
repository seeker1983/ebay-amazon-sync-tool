<div class="page-header" style="background-color:#000000;">
    <h2 style="text-align:center; color:#FFFFFF; margin-top:0px; padding-top:20px; font-family:Georgia, 'Times New Roman', Times, serif;"><a style="color:white" href="/"> Ebay - Amazon Tool</a></h2>
    <p style="color:#FFF; margin:auto; text-align:center; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;"><?php
        echo 'Hello ';
        if (isset($_SESSION['username'])) {
            ?>
            <strong><a href="profile.php"><?php echo $_SESSION['username']; ?></a></strong>
            <?php
        } else {
            echo 'Admin!';
        }
        ?> | <a href="logout.php" style="color:#FFFFFF; font-weight:bold;">Logout</a></p>
</div>
<!-- <div id="ShowResults" style="margin:auto; width:98%;">
    <div style="height:50px;">
         <a href="main.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-primary" type="button">Results</button></a>
         <a href="dump_ebay.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-warning" type="button">Ebay products</button></a>
         <a href="log.txt" target="_blank" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-success" data-loading-text="Loading..." type="button">view log file</button></a>
         <a href="cron_job.php?user_id=<?php echo $active_user; ?>" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-info" type="button">Execute cron job</button></a>   
    </div>
</div>
 -->
