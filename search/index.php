<?php
$session_expiration = time() + 3600* 6; // +6 hours
session_set_cookie_params($session_expiration);
session_start();

if (isset($_SESSION['username'])) {
    header("Location:home.php");
}
require_once('head.php');
include('inc.db.php');
//error_reporting(0);
$error = array();
//


if (isset($_POST['signin'])) {

    $username = stripslashes(trim($_POST['uname']));
    $password = $_POST['pass'];
    $password = md5($password);

    // check
    $sql = "SELECT * FROM ebay_users WHERE username = '$username' and password='$password'";
    //echo $sql;
    $rs = mysql_query($sql) or die(mysql_error());
    $count = mysql_num_rows($rs);

    if ($count > 0) {
        while ($row = mysql_fetch_array($rs)) {
            $user_id = $row['user_id'];
            $user = $row['username'];
            $ready_ebay = $row['eBayReady'];

            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $user;
            $_SESSION['eBayReady'] = $ready_ebay;
            $_SESSION['signedin'] = true;

            $sql = "SELECT * FROM ebay_config WHERE user_id = $user_id ";
            //echo $sql;
            $resp = mysql_query($sql) or die(mysql_error());
            $count = mysql_num_rows($resp);
            if ($count <= 0) {
                $sql_insert = "INSERT INTO ebay_config SET 
                user_id=$user_id,
                title_prefix='New',
                profit_percentage = 25,
                condition_id = '1000',
                price_formula = '001',
                dispatch_time = 1,
                max_quantity = 1,
                shipping_service ='UPSGround',
                shipping_type = 'Flat',
                payment_method='PayPal',
                return_accept_option = 'ReturnsAccepted',
                return_days = 'Days_14',
                listing_type = 'FixedPriceItem',
                listing_duration ='GTC'
                ";
                mysql_query($sql_insert) or die(mysql_error());
            }
        }
        //header("Location: home.php");
        echo '<script>  
							window.location.href = "view_ebay_data.php";
						
					  </script>';
    } else {
        $error['count'] = '<div id="ErrorMsg" class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								Error! Invalid User Name Or Password
								</div>';
        $signedin = false;
    }
} elseif (isset($_SESSION['username'])) {
    header("Location:home.php");
}
?>

<body>
    <div id="header" style="width:100%; height:110px; background-color:#000000;">
        <h2 style="text-align:center; color:#FFFFFF; margin-top:0px; padding-top:20px; font-family:Georgia, 'Times New Roman', Times, serif;">Ebay Tool</h2>
    </div>


    <div style="margin:auto; width:355px; border-radius:10px; margin-top:100px; border:solid 1px #CCCCCC;">
        <h3 style="text-align:center; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif;">Please Sign In</h3>
        <?php
        if (isset($error['count'])) {
            echo $error['count'];
        }
        ?>
        <form name="LoginForm" id="LoginForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >

            <table align="center">
                <tr>

                    <td><label class="label">User Name:</label></td>

                    <td><input type="text" name="uname" id="uname" class="span"  rel="tooltip" data-original-title="Enter UserName"/></td>
                </tr>

                <tr>
                    <td><label class="label">Password:</label></td>
                    <td><input type="password" name="pass" id="password" class="span"  rel="tooltip" data-original-title="Enter password"/></td>
                </tr>


                <tr>
                    <td></td>
                    <td align="right"><input type="submit" value="LOG IN" name="signin" id="Login" class="btn btn-success" /></td>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right"><a href="register.php" class="btn btn-primary">Register</a></td>
                </tr>
            </table>
        </form>


    </div>

</body>
</html>