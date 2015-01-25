<?php
$mysql_hostname = "localhost";
$mysql_user = "eshoppe4_imran";
$mysql_password = "imara@123";
$mysql_database = "eshoppe4_imran";
$prefix = "";
$bd = mysql_connect($mysql_hostname, $mysql_user, $mysql_password) or die("Opps some thing went wrong");
mysql_select_db($mysql_database, $bd) or die("Opps some thing went wrong");

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
  `weight` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `ean` varchar(100) NOT NULL,
  `list_price` varchar(100) NOT NULL,
  `offer_price` varchar(100) NOT NULL,
  `currency_code` varchar(10) NOT NULL,
  `size` varchar(20) NOT NULL,
  `sku` varchar(100) NOT NULL,
  `upc` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mpn` varchar(100) NOT NULL,
  `prime` varchar(20) NOT NULL,
  `thumb_img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
)");

mysql_query("CREATE TABLE IF NOT EXISTS `asins_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(255) NOT NULL,
  `asins` varchar(100) NOT NULL,
  `processed` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
)");

mysql_query("CREATE TABLE IF NOT EXISTS `ebay_asin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(255) NOT NULL,
  `asins` varchar(100) NOT NULL,
  `item_id` varchar(100) NOT NULL,
  `ebay_title` varchar(1000) NOT NULL,
  `ebay_description` varchar(9000) NOT NULL,
  `ebay_description_url` varchar(255) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `profit_percent` varchar(50) NOT NULL,
  `ebay_price` decimal(10,2) NOT NULL,
  `amazon_price` decimal(10,2) NOT NULL,
  `in_ebay` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
)");

/*mysql_query("CREATE TABLE IF NOT EXISTS `admin` (
  `user_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ebay_name` varchar(100) NOT NULL,  
  PRIMARY KEY (`user_id`)
)");*/

//mysql_query("INSERT INTO admin SET username = 'admin', password = 'admin123'");

mysql_query("CREATE TABLE IF NOT EXISTS `ebay_users` (
  `user_id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ebay_name` varchar(100) NOT NULL,
  `eBayReady` varchar(20) NOT NULL,
  `token` varchar(5000) NOT NULL,
  `Token_exp_date` varchar(100) NOT NULL,
  PRIMARY KEY (`user_id`)
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
  UNIQUE KEY `ItemID` (`ItemID`)
)");

?>
