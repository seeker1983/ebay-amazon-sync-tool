<?php

require_once 'inc.db.php';

(mysql_query("delete from asins_table") and print_r("Cleared asin_table\n")) or die(mysql_error());
(mysql_query("delete from aws_asin") and print_r("Cleared aws_asin\n")) or die(mysql_error());
(mysql_query("delete from ebay_asin") and print_r("Cleared ebay_asin\n")) or die(mysql_error());
(mysql_query("delete from user_products") and print_r("Cleared user_products\n")) or die(mysql_error());

?>
