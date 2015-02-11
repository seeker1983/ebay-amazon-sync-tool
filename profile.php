<?php
set_time_limit(0);
$start_time = time("now");
require_once('lib/config.php');

if($user['group'] !=='admin')
    {
    $user_info = $user;
    }
else
{
    if(! empty($_GET['add']) )    
      {
        $user_info = array (
          'user_id' => 'NULL',
          'first_name' => '',
          'name' => 'John Doe',
          'username' => 'new_user',
          'password' => '',
          'email' => '',
          'paypal_address' => '',
          'ebay_name' => '',
          'dev_name' => '',
          'app_name' => '',
          'cert_name' => '',
          'token' => '',
          'sandbox' => '0',
          'postal_code' => '00000',
          'location' => 'New york',
          'payment_methods' => 'PayPal'
        );
        $user_info = array (
          'user_id' => '',
          'first_name' => '',
          'name' => 'Justin Given',
          'username' => 'jgiven',
          'password' => '332532dcfaa1cbf61e2a266bd723612c',
          'email' => 'mnmerchants@gmail.com',
          'paypal_address' => 'mnmerchants@gmail.com',
          'ebay_name' => 'mnmerchant2014',
          'dev_name' => '47d6fc1b-0e74-4627-b281-be45b8a2191f',
          'app_name' => 'MnMercha-6e1f-4d7e-88b9-9954f3aab846',
          'cert_name' => 'd5d6cf0c-f5c2-4baa-9196-4cb3db9e0f5d',
          'eBayReady' => 'Yes',
          'token' => decrypt('+D9KBgPFHvVdaH5I3o1HixlIfEANKhJT7Ti9kyBFwUqIW7ZN3zrBGBVL2javkhM1uxPb9HNz639EiuVfeWoAFdHWx0tuFk8ZGWqbrTFcFueKHLnYr31rUt07AzEoHI7qIDnURm+dwqdm1GEF7tGQ50nHemh1yQ9JkyW6i9UsvRC4v4AVYATzFahcKRFfzkKChwYJ9OlmbklwRvze/9hskWyHLv0eLsVzNhP8mm4n34V6p1iBuzasnHeP6Ipj+n7jAHYhCcs4q5+rJvAD37Cs9mOgUfjCRnWXezhGYM7PzSom68p7dmWGPpOSbBWDScTKp9jlkbDtCFuWrxiyK9SqWIS7SjK7fFb+cX+cGpxm2zfFHz3TnU0p7+J0eXyQFl0Z1H1ScHBZS6yFqcJKI3xfgrPtWG93AuBAYCBpGrCxdXo/PTz4b+dUru1HGUkSAgtS18Fjoef2sTS2MhJIufkmZgrRVV1UaGriuifFM45trj9D2UKFDuT50Y7ssEL8JrRO+1WaUyUrYSgFxW9CmOaCUcOTIIsHa47OmYrq0KdHCLpvEgeLhxvbsyq0clA6d0OksUxhzEbdxAyTXB3n+iA+xeRuz6NtbsREGYy8jTl6PuSr9AAPNuYxhlU2Eg5M8ECxVLwN3LKUdtsviZ0ai8iJKZd6sFWdKEGYEyhbJmfg0j5B+AmNNTRKyFSFKl53UFvKtQkkLolit90ZAxO1dxHWUGOWFjThgQhQUEu8XkrRkIN2RDbRSkOOP3rwNAa9JBXhbo6sbE0uvlE981Uk12WZ7Km9Sx+KFtxpjuk5h2+kQfCMmYpZJJHF6YzObZBfRHyj7m+5/RkYf5LTMzdAptkPwWl+zLiUrw/syDX3px7k+/D3gmde29G/MgI99xCvIFE00iHL40VnU29HdX1R4MpSeRCXB404wJDJ91Y9UXC3m7nWSC7jCpZPkuTatX86xDUpUgezDIMu5OdDTqze3STS+z2VL51fSi75qSvEgXYMMyQPhEt/eY8cg4BGQsVrC8FsZsZNMfD5aWv91yisap/Qg7AJNsxl0HV4jIlsTNWdCNZUENp3ifmEdhnYNw/weeV9Kt2LZKLAfVJOt5XlWj/n87GmtJz8DJWH7SW7Conu9vYVy/LHbWe0vlc0Y1x1KFfRq9UA5DOLEPo='),
          'Token_exp_date' => '',
          'amazon_username' => '',
          'amazon_publickey' => '',
          'amazon_privatekey' => '',
          'sandbox' => '0',
          'postal_code' => '55317',
          'location' => 'Mines Chanhassen',
          'payment_methods' => 'PayPal',
          'footer' => '0',
        );
      }
  else
      {
        $user_id = empty($_GET['user_id'])? $user['user_id'] : intval($_GET['user_id']);
        $user_info = DB::query_row("SELECT * from ebay_users where `user_id` = '$user_id'") ;
      }

}

