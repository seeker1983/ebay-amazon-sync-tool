<?php
require_once 'redirect.php';
$active_user = $_SESSION['user_id'];

require_once 'inc.db.php';
require_once 'head.php';
?>

<div class="page-header" style="background-color:#000000;">
    <h2 style="text-align:center; color:#FFFFFF; margin-top:0px; padding-top:20px; font-family:Georgia, 'Times New Roman', Times, serif;">Ebay - Amazon Tool</h2>
    <p style="color:#FFF; margin:auto; text-align:center; font-family:'Lucida Sans Unicode', 'Lucida Grande', sans-serif;"><?php
        echo 'Hello ';
        if (isset($_SESSION['username'])) {
            echo $_SESSION['username'];
        } else {
            echo 'Admin!';
        }
        ?> | <a href="logout.php" style="color:#FFFFFF; font-weight:bold;">Logout</a></p>
</div>