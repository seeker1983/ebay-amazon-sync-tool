<?php
require_once 'redirect.php';
$active_user = $_SESSION['user_id'];

require_once 'inc.db.php';



if (isset($_POST['current_pass']) and isset($_POST['new_pass']) and isset($_POST['confirm_pass'])) {
    //

    $str_validation = true;
    //
    if ((trim($_POST['current_pass']) == '' or trim($_POST['new_pass']) == '' or trim($_POST['confirm_pass']) == '') or
            (empty($_POST['current_pass']) or empty($_POST['new_pass']) or empty($_POST['confirm_pass'])) or
            (is_null($_POST['current_pass']) or is_null($_POST['new_pass']) or is_null($_POST['confirm_pass']))
    )
        $str_validation = false;
    //
    if ($str_validation) {
        $current_pass = $_POST['current_pass'];
        $new_pass = $_POST['new_pass'];
        $confirm_pass = $_POST['confirm_pass'];

        $current_pass_md5 = md5($current_pass);

        $sql_ebay_users = "SELECT password FROM ebay_users WHERE user_id=$active_user AND password='$current_pass_md5'";
        $rs_ebay_users = mysql_query($sql_ebay_users) or die(mysql_error());
        //
        //
        if (mysql_num_rows($rs_ebay_users) > 1)
            die('Error in ebay_user Records');
        elseif (mysql_num_rows($rs_ebay_users) and ($new_pass === $confirm_pass) and ($current_pass != $new_pass) and preg_match('/^[a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\{\}\[\]\;\:\'\"\,\.\<\>\?\|\/]{8,30}$/', $new_pass)
        ) {
            //
            $new_pass_md5 = md5($new_pass);
            $sql_ebay_users = "UPDATE ebay_users SET password='$new_pass_md5' WHERE user_id=$active_user";
            (mysql_query($sql_ebay_users) or die(mysql_error())) and header("Location:profile.php");

            /* and ($msg = '<div id="ErrorMsg" class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              Password Updated Successfully !
              </div>'); */
        }  elseif (mysql_num_rows($rs_ebay_users) == 0) {
            $msg = '<div id="ErrorMsg" class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              Unmatched Current Password !
              </div>';
        } elseif ($current_pass_md5 === md5($new_pass)) {
            $msg = '<div id="ErrorMsg" class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              Current Password and New Passwords are Identical !
              </div>';
        } elseif (!preg_match('/^[a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+\{\}\[\]\;\:\'\"\,\.\<\>\?\|\/]{8,30}$/', $new_pass)) {
            $msg = '<div id="ErrorMsg" class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              New Password Length should be between 8 to 30 characters !
              </div>';
        }elseif ($new_pass != $confirm_pass) {
            $msg = '<div id="ErrorMsg" class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              Unmatching New Passwords !
              </div>';
        }
        //
    } else {
        $msg = '<div id="ErrorMsg" class="alert alert-error">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              Empty Values are passed !
              </div>';
    }
}
require_once 'head.php';
require_once 'headlines.php';
?>

<!-- below change password -->
<br>

<div>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <table width="100%">
            <tr>
                <td width =" 20%"><div align="center"><h4>Change Password</h4></div></td> 
            </tr>
            <tr>
                <td width="20%"><div align="center">Current Password</div>
                </td>
                <td width="80%">
                    <input type="password" name="current_pass"/>
                </td>
            </tr>
            <td width="20%">
                <div align="center">New Password</div>
            </td>
            <td width="80%">
                <input type="password" name="new_pass"/>
            </td>
            <tr>
                <td width="20%">
                    <div align="center">New Password [confirm]</div>
                </td>
                <td width="80%">
                    <input type="password" name="confirm_pass"/>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td width="20%">

                </td>
                <td width="80%">
                    <input type="submit" class="btn btn-primary" value="Change"/>
                </td>
            </tr>
        </table>
    </form>

</div>