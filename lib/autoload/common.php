<?php
function xd($v, $v2 = false, $v3 = false, $v4 = false)
{
    xs($v, $v2, $v3, $v4);
}

function dbg($v)
{
//    print($v);
}

function xs($v, $v2 = false, $v3 = false, $v4 = false)
{
    var_export($v);
    if($v2) var_export($v2);
    if($v3) var_export($v3);
    if($v4) var_export($v4);
	debug_print_backtrace();
    exit;
}

function xp($v, $v2 = false, $v3 = false, $v4 = false)
{
    print '<pre>';
    $a = var_export($v, true);
    print $a;
    //print htmlspecialchars($a);
    if($v2) var_export($v2);
    if($v3) var_export($v3);
    if($v4) var_export($v4);
	debug_print_backtrace();
    exit;
}

function xpe($v, $v2 = false, $v3 = false, $v4 = false)
{
    print '<pre>';
    $a = var_export($v, true);
    print htmlspecialchars($a);
    exit;
}

function dump_img($src)
{
	print("<img src='$src'>");
}

function Send404()
{
    header('HTTP/1.0 404 Not Found');
    echo "<h1>404 Not Found</h1>";
    echo "The page that you have requested could not be found.";
    exit();    
}
