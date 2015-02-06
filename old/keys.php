<?php

session_start();
include('inc.db.php');
//require_once 'functions.php';

$active_user =8;
$sql = "SELECT * FROM ebay_users WHERE user_id = $active_user ";
////$sql = "SELECT * FROM ebay_users WHERE user_id = 1 ";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);

$DEVNAME = trim($row['dev_name']);
$APPNAME = trim($row['app_name']);
$CERTNAME = trim($row['cert_name']);

//$token = "AgAAAA**AQAAAA**aAAAAA**rk05VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AElYCmCJiFqQqdj6x9nY+seQ**y4ICAA**AAMAAA**I+dnHHqjbr1rjZlo8wEbyge8d1Rj73Vd3La+W4FzvDVDYl4cLgvS2pw9bttZX9kkkFTpeRtt+UDuYxU9N6O3FpzN7OklgZaQKMud9sjKb6O3WQYICnjw2KdMlEaeBKrTTmyM31hEcOiTvJB5u/0U0zFLWXvk1Qk6YAPhqTGejzYiwQtqYGtmd0rGtoS+b+46XxRov3saL5cRdOfSSTcHlWY9mtIqWerFpM4ZP0Qbj6xM4azxUa0m0BIZKf70+EbSBoFOaVo4X2k5ysHvSII+1WTbVBNvqgp9PNvgfyoMs+VZZ77Q+Qlfl+kk1pmGJqgqUY854qv0gABzSV5OEbgHIS2v0K2OhcTbo3EgvDX1SbPr/MKGFmC2aeqrVbn/9x5XZKnwPdtqoDwYJKe3sU/tzonToUKfyqldIF1GbJlvh5lgd2dPv+WS29g0wNNKGtPDyrLmQ0cktF0Ymnx5zvT7xffOtE9HweCCCILJeqFgyS7XeBgSY4d4fXcxXtaMaK9c2HFjwnF4v2WKoJ9KYYT9svSsf3ksk4fGzEv6XVPonZQxvyZESJHxQ7SbR/KE0rAB1WZY8N8x0hMu999SEJEse4lYRP7RjVxx82AVZp6MUVExfHSMQcQQL8O5O6wgjkErAHpfd7CzOmaSeyy4HNzWq3liP0WGQCfVXFDGl2YunJL9stIKaA/SrMeOvx5CSAWYUwEnx4OUwKlPpeZot+x98I+6Q1i2K1Paf6jMM6X5j4HGIrtMHgCMqWfM8IkPU+5Q";
$token=trim($row['token']);
$ebayusername=trim($row['ebay_name']);
$sql = "SELECT * FROM ebay_config WHERE user_id = $active_user ";
////$sql = "SELECT * FROM ebay_users WHERE user_id = 1 ";
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$paypal_address=trim($row['paypal_address']);


$eBayAPIURL = "https://api.ebay.com/ws/api.dll";
//$eBayAPIURL = "https://api.sandbox.ebay.com/ws/api.dll";
$COMPATIBILITYLEVEL = '837';
$SiteId = 0;

//include('site_conf.php');

?>