if(isset($_POST['do']))
{
  $data = array(
          'first_name' => mysql_real_escape_string(isset($_POST['first_name'])?$_POST['first_name']:''),
          'name' => mysql_real_escape_string(isset($_POST['name'])?$_POST['name']:''),
          'username' => mysql_real_escape_string(isset($_POST['username'])?$_POST['username']:''),
          'email' => mysql_real_escape_string(isset($_POST['email'])?$_POST['email']:''),
          'paypal_address' => mysql_real_escape_string(isset($_POST['paypal_address'])?$_POST['paypal_address']:''),
          'ebay_name' => mysql_real_escape_string(isset($_POST['ebay_name'])?$_POST['ebay_name']:''),
          'dev_name' => mysql_real_escape_string(isset($_POST['dev_name'])?$_POST['dev_name']:''),
          'app_name' => mysql_real_escape_string(isset($_POST['app_name'])?$_POST['app_name']:''),
          'cert_name' => mysql_real_escape_string(isset($_POST['cert_name'])?$_POST['cert_name']:''),
          'token' => encrypt($_POST['token']),
          'sandbox' => empty($_POST['sandbox'])? 0 : 1,
          'postal_code' => mysql_real_escape_string(isset($_POST['postal_code'])?$_POST['postal_code']:''),
          'location' => mysql_real_escape_string(isset($_POST['location'])?$_POST['location']:''),
          'payment_methods' => mysql_real_escape_string(isset($_POST['payment_methods'])?$_POST['payment_methods']:''),
    );
  if($user['group']=='admin')
    {
        if(!empty($_POST['add']) || isset($_GET['add']))
          $data['user_id'] = '';
        else
        {
          if(!empty($_POST['user_id']))
              $data['user_id'] = intval($_POST['user_id']);
          elseif(!empty($_GET['user_id']))
              $data['user_id'] = intval($_GET['user_id']);
        }
    }
    else
    {
        $data['user_id'] = $user['user_id'];
    }
    if(!empty($_POST['password']))
      $data['password'] = md5($_POST['password']);

    if($data['user_id'])
        DB::update('ebay_users', $data, "WHERE `user_id` = ${data['user_id']}");
    else
        DB::replace('ebay_users', $data);

    if($user['group'] ==='admin')
    {
      header('location:dashboard.php');
    }
    else
    {
      header('location:profile.php');      
    }

}

require_once('blocks/head.php');
require_once('blocks/menu.php');


?>
    <body>
    
