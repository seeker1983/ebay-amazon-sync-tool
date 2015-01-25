<?php

//***  "die()" will exit the script and show an error if something goes wrong with the "connect" or "select" functions. 
//***  A "mysql_connect()" error usually means your connection specific details are wrong 
//***  A "mysql_select_db()" error usually means the database does not exist.
// Place db host name. Usually is "localhost" but sometimes a more direct string is needed
$db_host = "jgiven79.mydomaincommysql.com";
$db_name = "ezonsync";
$db_username = "sam";
$db_pass = "sam1234";

$dbc = mysql_connect("$db_host", "$db_username", "$db_pass") or die(mysql_error());
mysql_select_db("$db_name") or die("no database by that name");
mysql_query("SET NAMES 'utf8'");
mysql_query("CREATE TABLE IF NOT EXISTS `ebay_users` (
  `user_id` int(255) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `paypal_address` varchar(100) NOT NULL,
  `ebay_name` varchar(100) NOT NULL,
  `dev_name` varchar(100) NOT NULL,
  `app_name` varchar(100) NOT NULL,
  `cert_name` varchar(100) NOT NULL,
  `eBayReady` varchar(20) NOT NULL,
  `token` varchar(5000) NOT NULL,
  `Token_exp_date` varchar(100) NOT NULL,
  `amazon_username` varchar(100) NOT NULL,
  `amazon_publickey` varchar(150) NOT NULL,
  `amazon_privatekey` varchar(150) NOT NULL,
  PRIMARY KEY (`user_id`)
)");


mysql_query("CREATE TABLE IF NOT EXISTS `asins_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(255) NOT NULL,
  `asins` varchar(100) NOT NULL,
  `processed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
)");

mysql_query("CREATE TABLE IF NOT EXISTS `aws_asin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(255) NOT NULL,
  `asin` varchar(100) NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(1000) NOT NULL,
  `description_url` varchar(255) NOT NULL,
  `description` varchar(3000) NOT NULL,
  `features` varchar(2000) NOT NULL,
  `large_image_url` varchar(255) NOT NULL,
  `medium_image_url` varchar(255) NOT NULL,
  `small_image_url` varchar(255) NOT NULL,
  `thumb_img` varchar(255) NOT NULL,
  `swatch_image_url` varchar(255) NOT NULL,
  `tiny_image_url` varchar(255) NOT NULL,
  `weight` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `length` int(11) NOT NULL,
   `weight_string` varchar(16) NOT NULL,
  `dimensions` text NOT NULL,
  `brand` varchar(100) NOT NULL,
  `ean` varchar(100) NOT NULL,
  `list_price` varchar(100) NOT NULL,
  `offer_price` varchar(100) NOT NULL,
  `shipping_cost` varchar(24) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `size` varchar(20) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `upc` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mpn` varchar(100) NOT NULL,
  `prime` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");



mysql_query("CREATE TABLE IF NOT EXISTS `ebay_asin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(255) NOT NULL,
  `asins` varchar(100) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `ebay_title` varchar(1000) NOT NULL,
  `ebay_description` varchar(9000) NOT NULL,
  `ebay_description_url` varchar(255) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `handling_time` int(11) NOT NULL,
  `profit_percent` varchar(50) NOT NULL,
  `ebay_price` decimal(10,2) NOT NULL,
  `amazon_price` decimal(10,2) NOT NULL,
  `profit_ratio` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amazon_quantity` int(11) NOT NULL,
  `max_quantity` int(11) NOT NULL,
  `shipping_charge` varchar(24) NOT NULL,
  `shipping_option` varchar(24) NOT NULL,
  `return_option` varchar(24) NOT NULL,
  `in_ebay` tinyint(4) NOT NULL,
  `in_amazon` tinyint(4) NOT NULL,
  `product_active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
)");

mysql_query("CREATE TABLE IF NOT EXISTS `user_products` (
  `UserID` int(255) NOT NULL,
  `ItemID` bigint(255) NOT NULL,
  `Qty` int(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `SKU` varchar(100) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `ItemUrl` varchar(255) NOT NULL,
  `AmazonPrice` varchar(100) NOT NULL,
  `ProfitRatio` decimal(10,2) NOT NULL,
  `AmazonQty` int(11) NOT NULL,
  `MaxQty` int(11) NOT NULL,
  `product_active` tinyint(4) NOT NULL,
  UNIQUE KEY `ItemID` (`ItemID`)
)");

mysql_query("CREATE TABLE IF NOT EXISTS `user_products_cron` (
  `UserID` int(255) NOT NULL,
  `ItemID` bigint(255) NOT NULL,
  `Qty` int(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `SKU` varchar(100) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `ItemUrl` varchar(255) NOT NULL,
  `AmazonPrice` varchar(100) NOT NULL,
  `ProfitRatio` decimal(10,2) NOT NULL,
  `AmazonQty` int(11) NOT NULL,
  `MaxQty` int(11) NOT NULL,
  `product_active` tinyint(4) NOT NULL,
  UNIQUE KEY `ItemID` (`ItemID`)
)");

mysql_query("CREATE TABLE IF NOT EXISTS `ebay_config` (
    `user_id` int(11) NOT NULL,
    `title_prefix` varchar(100) NOT NULL,
    `max_quantity` int(11) NOT NULL,
    `profit_percentage` decimal(10,2) NOT NULL,
    `price_formula` varchar(100) NOT NULL,
    `dispatch_time` int(11) NOT NULL,
    `listing_duration` varchar(100) NOT NULL,
    `listing_type` varchar(100) NOT NULL,
    `condition_id` varchar(100) NOT NULL,
    `refund_option` varchar(100) NOT NULL,
    `return_accept_option` varchar(100) NOT NULL,
    `return_days` varchar(100) NOT NULL,
    `shipping_service` varchar(100) NOT NULL,
    `shipping_type` varchar(100) NOT NULL,
    `shipping_cost` decimal(10,2)  NOT NULL,
    `payment_method` varchar(100) NOT NULL,
    `paypal_address` varchar(100) NOT NULL,
    `postal_code`  varchar(100) NOT NULL,
     PRIMARY KEY (`user_id`)
    )");





/* mysql_query("CREATE TABLE IF NOT EXISTS `admin` (
  `user_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ebay_name` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
  )"); */
//mysql_query("INSERT INTO admin SET username = 'admin', password = 'admin123'");


?>