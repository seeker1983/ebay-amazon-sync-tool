<?php
function xd($v, $v2 = false, $v3 = false, $v4 = false)
{
    header('Content-Type: text/plain; charset=utf-8');
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
    var_dump($v);
    if($v2) var_dump($v2);
    if($v3) var_dump($v3);
    if($v4) var_dump($v4);
    exit;
}

