<?php
require_once('lib/config.php');
require_once('blocks/head.php');
$error = array();


if (isset($_POST['signin'])) {

    $username = stripslashes(trim($_POST['uname']));
    $password = $_POST['pass'];
    $password = md5($password);

    $sql = "SELECT * FROM ebay_users WHERE username = '$username' and password='$password'";

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
    } else {
        $error['count'] = '<div id="ErrorMsg" class="alert alert-error">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								Error! Invalid User Name Or Password
								</div>';
        $signedin = false;
    }
} 

if (isset($_SESSION['username'])) 
{
?>
    <script type="text/javascript">
        window.location.href = '/main.php';
    </script>
<?php
}
?>

<style>
body {
  padding-top: 180px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
input
{
  margin-bottom: 20px;    
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>

<body>
    <div class="container">

      <form name="LoginForm" id="LoginForm" action="index.php" method="post" class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Login</label>
        <input type="text" name="uname" id="uname"  class="form-control" placeholder="Login" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="pass" id="password" class="form-control" placeholder="Password" required>
        <div class="checkbox">
          <!-- <label> -->
            <!-- <input type="checkbox" value="remember-me"> Remember me -->
          <!-- </label> -->
        </div>
        <button class="btn btn-lg btn-primary btn-block" name="signin"  type="submit">Sign in</button>
      </form>    

    </div> <!-- /container -->




</body>
</html>