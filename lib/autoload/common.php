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
    var_dump($v);
    if($v2) var_dump($v2);
    if($v3) var_dump($v3);
    if($v4) var_dump($v4);
    exit;
}

function xp($v, $v2 = false, $v3 = false, $v4 = false)
{
    print '<pre>';
    $a = var_export($v, true);
    print $a;
    //print htmlspecialchars($a);
    if($v2) var_dump($v2);
    if($v3) var_dump($v3);
    if($v4) var_dump($v4);
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
