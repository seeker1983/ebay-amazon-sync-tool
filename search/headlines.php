<div class="page-header" style="background-color:#000000;">
    <h2 style="text-align:center; color:#FFFFFF; margin-top:0px; padding-top:20px; font-family:Georgia, 'Times New Roman', Times, serif;">Ebay - Amazon Tool</h2>
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
<div class="navbar">
    <div class="navbar-inner"> <a class="brand" href="dashboard.php">Ebay - Amazon Tool</a>
    </div>
</div>

<div id="ShowResults" style="margin:auto; width:98%;">
    <div style="height:50px;">
        <a href="add_asin.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-info" type="button">Add Asin</button></a>  
        <a href="grab_amazon.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-success" data-loading-text="Loading..." type="button">Fetch Asin Details</button></a>
        <a href="ebay_edit.php" style="color:#FFFFFF; font-weight:bold;"><button class="btn btn-inverse disabled" type="button">Edit Ebay Settings</button></a>
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