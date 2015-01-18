<?php

$session_expiration = time() + 3600* 6; // +6 hours
session_set_cookie_params($session_expiration);
session_start();

if($_SERVER['HTTP_HOST'] === 'ezonsync.ru')
{
	$db_host = "localhost";
	$db_name = "ezonsync";
	$db_username = "root";
	$db_pass = "";	
}
else
{
	$db_host = "jgiven79.mydomaincommysql.com";
	$db_name = "ezonsync";
	$db_username = "sam";
	$db_pass = "sam1234";	
}

foreach(glob('lib/autoload/*.php') as $lib)
    require_once($lib);

$db = new DB($db_name, $db_host, $db_username, $db_pass);

define('SANDBOX', false);

$active_user = $_SESSION['user_id'];
$sql = "SELECT dev_name, app_name, cert_name, token, ebay_name FROM ebay_users WHERE user_id = $active_user";
$user = array_map('trim', DB::query_row($sql));

$DEVNAME = $user['dev_name'];
$APPNAME = $user['app_name'];
$CERTNAME = $user['cert_name'];

//xd(encrypt('AgAAAA**AQAAAA**aAAAAA**G+62VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFmYKiAZODqAidj6x9nY+seQ**D5kCAA**AAMAAA**hBTDbwVBZQzRd1eWo+Q6GpIKHswi1M0LpELsFw3mMBz1rTRu6YbK++5EW9jfeOEaP47160L+pM3DonYI+9x0IRUrCGa5A+KARGCZsAtv9ahZO/SNw0zdKEqGQjU2gDbEtniyRYGhRXmyNBadHTQjnl21gsCGRyIO3IJDiMkdm8pxl/lTax4XaNr2lvrCD4zzBSFVScyKxnJAIFnFhuhMij3vagg452WSHApVajCe4STr9yatb9VX1Rleo2jVHQdWX5XrAV3HXvAP3MsVVu4LXAvtXvmbOAmM6c/FS6yqrC1k1dS7ODqTFxSgaDPwJxJxJ/FVmpKlkgOyRC2n9MMXqY2GCe+3uPa4CsosCnKduTryU1BSsw2wmizaG/uDoX7w8ci6D17ot3OJbhQQUFfO0RaGFQuSHTq1T0qSnhRzpHQrdHuX6iHWYTYt9/aP/rONApXk7Y5zGx9AKjwxyfPPWztyrG1Xo8v+WVySrQ6DN6yY4KYFDzjR/ENhy2Hnd/gxIMkQXmLrq4gzgJ9RPG3V6kBxoGrqOJ5tT45SBNp3gvxe9lpeTSRrwr8LGKTLHc/ukq3eTdLiQoXTyCBOp8EyU9J/7oz9/+b1mRLNq2zkCZkTyicST0TB+TmyEgnvzxtIqWv5Ho3ULbKYd0UcQo43CMpEuU88OA+uAZCCBGsY77YKnA3lKl7G7p+HOM7nAoWd20eVCsACmNhSDhRkVn+l4m+48gPxGTGJh107N7GqM+ExG1nXYS/cqFo0EXpgVyGr'));

$token = decrypt($user['token']);
$ebayusername=$user['ebay_name'];

if(SANDBOX)
{
	$eBayAPIURL = "https://api.sandbox.ebay.com/ws/api.dll";
}
else
{
	$eBayAPIURL = "https://api.ebay.com/ws/api.dll";	
}

$COMPATIBILITYLEVEL = '837';
$SiteId = 0;




?>