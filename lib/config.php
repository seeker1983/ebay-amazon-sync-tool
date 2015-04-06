<?php

$session_expiration = time() + 3600* 6; // +6 hours
session_set_cookie_params($session_expiration);
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors', true);

require_once('lib/const.php');

if($_SERVER['HTTP_HOST'] === 'ezonsync.ru')
{
	$db_host = "localhost";
	$db_name = "ezonsync";
	$db_username = "root";
	$db_pass = "";	

	define('LOCAL_SERVER', TRUE);
}
elseif($_SERVER['HTTP_HOST'] === 'znz.edu.vn.ua')
{
	$db_host = "localhost";
	$db_name = "parsel";
	$db_username = "parsel";
	$db_pass = "parsel1q";	

	define('LOCAL_SERVER', FALSE);
}
elseif($_SERVER['HTTP_HOST'] === 'dropshippingsync.com')
{
	$db_host = "localhost";
	$db_name = "ezonsync";
	$db_username = "ezonsync";
	$db_pass = "ezonsync1q";	

	define('LOCAL_SERVER', FALSE);
}
else
{
	$db_host = "jgiven79.mydomaincommysql.com";
	$db_name = "ezonsync";
	$db_username = "sam";
	$db_pass = "sam1234";	

	define('LOCAL_SERVER', FALSE);
}

foreach(glob('lib/autoload/*.php') as $lib)
    require_once($lib);

$db = new DB($db_name, $db_host, $db_username, $db_pass);

foreach (DB::query_rows("select * from ebay_users") as $row) 
{
//	DB::query("update ebay_users set token='" .decrypt($row['token']). "' where user_id=${row['user_id']}");
}