<div class="container-fluid span14" style="margin-top: 0px">
    
    <div class="row-fluid">
        
      <section id="global" class="span14">
          
            <div class="row-fluid">
        
        <section id="global" class="span12">
        <fieldset class="form-horizontal">
        <legend>Profile </legend>

    <form action="profile.php?user_id=<?php echo $user_info['user_id']; ?>" method="post">
        <table width="100%" border="1" bordercolor="#FFFFFF">
            <tr>
                <td width="20%"><div align="center"><h4>General Settings</h4></div></td> 
            </tr>
            <tr>
                <td width="20%"><div align="center">First Name</div></td>
                <td width="80%"><input type="text" name="first_name" autocomplete="off" value="<?php echo $user_info['first_name']; ?>"/></td>
                <input type="hidden" name="id" value="<?php echo $user_info['user_id']?$user_info['user_id']:0; ?>"/></td>
                <input type="hidden" name="add" value="<?php echo isset($_GET['add'])?1:0; ?>"/></td>

            </tr>
            <tr>
                <td width="20%"><div align="center">Last Name</div></td>
                <td width="80%"><input type="text" name="last_name" autocomplete="off" value="<?php echo $user_info['name']; ?>"/></td>

            </tr>

            <tr>
                <td width="20%"><div align="center">Email Address</div></td>
                <td width="80%"><input type="text" name="email" autocomplete="off" value="<?php echo $user_info['email']; ?>"/></td>

            </tr>

            <tr>
                <td width="20%"><div align="center">Paypal Address</div></td>
                <td width="80%"><input type="text" name="paypal_address" autocomplete="off" value="<?php echo $user_info['email']; ?>"/></td>

            </tr>

            <tr>
                <td width="20%"><div align="center">Username</div></td>
                <td width="80%"><input type="text" name="username" autocomplete="off" value="<?php echo $user_info['username']; ?>"/></td>
            </tr>
            <tr>
                <td width="20%"><div align="center">Password</div></td>
                <td width="80%"><input type="text" name="password" autocomplete="off"><br/>(Leave blank for no change)</td>
            </tr>
        </table>
        <br>

        <table width="100%" border=" 1" bordercolor="#FFFFFF">
            <tr>
                <td width="20%"><div align="center"><h4>Ebay Settings</h4></div></td> 
            </tr>
            <tr>
                <td width="20%"><div align="center">DEV NAME</div></td>
                <td width="80%"><input type="text" name="dev_name" autocomplete="off" value="<?php echo $user_info['dev_name']; ?>"/></td>
            </tr>

            <tr>
                <td width="20%"><div align="center">APP NAME</div></td>
                <td width="80%"><input type="text" name="app_name" autocomplete="off" value="<?php echo $user_info['app_name']; ?>"/></td>

            </tr>

            <tr>
                <td width="20%"><div align="center">CERT NAME</div></td>
                <td width="80%"><input type="text" name="cert_name" autocomplete="off" value="<?php echo $user_info['cert_name']; ?>"/></td>
            </tr>

            <tr>
                <td width="20%"><div align="center">Token</div></td>
                <td width="80%"><input type="text" name="token" autocomplete="off" value="<?php echo decrypt($user_info['token']); ?>"/></td>              
            </tr>

            <tr>
                <td width="20%"><div align="center">Sandbox</div></td>
                <td width="80%"><input type="checkbox" name="sandbox" autocomplete="off"  <?=$user_info['sandbox']?'checked':''?>/></td>               
            </tr>
         </table>
         <br>
        <table width="100%" border=" 1" bordercolor="#FFFFFF">
            <tr>
                <td width="20%"><div align="center"><h4>Product Settings</h4></div></td> 
            </tr>

            <tr>
                <td width="20%"><div align="center">Location</div></td>
                <td width="80%"><input type="text" name="location" autocomplete="off" value="<?php echo ($user_info['location']); ?>"/></td>              
            </tr>

            <tr>
                <td width="20%"><div align="center">Postal code</div></td>
                <td width="80%"><input type="text" name="postal_code" autocomplete="off" value="<?php echo ($user_info['postal_code']); ?>"/></td>               
            </tr>


            <tr>
                <td width="20%"><div align="center">Payment method</div></td>
                <td width="80%">

                <select name="payment_methods" />
                  <option value="VisaMC,PayPal" <?=$user_info['payment_methods']=='VisaMC,PayPal'?'selected':''?>>Any</option>
                  <option value="PayPal" <?=$user_info['payment_methods']=='PayPal'?'selected':''?>>PayPal</option>
                  <option value="VisaMC" <?=$user_info['payment_methods']=='VisaMC'?'selected':''?>>VisaMC</option>
                </td>
               
            </tr>

            <tr>
                <td width="20%"><div align="center">Product footer(shipping, return policy etc.)</div></td>
                <td width="80%">
                <textarea width="800px" name="footer"></textarea>
                </td>

            </tr>

        </table>
<!--         <br>
        <table width="100%" border=" 1" bordercolor="#FFFFFF">
            <tr>
                <td width="20%"><div align="center"><h4>Amazon Settings</h4></div></td> 
            </tr>
            <tr>
                <td width="20%"><div align="center">Amazon Username</div></td>
                <td width="80%"><input type="text" name="amazon_username" autocomplete="off" value=""/></td>

            </tr>
            <tr>
                <td width="20%"><div align="center">Public Key</div></td>
                <td width="80%"><input type="password" name="public_key_aws"  value=""/></td>

            </tr>

            <tr>
                <td width="20%"><div align="center">Private Key</div></td>
                <td width="80%"><input type="password" name="private_key_aws"  value=""/></td>
            </tr>
        </table>
 -->        <br>
        <table width="100%" border=" 1" bordercolor="#FFFFFF">
            <tr>
                <td width="20%"><div align="center"></div></td> 
                <td width=" 80%"><input type="submit" class="btn btn-primary" name="do" value="<?=isset($_GET['add'])?'Create new user':'Save profile'?>"/></td> 
            </tr>
        </table>
    </form>
    <div class="spacer"></div>
       

    
</body>
</html>