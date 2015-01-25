<?php
session_start();
require_once('head.php');
include('inc.db.php');

session_start();
if (isset($_SESSION['username'])) {
    header("Location:home.php");
}


$msg = array();

if (isset($_POST['register'])) {

    $name = stripslashes(trim($_POST['name']));
    $uname = stripslashes(trim($_POST['uname']));
    $pword = trim($_POST['password']);
    $pword = md5($pword);
    $email = trim($_POST['email']);
    $ebay_name = trim($_POST['eBayName']);

    $sql = "SELECT * FROM ebay_users WHERE username = '$uname'";
    $rs = mysql_query($sql) or die(mysql_error());
    $count = mysql_num_rows($rs);

    if ($count == 0) {
        $insert = "INSERT INTO ebay_users SET name = '$name', username = '$uname', password = '$pword', email = '$email', ebay_name = '$ebay_name', eBayReady = 'No' ";
        $rs = mysql_query($insert) or die(mysql_error());
        ?>

        <script>
            window.location.href = "index.php";
        </script>

        <?php
        /*
          $last_id = mysql_insert_id();

          if($rs)	{

          require('ebayFunctions.php');
          $session = GetSessionID();
          $xml_session = simplexml_load_string($session);
          //print_r($xml_session);
          //return;

          if($xml_session->Ack == 'Success') {

          $session_id = $xml_session->SessionID;

          $ebay_auth_url = 'https://signin.sandbox.ebay.com/ws/eBayISAPI.dll?SignIn&runame='.$ru_name.'&SessID='.$session_id.'&ruparams='.urlencode('isAuthSuccessful=true&blzsid='.$session_id.'&blzuid='.$last_id);

          //header('Location:'.$ebay_auth_url);
          echo '<script>
          window.location.href = "'.$ebay_auth_url.'"

          </script>';

          }

          } */
    } else {
        $msg['error'] = '<div id="ErrorMsg" class="alert alert-error">
										<button type="button" class="close" data-dismiss="alert">&times;</button>
										Error! Username Already Exists
								    </div>';
    }
}
?>

<body>
    <div id="header" style="width:100%; height:110px; background-color:#000000;">
        <h2 style="text-align:center; color:#FFFFFF; margin-top:0px; padding-top:20px; font-family:Georgia, 'Times New Roman', Times, serif;">Ebay Tool</h2>
    </div>

    <div style="margin:auto; width:355px; border-radius:10px; margin-top:100px; border:solid 1px #CCCCCC;">
        <h3 style="text-align:center; font-family:'Palatino Linotype', 'Book Antiqua', Palatino, serif;">Register</h3>
<?php
if (isset($msg['error'])) {

    echo $msg['error'];
}
?>
        <form name="Register" id="Register" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post"  >
            <table align="center">
                <tr>
                    <td><label class="label">Name:</label></td>
                    <td><input type="text" name="name" id="name" class="span" data-original-title="Enter UserName"   /></td>
                </tr>
                <tr>
                <tr>
                    <td><label class="label">User Name:</label></td>
                    <td><input type="text" name="uname" id="uname" class="span" /></td>
                </tr>
                <tr>
                    <td><label class="label">Password:</label></td>
                    <td><input type="password" name="password" id="password" class="span"  /></td>
                </tr>
                <tr>
                    <td><label class="label">Email:</label></td>
                    <td><input type="text" name="email" id="email" class="span"  /></td>
                </tr>
                <tr>
                    <td><label class="label">eBay Name:</label></td>
                    <td><input type="text" name="eBayName" id="eBayName" class="span"  /></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right"><input type="submit" value="Register" name="register" id="Register" class="btn btn-success" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="right"><a href="index.php" class="btn btn-primary">Login</a></td>
                </tr>
            </table>
        </form>
    </div>