if(isset($_SESSION['user_id']))
{
	$active_user = $_SESSION['user_id'];
	$_user = new User($active_user);

	$sql = "SELECT * FROM ebay_users WHERE user_id = $active_user";
	$user = array_map('trim', DB::query_row($sql));

	$DEVNAME = $user['dev_name'];
	$APPNAME = $user['app_name'];
	$CERTNAME = $user['cert_name'];

	//$token = encrypt('AgAAAA**AQAAAA**aAAAAA**G+62VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AFmYKiAZODqAidj6x9nY+seQ**D5kCAA**AAMAAA**hBTDbwVBZQzRd1eWo+Q6GpIKHswi1M0LpELsFw3mMBz1rTRu6YbK++5EW9jfeOEaP47160L+pM3DonYI+9x0IRUrCGa5A+KARGCZsAtv9ahZO/SNw0zdKEqGQjU2gDbEtniyRYGhRXmyNBadHTQjnl21gsCGRyIO3IJDiMkdm8pxl/lTax4XaNr2lvrCD4zzBSFVScyKxnJAIFnFhuhMij3vagg452WSHApVajCe4STr9yatb9VX1Rleo2jVHQdWX5XrAV3HXvAP3MsVVu4LXAvtXvmbOAmM6c/FS6yqrC1k1dS7ODqTFxSgaDPwJxJxJ/FVmpKlkgOyRC2n9MMXqY2GCe+3uPa4CsosCnKduTryU1BSsw2wmizaG/uDoX7w8ci6D17ot3OJbhQQUFfO0RaGFQuSHTq1T0qSnhRzpHQrdHuX6iHWYTYt9/aP/rONApXk7Y5zGx9AKjwxyfPPWztyrG1Xo8v+WVySrQ6DN6yY4KYFDzjR/ENhy2Hnd/gxIMkQXmLrq4gzgJ9RPG3V6kBxoGrqOJ5tT45SBNp3gvxe9lpeTSRrwr8LGKTLHc/ukq3eTdLiQoXTyCBOp8EyU9J/7oz9/+b1mRLNq2zkCZkTyicST0TB+TmyEgnvzxtIqWv5Ho3ULbKYd0UcQo43CMpEuU88OA+uAZCCBGsY77YKnA3lKl7G7p+HOM7nAoWd20eVCsACmNhSDhRkVn+l4m+48gPxGTGJh107N7GqM+ExG1nXYS/cqFo0EXpgVyGr');
	//xd(encrypt("AgAAAA**AQAAAA**aAAAAA**JPnJVA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GhDZGLpwudj6x9nY+seQ**TzUDAA**AAMAAA**vKXXPTO6jEoyGt+tEaOoay7uhUawF5n7YMuRajXR2Oq3NtXE2aFumryVj0iOkFogNf/0DxW2iffHyLUv93eRc/AMZFXyGBJb470GEGm6hh9ou7gUsIPN4Q7frHQgtl7yqrJ6RMBsYw4xuVVrqaTJ0Ud4gIaKFpxwV7KxJE20H2/MVUOFv4d/OhG8Y9pJJFITFQguWiMHB1amGPBugHADrYc97/wF/i3f5AZ+/6pjFGNAR2nmRA0NunJrqCHT4z0ckjB/aE0eRFOtFv35MIKyCSGMhVNzuv94p4OUFUAfEv2ojjf1/Qla8ufpnPigmfSjLVWaSGMqNu2nOPZL0Ul8j9MTCKI0MMxk5USC+gduMCKu2AIvLzd089heFPCwGH7MeNSlRdFEq0BshHNdzBXS+sGgvfHArIg0TIXzqq7Kn85F90F2HL9rFgfDaywZmk5lvMv0W7DjpbsoSsSQv9H8AmivtBsQN7J0KovenEKmOF1AK7U7MIC/evDRFzWyu2B6B3I7ylkMd+P9nygw3/a/eQMVGtt5kIJVByoLjVY5KMlrBZ1xSqALsvmaS2xG5PWAq8077oB5U3y1iUZwH1941wkMgE2tB3NQVBGuLe5drIvt5ODvCkNxewP6aLmMInGOqwvgWE20+uWZQhiqWtcqMSScHGxIO5poyIAARlwlmsiqcBwwCHPJoo4+1y9C34nSFvvcDSMNJCP3of4j4EsrMNcVsMv0OtywBIR1y1VQ54N7MPRvInqXwCYZKpQuyDOq")); // TESTUSER_seeker1983
	//xd(encrypt("AgAAAA**AQAAAA**aAAAAA**bajSVA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GhDZKKpwydj6x9nY+seQ**zjgDAA**AAMAAA**xN7nAFSZwy0OULgz4hYVevvVk4DmgZp38S0PkqCtN0v2eNAviRrZuoYFbNWzXuyGc+ebcPkWaL1hY7IKm0BUCiFShZQo+p70ZObH0cotvF9xu9jg8Oj8M/Tr7ocaRf3JYqwL9igtJb6RXM7F0r69OcN3G/mu4iOSSIWjMeDmG7WbkjzVlQwidWAJlne8nMviZpTakBXweGIX3AdpfM9MSVO2mPpJZwQZiIqqEOIvHnTn1lFgKIkPU66jFCeeFv1ykWmkad1rKCJjOzpMKSB3gpf2wr3CG6voueYvt6gYzJ/+6Xb0vuYLV34cr8bX7QQPfQx/xdIFuCW1ZLrXaEvsMsojBgHwvIdUYZDMf3cK0P+XQN8yIALKYKnRDMXCuGtskYhNcswtDKu1kCtx9RAEt+JN28nj7dv2VfMsrt2E4OoZeZ29eQdT/mCXEFAyDz9HlUU5TtiVtRh/3N6wIgLT7/J169iLpDmEDKAp6W6F6MiQ82jeNxq/khUEdfOAZnkR73CyZ4ZatY1JvKJeo5XIgOuB6DkT7frMJltKIedqzSeDz+nBL/kRdNzyW5asaR0H1qg02C+3jaYRHURLiReBGN7YnGBxgVsiBzilMuz2D3m6n3veQAZWXf92QIBR2XVqcR2Ie/qw4L2eJtmFxUc75ZwQSOZgsyuHb1I+XBpdhHY0IDxrpBhnujlizZ3D0FUJWsACK1F9hte28XzlvSirWlmEPiK8T6YbWNz2SHU7C+ap+0PXSs7xgZ0JTLI706/2")); // TESTUSER_jgiven
	//mysql_query("UPDATE ebay_users set `token`='$token' WHERE user_id = $active_user");

//	$token = decrypt($user['token']);
	$token = $user['token'];
	$ebayusername=$user['ebay_name'];

	if($user['sandbox'])
	{
		$eBayAPIURL = "https://api.sandbox.ebay.com/ws/api.dll";
	}
	else
	{
		$eBayAPIURL = "https://api.ebay.com/ws/api.dll";	
	}

}

$COMPATIBILITYLEVEL = '800';
$SiteId = 0;






