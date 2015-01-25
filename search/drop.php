<?php
include 'inc.db.php';
mysql_query("DROP TABLE aws_asin");
mysql_query("DROP TABLE asins_table");
mysql_query("DROP TABLE ebay_asin");
mysql_query("DROP TABLE ebay_users");
//mysql_query("DROP TABLE admin");
mysql_query("DROP TABLE user_products");
mysql_query("DROP TABLE ebay_config");
exec ("rm ".  dirname(__FILE__)."/templates/*");

?>
