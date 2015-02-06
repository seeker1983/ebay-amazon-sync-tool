<?php
require_once 'redirect.php';
$active_user = $_SESSION['user_id'];
require_once 'inc.db.php';
require_once 'functions.php';
//

if (isset($_POST) and sizeof($_POST)) {
    $str_full = true;

    /*
    foreach ($_POST as $post) {
        if (!strlen($post) or empty($post) or is_null($post)) {
            $str_full = false;
            break;
        }
    }*/

    if ($str_full) {

        $post_fistname = trim($_POST['first_name']);
        $post_lastname = trim($_POST['last_name']);

        $post_ebay_name = trim($_POST['ebay_name']);
        $post_email = trim($_POST['email_address']);
        $post_paypal_address = trim($_POST['paypal_address']);
        $post_dev_name = trim($_POST['dev_name']);
        $post_app_name = trim($_POST['app_name']);
        $post_cert_name = trim($_POST['cert_name']);
        
        //$post_ru_name = trim($_POST['ru_name']);

        $post_token = $_POST['token'];
        $post_token_expiry = trim($_POST['token_expiry']);

        $post_amazon_user = trim($_POST['amazon_username']);
        $post_publickey = encrypt_decrypt('encrypt',$_POST['public_key_aws']);
        $post_privatekey = encrypt_decrypt('encrypt',$_POST['private_key_aws']);


        //user_id,first_name,name,amazon_username,amazon_publickey,amazon_privatekey
        if (filter_var($post_email, FILTER_VALIDATE_EMAIL) /*and filter_var($post_paypal_address, FILTER_VALIDATE_EMAIL)*/) {
            $sql_ebay_users = "UPDATE ebay_users SET 
                    first_name='$post_fistname', 
                    name = '$post_lastname',
                    ebay_name = '$post_ebay_name',
                    email = '$post_email',
                    dev_name = '$post_dev_name',
                    app_name = '$post_app_name',
                    cert_name = '$post_cert_name',
                    token = '$post_token',
                    eBayReady = 'Yes',
                    amazon_username = '$post_amazon_user',
                    amazon_publickey = '$post_publickey',
                    amazon_privatekey = '$post_privatekey'
                    WHERE user_id =$active_user;
                    ";

            //ru_name = '$post_ru_name',
            
            (mysql_query($sql_ebay_users) or die(mysql_error())) and ($_SESSION['eBayReady']='Yes');
            header("Location:home.php");
        } elseif (!filter_var($post_email, FILTER_VALIDATE_EMAIL)) {
            $msg = '<div id="ErrorMsg" class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>
                    INVALID EMAIL ADDRESS</div>';
        }else {
            
        }
    } else {
        
    }
}


$sql_ebay_users = "SELECT * FROM ebay_users WHERE user_id=$active_user";
$rs_ebay_users = mysql_query($sql_ebay_users) or die(mysql_error());

if (mysql_num_rows($rs_ebay_users) != 1)
    die('Error in User Records');

$row = mysql_fetch_array($rs_ebay_users);

if (trim($row['name']) == '' or is_null($row['name']) or empty($row['name']))
    die('Error Name Records');

if (trim($row['username']) == '' or is_null($row['username']) or empty($row['username']))
    die('Error Username Records');

if (trim($row['email']) == '' or is_null($row['email']) or empty($row['email']))
    die('Error in Email Records');

if (trim($row['username']) == '' or is_null($row['username']) or empty($row['username']))
    die('Error Username Records');

if (trim($row['ebay_name']) == '' or is_null($row['ebay_name']) or empty($row['ebay_name']))
    die('Error Ebay_name Records');


//General Settings

$first_name = trim($row['first_name']);
//
$last_name = trim($row['name']);
$username = trim($row['username']);
$email = trim($row['email']);
//Ebay Settings
$ebay_username = trim($row['ebay_name']);
if (strlen($row['paypal_address']) and !is_null($row['paypal_address']) and !empty($row['paypal_address']))
    $paypal_address = trim($row['paypal_address']);
else
    $paypal_address = $email;

$dev_name = trim($row['dev_name']);
$app_name = trim($row['app_name']);
$cert_name = trim($row['cert_name']);
$ru_name = trim($row['ru_name']);

$token = trim($row['token']);

//Amazon Settings
$amazon_username = trim($row['amazon_username']);
//
$public_key_aws = trim(encrypt_decrypt('decrypt',$row['amazon_publickey']));
//
$private_key_aws = trim(encrypt_decrypt('decrypt',$row['amazon_privatekey']));
//
require_once 'head.php';
require_once 'headlines.php';
?>
<div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table width="100%" border =" 1" bordercolor="#FFFFFF">
            <tr>
                <td width =" 20%"><div align="center"><h4>General Settings</h4></div></td> 
            </tr>
            <tr>
                <td width =" 20%"><div align="center">First Name</div></td>
                <td width ="80%"><input type="text" name="first_name" autocomplete="off" value="<?php echo $first_name; ?>"/></td>

            </tr>
            <tr>
                <td width =" 20%"><div align="center">Last Name</div></td>
                <td width ="80%"><input type="text" name="last_name" autocomplete="off" value="<?php echo $last_name; ?>"/></td>

            </tr>

            <tr>
                <td width =" 20%"><div align="center">Email Address</div></td>
                <td width ="80%"><input type="text" name="email_address" autocomplete="off" value="<?php echo $email; ?>"/></td>

            </tr>

            <tr>
                <td width =" 20%"><div align="center">Username</div></td>
                <td width ="80%"><strong><?php echo $username; ?></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="change_password.php"><u>change password</u></a></td>

            </tr>
        </table>
        <br>

        <table width="100%" border =" 1" bordercolor="#FFFFFF">
            <tr>
                <td width =" 20%"><div align="center"><h4>Ebay Settings</h4></div></td> 
            </tr>
            <tr>
                <td width =" 20%"><div align="center">Ebay Username</div></td>
                <td width ="80%"><input type="text" name="ebay_name" autocomplete="off" value="<?php echo $ebay_username; ?>"/></td>

            </tr>


            <tr>
                <td width =" 20%"><div align="center">DEV NAME</div></td>
                <td width ="80%"><input type="text" name="dev_name" autocomplete="off" value="<?php echo $dev_name; ?>"/></td>

            </tr>

            <tr>
                <td width =" 20%"><div align="center">APP NAME</div></td>
                <td width ="80%"><input type="text" name="app_name" autocomplete="off" value="<?php echo $app_name; ?>"/></td>

            </tr>

            <tr>
                <td width =" 20%"><div align="center">CERT NAME</div></td>
                <td width ="80%"><input type="text" name="cert_name" autocomplete="off" value="<?php echo $cert_name; ?>"/></td>

            </tr>

            <tr>
                <td width =" 20%"><div align="center">Token</div></td>
                <td width ="80%"><input type="password" name="token" autocomplete="off" value="<?php echo $token; ?>"/></td>
               
            </tr>

        </table>
        <br>
        <table width="100%" border =" 1" bordercolor="#FFFFFF">
            <tr>
                <td width =" 20%"><div align="center"><h4>Amazon Settings</h4></div></td> 
            </tr>
            <tr>
                <td width =" 20%"><div align="center">Amazon Username</div></td>
                <td width ="80%"><input type="text" name="amazon_username" autocomplete="off" value="<?php echo $amazon_username; ?>"/></td>

            </tr>
            <tr>
                <td width =" 20%"><div align="center">Public Key</div></td>
                <td width ="80%"><input type="password" name="public_key_aws"  value="<?php echo $public_key_aws; ?>"/></td>

            </tr>

            <tr>
                <td width =" 20%"><div align="center">Private Key</div></td>
                <td width ="80%"><input type="password" name="private_key_aws"  value="<?php echo $private_key_aws; ?>"/></td>

            </tr>


        </table>
        <br>
        <table width="100%" border =" 1" bordercolor="#FFFFFF">
            <tr>
                <td width =" 20%"><div align="center"></div></td> 
                <td width =" 80%"><input type="submit" class="btn btn-primary" value="Save Setttins"/></td> 
            </tr>
        </table>
    </form>
</div>

