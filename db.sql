-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Янв 07 2016 г., 23:21
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `ezonsync`
--

-- --------------------------------------------------------

--
-- Структура таблицы `asins_table`
--

DROP TABLE IF EXISTS `asins_table`;
CREATE TABLE IF NOT EXISTS `asins_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(255) NOT NULL,
  `asins` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `processed` tinyint(4) NOT NULL,
  `provider` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=414 ;

--
-- Дамп данных таблицы `asins_table`
--

INSERT INTO `asins_table` (`id`, `UserID`, `asins`, `processed`, `provider`) VALUES
(408, 12, 'B0000WM2M2', 1, 'Amazon'),
(409, 12, 'B00K5S8LEE', 1, 'Amazon'),
(407, 12, 'B004YJNK3W', 1, 'Amazon'),
(406, 12, 'B00IITERGS', 1, 'Amazon'),
(405, 12, 'B000FPE23Q', 1, 'Amazon'),
(404, 12, 'B000LDH3JC', 1, 'Amazon'),
(403, 12, 'B005FLN2FO', 1, 'Amazon'),
(401, 12, 'B000EOJPC6', 1, 'Amazon'),
(398, 12, 'B00CHWVJ2C', 1, 'Amazon'),
(399, 12, 'B004X7DIHI', 1, 'Amazon'),
(410, 12, 'B00IITERYA', 1, 'Amazon'),
(411, 12, 'B00GSPFDX0', 1, 'Amazon');

-- --------------------------------------------------------

--
-- Структура таблицы `aws_asin`
--

DROP TABLE IF EXISTS `aws_asin`;
CREATE TABLE IF NOT EXISTS `aws_asin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(255) NOT NULL,
  `asin` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `description_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(3000) COLLATE utf8_unicode_ci NOT NULL,
  `features` varchar(2000) COLLATE utf8_unicode_ci NOT NULL,
  `large_image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `medium_image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `small_image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `thumb_img` text COLLATE utf8_unicode_ci NOT NULL,
  `swatch_image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tiny_image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `weight` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `weight_string` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `dimensions` text COLLATE utf8_unicode_ci NOT NULL,
  `brand` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ean` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `list_price` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `offer_price` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_cost` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sku` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `upc` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mpn` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `prime` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=104 ;

--
-- Дамп данных таблицы `aws_asin`
--

INSERT INTO `aws_asin` (`id`, `UserID`, `asin`, `processed`, `title`, `description_url`, `description`, `features`, `large_image_url`, `medium_image_url`, `small_image_url`, `thumb_img`, `swatch_image_url`, `tiny_image_url`, `weight`, `height`, `width`, `length`, `weight_string`, `dimensions`, `brand`, `ean`, `list_price`, `offer_price`, `shipping_cost`, `currency_code`, `size`, `sku`, `upc`, `quantity`, `last_updated`, `mpn`, `prime`) VALUES
(93, 8, 'B00F1CPBQ0', 2, 'Tiny Love Take Along Mobile, Animal Friends, Blue ', '', '        Keep baby calm and content, with this mesmerizing mobile. Adorable, colorful animal friends, with black and white geometric designs. 30 minutes of continuous minutes. Fits most pack and play, travel cribs, strollers, infants seats and bassinetts. Mobile arm seperates for easy carry in any diaper bag.                      ', '<li>Fits most pack n plays, travle cribs, inafant car seats, strollers and bassinets </li><li>Electronic mobile with 30 minutes continuous music </li><li>Designated connector for each mode of use </li><li>Gives baby relazing continuity of a crib mobile, while on the go </li>', 'http://ezon.org/cl/ezonlister/uploads/amazon/B00F1CPBQ0.jpg', '', '', 'http://ecx.images-amazon.com/images/I/41x36ekfENL.jpg', '', '', 0, 0, 0, 0, '', '', 'Tiny Love', '', '', '23.04', '', 'USD', '', '', '', 30, '2014-11-04 02:30:03', '', 'Yes'),
(103, 8, 'B00JRYH3HS', 2, 'KidKraft Modern Outdoor Playhouse ', '', '        Now that is one fancy house! The Modern Outdoor Playhouse is a ton of fun, allowing kids to explore a whole new world without leaving the backyard.&#xA0; It has a hip, one-of-a-kind design and plenty of extra seating.                      ', '<li>Espresso picnic table and two benches attached to the side </li><li>Front door opens and closes </li><li>Mailbox with flag </li><li>Outdoor grill with removable lid </li><li>Reinforced wooden panels prevent warping and weathering, water-resistant </li>', 'http://ezon.org/cl/ezonlister/uploads/amazon/B00JRYH3HS.jpg', '', '', 'http://ecx.images-amazon.com/images/I/612TlZARz2L._SS400_.jpg,http://ecx.images-amazon.com/images/I/613vTuLFenL._SS400_.jpg,http://ecx.images-amazon.com/images/I/61TEjJjaqxL._SS400_.jpg,http://ecx.images-amazon.com/images/I/615xRXGbAsL._SS400_.jpg,http://ecx.images-amazon.com/images/I/61MyPumBXtL._SS400_.jpg,http://ecx.images-amazon.com/images/I/51OajSbsTFL._SS400_.jpg,http://ecx.images-amazon.com/images/I/51aA0dO9jAL._SS400_.jpg', '', '', 0, 0, 0, 0, '', '', 'KidKraft', '', '', '377.39', '', 'USD', '', '', '', 22, '2014-11-21 02:32:39', '', 'Yes');

-- --------------------------------------------------------

--
-- Структура таблицы `ebay_asin`
--

DROP TABLE IF EXISTS `ebay_asin`;
CREATE TABLE IF NOT EXISTS `ebay_asin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(255) NOT NULL,
  `asins` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` bigint(255) NOT NULL,
  `ebay_title` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `ebay_description` varchar(9000) COLLATE utf8_unicode_ci NOT NULL,
  `ebay_description_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prefix` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `handling_time` int(11) NOT NULL,
  `profit_percent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ebay_price` decimal(10,2) NOT NULL,
  `amazon_price` decimal(10,2) NOT NULL,
  `profit_ratio` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amazon_quantity` int(11) NOT NULL,
  `max_quantity` int(11) NOT NULL,
  `shipping_charge` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_option` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `return_option` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `in_ebay` tinyint(4) NOT NULL,
  `in_amazon` tinyint(4) NOT NULL,
  `in_walmart` int(11) NOT NULL,
  `product_active` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=507 ;

--
-- Дамп данных таблицы `ebay_asin`
--

INSERT INTO `ebay_asin` (`id`, `UserID`, `asins`, `item_id`, `ebay_title`, `ebay_description`, `ebay_description_url`, `prefix`, `handling_time`, `profit_percent`, `ebay_price`, `amazon_price`, `profit_ratio`, `quantity`, `amazon_quantity`, `max_quantity`, `shipping_charge`, `shipping_option`, `return_option`, `in_ebay`, `in_amazon`, `in_walmart`, `product_active`) VALUES
(506, 8, '14165678', 331437979193, 'White Kitchen Cart Island Furniture Mobile Storage Butcher Block Cookware Wood', '', '', '', 0, '', '269.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(505, 8, 'B0085C96DC', 231443397744, 'All Clad Pots Stainless Steel Cookware Set 12 Piece Emeril Pan Cooking Chef ', '', '', '', 0, '', '249.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(504, 8, '10244646', 331437081845, 'Tiffany Rose Mission Style Table Lamp Desk Den Office 60 Watt Stained Glass', '', '', '', 0, '', '179.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(503, 8, 'B000BY2DJQ', 231442072056, 'BARSKA Telescopes Tripod Astronomical Magnifying Scope Astronomy Kids Adult', '', '', '', 0, '', '69.66', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(502, 8, 'B001CFRF7I', 231441964960, 'Indoor Outdoor Mr. Heater Propane Garage Porch Fishing Camping Camping 9,000 BTU', '', '', '', 0, '', '89.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(501, 8, 'B002G51BZU', 231441964959, 'Mr. Heater Portable 9,000 BTU Ice Fishing Hunting Camping Propane Garage Porch', '', '', '', 0, '', '104.99', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(500, 8, '16070876', 231441002038, 'Espresso China Cabinet Plates Silverware Buffet Hutch Mission Glass Doors Dining', '', '', '', 0, '', '169.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(499, 8, '16070871', 331434457732, 'Cabinet Mission China Buffet Dark Wood Sliding Door Storage Plates Furniture', '', '', '', 0, '', '159.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(498, 8, 'B00AZBIXHG', 231440780395, 'BISSELL Bagless Canister Vacuum Cleaner Blue Floors Carpet Filter No Mess Easy', '', '', '', 0, '', '63.99', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(497, 8, 'HMB1766', 231439048189, 'Digital Hamilton Beach 2 Way FlexBrew Coffee Maker Tea Brewing Keurig K Cup Coco', '', '', '', 0, '', '117.64', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(495, 8, 'B00006RH5I', 331431372882, 'Celestron Telescope Eye Piece Filter Accessory Kit Set Astronomical Astronomy', '', '', '', 0, '', '158.99', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(496, 8, 'B0007UQNV8', 331431372884, 'Celestron Telescope Astronomical Scope Astronomy Accessory Eye Lens Piece ', '', '', '', 0, '', '68.99', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(494, 8, 'B00J261PGQ', 231436977185, 'New 1000-Watt AC/DC Portable Gas Generator Power RV Quiet Electric Emergency', '', '', '', 0, '', '184.09', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(493, 8, 'B002WB2G9I', 331430415026, 'Black First Years Stroller Carrier Infant Storage Walker LIghtweight Unisex Kid', '', '', '', 0, '', '89.99', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(491, 8, 'B005NNF0YU', 231434759658, 'Black & Decker 20-Volt Power Drill Electric Screwdriver Cordless Tool Driver 20v', '', '', '', 0, '', '72.54', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(492, 8, 'B00FJWY2EO', 231434770376, 'Black & Decker 20-Volt Combo Drill Driver Impact Tool Cordless Power Bundle', '', '', '', 0, '', '178.44', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(490, 8, '12246636', 331427902352, 'Dinning Room Set Antique Country 7 Piece Distressed Cherry Chairs Vintage Cream', '', '', '', 0, '', '1590.45', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(489, 8, 'B000F6X5BK', 331427022417, 'Gift Basket Box Lady Pink Lotion Picture Chest Candle Rose Cookies Anniversary', '', '', '', 0, '', '92.52', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(488, 8, 'B008EYP5HC', 231433575834, 'New Digital Krups Coffee Maker and Espresso Latte Machine Combination Black ', '', '', '', 0, '', '153.81', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(486, 8, 'B00CSY9KAW', 331423245871, 'Tommy Bahama Men Cologne Spray 3.4 Fluid Ounce Men Male Fragrance Box Gift Amber', '', '', '', 0, '', '54.29', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(487, 8, 'B000MLHMAS', 331426945721, 'Celestron Telescope Tripod Astronomical Magnifying Scope Astronomy Refractor', '', '', '', 0, '', '135.56', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(485, 8, 'B0006B6U34', 231429892205, 'Large Gift Basket Boxes Theme Mom Lady Lotion Body Slippers', '', '', '', 0, '', '90.77', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(484, 8, 'B000GPV2QA', 331423244874, 'Car Multi Battery Booster Charger Jump Starter Built In Air Compressor Light ', '', '', '', 0, '', '130.86', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(483, 8, 'B000JFHNQA', 331423243342, 'Jump Starter Portable Battery Booster Charger Pack 1500 Amps Car Boat Peak', '', '', '', 0, '', '168.75', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(482, 8, 'B002S7H920', 231429889973, 'Video Gaming Chair Wireless Xbox Play Station 3 Wii PC Seat Gamer Sound Speaker', '', '', '', 0, '', '191.55', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(480, 8, '14974452', 331418776917, 'Futon Couch Sleeper Furniture Cabin Queen Sofa Convertible Mattress Green', '', '', '', 0, '', '744.99', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(481, 8, 'B00KLTA5HS', 231429889972, 'Hummingbird Nectar Feeders Bird Squirrel Proof Unique birdfeeders Blown Glass ', '', '', '', 0, '', '36.97', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(479, 8, 'GSEB125', 331418746339, 'All-Clad Stainless 2 Qt Sauce Pot Pan Double Boiler With Lid Lifetime Warranty', '', '', '', 0, '', '237.92', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(478, 8, 'HMS1268', 331418729563, 'Hutch Mission Vintage Furniture Dining Room Wood China Cabinet Storage ', '', '', '', 0, '', '1579.60', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(477, 8, 'KWJ1155', 331418713163, 'New Pattern Quilt Fabric Cotton Bed Vintage Blue White King Flower Bedding', '', '', '', 0, '', '103.09', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(476, 8, 'CRY1353', 331418692908, 'Island Storage Cabinet Steel Kitchen Utility Cart Wood Furniture Rolling Vintage', '', '', '', 0, '', '500.98', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(475, 8, 'AAC1475', 231425283123, 'All Clad 14 Piece Set Kitchen Pots Pans Cookware Sets Cooking  Stainless Steel', '', '', '', 0, '', '1299.99', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(473, 8, '12960331', 231443324500, 'Cherry Low Saddleback Bar Stool Wood Sturdy Set of Two 2 Dinning Furniture', '', '', '', 0, '', '119.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(474, 8, '15072614', 231443324501, 'Rustic Lodge Wood Counter Stools (Set of 2) Dining Bar Counter Top Heavy Duty', '', '', '', 0, '', '313.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(472, 8, '10219152', 231443277153, 'Tiffany Style Mission White Table Lamp Bronze 60 Watt Desk Office Den Living', '', '', '', 0, '', '99.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(471, 8, '15789669', 231443277148, 'Tiffany Style Mission Table and Floor Lamp Set Stain Glass Vintage Combo 60w', '', '', '', 0, '', '219.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(470, 8, '14978469', 331437059430, 'Tiffany Style Contemporary Table Lamp Light Bright Contemporary Zinc Bronze', '', '', '', 0, '', '148.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(469, 8, '1149742', 331437058284, 'Tiffany Style Table Lamp Vintage Antique Bronze Living Room Den Family', '', '', '', 0, '', '138.99', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(468, 8, '13883369', 331437058282, 'Aztec Lighting Bronze Tiffany Style Table Lamp Bright Contemporary Furniture ', '', '', '', 0, '', '94.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(467, 8, '1135221', 231443253748, 'Tiffany Style Table Lamp Light Colorful Bright Contemporary Den Living Room Desk', '', '', '', 0, '', '109.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(466, 8, '14806474', 231443253744, 'Tiffany Style Feathered Table Lamp Vintage Contemporary Bronze Stained Glass', '', '', '', 0, '', '119.99', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(465, 8, '15093309', 231443253738, 'Tiffany Style Contemporary Table Lamp Light Colorful Bright Contemporary Bronze', '', '', '', 0, '', '79.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(464, 8, '12935667', 231442256166, 'Tiffany Style Victorian Lighted Base Table Lamp Stained Glass Vintage Bronze', '', '', '', 0, '', '179.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(463, 8, '11402032', 231437824096, 'White Kitchen Pantry Standing Storage Utility Cabinet Cupboard Cans Spice Jar', '', '', '', 0, '', '134.99', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(462, 8, 'B000N22JX6', 331411929691, 'New 1500w Electric Portable Lasko Ceramic Oscillating Space Heater Room Remote', '', '', '', 0, '', '65.08', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(461, 8, 'B000OCLFX0', 231436930609, 'Chrome Lighted Illuminated Mirror Makeup Vanity Magnifying Fog Glare Dual Sided', '', '', '', 0, '', '59.25', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(460, 8, 'B000U0F9GA', 231435761768, 'Illuminated Vanity Mirror Makeup Lighted Magnifying Bronze Make Up Cosmetic Oval', '', '', '', 0, '', '60.67', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(459, 8, 'B003JFBXMM', 231434632605, 'Illuminated Vanity Mirror Makeup Lighted Magnifying Beauty Make Up Cosmetic Oval', '', '', '', 0, '', '48.25', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(458, 8, '13652290', 331427992553, '12 Piece Comforter Set Blue White Brown Floral Sheets Sham Pillow Queen Skirt', '', '', '', 0, '', '152.00', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(457, 8, '16098791', 331427992551, '9 Piece Comforter Set Queen White Brown Floral Tree Branches Taupe Patterned ', '', '', '', 0, '', '101.11', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(456, 8, '13532715', 331427992549, '24 piece Brown Contemporary Bed in a Bag Sheet Set Sham Queen Valance Pillow', '', '', '', 0, '', '178.44', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(455, 8, '12332923', 231434494716, 'Tommy Hilfiger Men Cologne Spray Fragrance Perfume  Parfum Scent Male Smell', '', '', '', 0, '', '39.95', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(454, 8, '12332921', 231434494115, 'Tommy Hilfiger Girl Cologne Spray Fragrance Perfume Parfum Scent Lady Smell', '', '', '', 0, '', '39.64', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(453, 8, 'B000IELD9A', 231414540814, 'New 15 Piece Kitchen Set Tool Cooking Utensils Spoon Spatula Whisk Cookware', '', '', '', 0, '', '131.99', '0.00', '0.00', 1, 0, 1, '', '', '', 1, 1, 0, 0),
(452, 8, 'B0006B6XIQ', 231433626232, 'Gift Basket Box Caviar Cheese Godiva Chocolate Mocha Cookies Salmon Mustard', '', '', '', 0, '', '109.05', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0),
(451, 8, 'B005EF0HU4', 331427038075, 'Italy Gift Basket Olives Chocolate Candies Crackers Cheese Salami Coffee', '', '', '', 0, '', '77.30', '0.00', '0.00', 2, 0, 2, '', '', '', 1, 1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `ebay_config`
--

DROP TABLE IF EXISTS `ebay_config`;
CREATE TABLE IF NOT EXISTS `ebay_config` (
  `user_id` int(11) NOT NULL,
  `title_prefix` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `max_quantity` int(11) NOT NULL,
  `sku` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `profit_percentage` decimal(10,2) NOT NULL,
  `price_formula` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dispatch_time` int(11) NOT NULL,
  `listing_duration` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `listing_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `condition_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `refund_option` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `return_accept_option` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `return_days` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_service` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL,
  `payment_method` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `ebay_config`
--

INSERT INTO `ebay_config` (`user_id`, `title_prefix`, `max_quantity`, `sku`, `profit_percentage`, `price_formula`, `dispatch_time`, `listing_duration`, `listing_type`, `condition_id`, `refund_option`, `return_accept_option`, `return_days`, `shipping_service`, `shipping_type`, `shipping_cost`, `payment_method`, `paypal_address`, `postal_code`) VALUES
(1, 'New', 2, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', 'MoneyBack', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '2.00', 'PayPal', 'rinsad@gmail.com', '71500'),
(2, 'New', 1, '', '30.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', 'MoneyBack', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', 'rinsad@gmail.com', '71500'),
(4, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(5, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(6, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(7, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(8, 'New', 2, 'AMZ$ASIN', '30.00', '003', 3, 'Days_7', 'FixedPriceItem', '1000', 'MoneyBack', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '10.00', 'PayPal', 'ezebaytemplates@gmail.com', '94536'),
(9, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(10, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(11, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(12, 'New', 2, 'AMZ$ASIN', '4.00', '001', 1, 'Days_30', 'FixedPriceItem', '1000', 'MoneyBack', 'ReturnsAccepted', 'Days_30', 'UPSGround', 'Flat', '0.00', 'PayPal', 'c_hix@ca.rr.com', '95125'),
(13, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(14, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', ''),
(15, 'New', 1, '', '25.00', '001', 1, 'GTC', 'FixedPriceItem', '1000', '', 'ReturnsAccepted', 'Days_14', 'UPSGround', 'Flat', '0.00', 'PayPal', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `ebay_cron`
--

DROP TABLE IF EXISTS `ebay_cron`;
CREATE TABLE IF NOT EXISTS `ebay_cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ItemID` bigint(20) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `ebay_settings_narek`
--

DROP TABLE IF EXISTS `ebay_settings_narek`;
CREATE TABLE IF NOT EXISTS `ebay_settings_narek` (
  `id_ebay_settings` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(100) NOT NULL,
  `dispatch_time_max` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `listing_duration` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `condition_id` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `listing_type` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `refund_option` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `return_within` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_service` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_type` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `shipping_cost` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `payment_method` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_email` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `temp_code` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_ebay_settings`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `ebay_settings_narek`
--

INSERT INTO `ebay_settings_narek` (`id_ebay_settings`, `id_user`, `dispatch_time_max`, `listing_duration`, `condition_id`, `listing_type`, `refund_option`, `return_within`, `shipping_service`, `shipping_type`, `shipping_cost`, `payment_method`, `paypal_email`, `postal_code`, `temp_code`) VALUES
(7, 1, '444', 'Days_1', '5000', 'FixedPriceItem', 'MoneyBackOrExchange', 'Days_10', '444', 'Flat', '44', 'PayPal', 'narektovmasyanv@gmail.com', 'qqqq', 'qqqq     ');

-- --------------------------------------------------------

--
-- Структура таблицы `ebay_users`
--

DROP TABLE IF EXISTS `ebay_users`;
CREATE TABLE IF NOT EXISTS `ebay_users` (
  `user_id` int(255) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `paypal_address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ebay_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dev_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `app_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `cert_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `eBayReady` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `Token_exp_date` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `amazon_username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `amazon_publickey` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `amazon_privatekey` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `sandbox` tinyint(4) NOT NULL,
  `postal_code` int(11) NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_methods` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `group` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `footer` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Дамп данных таблицы `ebay_users`
--

INSERT INTO `ebay_users` (`user_id`, `first_name`, `name`, `username`, `password`, `email`, `paypal_address`, `ebay_name`, `dev_name`, `app_name`, `cert_name`, `eBayReady`, `token`, `Token_exp_date`, `amazon_username`, `amazon_publickey`, `amazon_privatekey`, `sandbox`, `postal_code`, `location`, `payment_methods`, `group`, `footer`) VALUES
(14, 'testuser', '', 'roma', '202cb962ac59075b964b07152d234b70', 'fr2@mail.ru', 'fr2@mail.ru', '', '', '', '', '', '', '', '', '', '', 1, 55317, 'Mines Chanhassen', 'VisaMC,PayPal', 'admin', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `user_products`
--

DROP TABLE IF EXISTS `user_products`;
CREATE TABLE IF NOT EXISTS `user_products` (
  `UserID` int(255) NOT NULL,
  `ItemID` bigint(255) NOT NULL,
  `Qty` int(100) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `SKU` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ItemUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `VendorPrice` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ProfitRatio` decimal(10,2) NOT NULL,
  `VendorQty` int(11) NOT NULL,
  `max_quantity` int(11) NOT NULL,
  `product_active` tinyint(4) NOT NULL,
  `VendorUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `lastUpdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `ItemID` (`ItemID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user_products`
--

INSERT INTO `user_products` (`UserID`, `ItemID`, `Qty`, `Price`, `Title`, `SKU`, `Image`, `ItemUrl`, `VendorPrice`, `ProfitRatio`, `VendorQty`, `max_quantity`, `product_active`, `VendorUrl`, `sort`, `lastUpdate`) VALUES
(14, 110155630045, 3, '163.38', 'Mr. Heater MH18B, Portable Propane Heater ', 'B0002WRHE8', '->GalleryURL', '->ViewItemURL', '123.99', '15.00', 30, 3, 0, 'http://www.amazon.com/dp/B0002WRHE8', 0, '2015-02-22 17:50:52'),
(14, 110155630177, 1, '167.75', 'Mr. Heater MH18B, Portable Propane Heater ', 'B0002WRHE8', '->GalleryURL', '->ViewItemURL', '123.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B0002WRHE8', 1, '2015-02-21 08:07:46'),
(2, 231454408858, 1, '121.75', 'Tiffany Style Table Lamp Light Colorful Furniture Den Living Room Desk Deco', 'OS1135221', '->GalleryURL', '->ViewItemURL', '89.99', '15.00', 12, 0, 0, 'http://www.overstock.com/search/1135221', 19, '2015-02-26 10:57:59'),
(14, 110156194318, 1, '70.57', 'BISSELL Zing Bagless Canister Vacuum, Caribbean Blue ', 'B00AZBIXHG', 'http://i.ebayimg.sandbox.ebay.com/00/s/MzAwWDI2Mg==/z/MbcAAOSwX-hUzs2F/$_1.JPG?set_id=8800004005', 'http://cgi.sandbox.ebay.com/BISSELL-Zing-Bagless-Canister-Vacuum-Caribbean-Blue-/110156194318', '49.99', '15.00', 30, 2, 0, 'http://www.amazon.com/dp/B00AZBIXHG', 0, '2015-02-22 17:50:52'),
(2, 331458532216, 1, '231.34', 'Rocker Gaming Chair Wireless Xbox Play Station 3 Wii PC Seat Gamer Sound Speaker', 'B002S7H920', 'http://thumbs.ebaystatic.com/pict/3314585322166464_1.jpg', 'http://www.ebay.com/itm/Rocker-Gaming-Chair-Wireless-Xbox-Play-Station-3-Wii-PC-Seat-Gamer-Sound-Speaker-/331458532216', '170.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002S7H920', 6, '2015-02-26 10:57:12'),
(2, 331458558483, 1, '133.94', 'DEWALT Tools 18-Volt Power Cordless Compact Drill Driver Screw Battery Kit ', 'B002RLR0EY', 'http://thumbs.ebaystatic.com/pict/3314585584836464_1.jpg', 'http://www.ebay.com/itm/DEWALT-Tools-18-Volt-Power-Cordless-Compact-Drill-Driver-Screw-Battery-Kit-/331458558483', '99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002RLR0EY', 8, '2015-02-26 10:57:23'),
(2, 331461561781, 2, '196.11', 'Celestron Telescope Tripod Astronomical Magnifying Scope Astronomy Refractor', 'B000MLHMAS', 'http://thumbs.ebaystatic.com/pict/3314615617816464_1.jpg', 'http://www.ebay.com/itm/Celestron-Telescope-Tripod-Astronomical-Magnifying-Scope-Astronomy-Refractor-/331461561781', '144.95', '15.00', 0, 0, 0, 'http://www.amazon.com/dp/B000MLHMAS', 0, '2015-02-22 17:51:57'),
(2, 331461562090, 1, '37.84', 'Humming Nectar Feeders Bird Squirrel Proof Unique Feeders Blown Glass Stained ', 'B00KLTA5HS', 'http://thumbs.ebaystatic.com/pict/3314615620906464_1.jpg', 'http://www.ebay.com/itm/Humming-Nectar-Feeders-Bird-Squirrel-Proof-Unique-Feeders-Blown-Glass-Stained-/331461562090', '27.97', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00KLTA5HS', 5, '2015-02-26 10:57:07'),
(2, 331461562453, 1, '1555.87', 'All Clad 14 Piece Set Kitchen Pots Pans Cookware Sets Cooking  Stainless Steel', 'WFAAC1475', 'http://thumbs.ebaystatic.com/pict/3314615624536464_1.jpg', 'http://www.ebay.com/itm/All-Clad-14-Piece-Set-Kitchen-Pots-Pans-Cookware-Sets-Cooking-Stainless-Steel-/331461562453', '1149.99', '15.00', 30, 0, 0, 'http://www.wayfair.com/All-Clad-Stainless-Steel-14-Piece-Cookware-Set-I-401716-AAC1475.html', 4, '2015-02-26 10:57:04'),
(2, 331461764592, 2, '143.29', 'Black & Decker 20-Volt Combo Drill Driver Impact Tool Cordless Power Bundle', 'B00FJWY2EO', 'http://thumbs.ebaystatic.com/pict/3314617645926464_1.jpg', 'http://www.ebay.com/itm/Black-Decker-20-Volt-Combo-Drill-Driver-Impact-Tool-Cordless-Power-Bundle-/331461764592', '105.91', '15.00', 27, 0, 0, 'http://www.amazon.com/dp/B00FJWY2EO', 3, '2015-02-26 10:57:00'),
(2, 331461765190, 1, '135.28', 'Car Multi Battery Booster Charger Jump Starter Built Air Compressor Light RV ATV', 'B000GPV2QA', 'http://thumbs.ebaystatic.com/pict/3314617651906464_1.jpg', 'http://www.ebay.com/itm/Car-Multi-Battery-Booster-Charger-Jump-Starter-Built-Air-Compressor-Light-RV-ATV-/331461765190', '99.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000GPV2QA', 2, '2015-02-26 10:56:57'),
(2, 231465816081, 1, '512.76', 'Island Storage Cabinet Steel Kitchen Utility Cart Wood Furniture Rolling Vintage', 'WFCRY1353', 'http://thumbs.ebaystatic.com/pict/2314658160816464_1.jpg', 'http://www.ebay.com/itm/Island-Storage-Cabinet-Steel-Kitchen-Utility-Cart-Wood-Furniture-Rolling-Vintage-/231465816081', '379', '15.00', 30, 0, 0, 'http://www.wayfair.com/Crosley-Alexandria-Kitchen-Island-with-Stainless-Steel-Top-KF30002EWH-CRY1353.html', 0, '2015-02-22 17:52:37'),
(2, 231465816082, 1, '146.25', 'New Pine Kitchen Pantry Storage Utility Cabinet Cupboard Organizer Jar Pantry', 'OS11402032', 'http://thumbs.ebaystatic.com/pict/2314658160826464_1.jpg', 'http://www.ebay.com/itm/New-Pine-Kitchen-Pantry-Storage-Utility-Cabinet-Cupboard-Organizer-Jar-Pantry-/231465816082', '108.1', '15.00', 20, 0, 0, 'http://www.overstock.com/search/11402032', 1, '2015-02-26 10:47:55'),
(2, 231465816083, 1, '146.25', 'White Kitchen Pantry Standing Storage Utility Cabinet Cupboard Cans Spice Jar', 'OS11402032', 'http://thumbs.ebaystatic.com/pict/2314658160836464_1.jpg', 'http://www.ebay.com/itm/White-Kitchen-Pantry-Standing-Storage-Utility-Cabinet-Cupboard-Cans-Spice-Jar-/231465816083', '108.1', '15.00', 20, 0, 0, 'http://www.overstock.com/search/11402032', 2, '2015-02-26 10:48:00'),
(2, 331463539590, 1, '116.34', 'New Pattern Quilt Fabric Cotton Bed Vintage Blue White King Flower Bedding', 'WFKWJ1155', 'http://thumbs.ebaystatic.com/pict/3314635395906464_1.jpg', 'http://www.ebay.com/itm/New-Pattern-Quilt-Fabric-Cotton-Bed-Vintage-Blue-White-King-Flower-Bedding-/331463539590', '85.99', '15.00', 30, 0, 0, 'http://www.wayfair.com/American-Traditions-Rose-Blossom-Queen-Quilt-PQW1406BFQ-1100-KWJ1155.html', 3, '2015-02-26 10:48:05'),
(2, 331463539591, 1, '358.52', 'Closet System Storage Wood Clothing Bedroom Furniture Home New Maple Shelf ', 'OS11051236', 'http://thumbs.ebaystatic.com/pict/3314635395916464_1.jpg', 'http://www.ebay.com/itm/Closet-System-Storage-Wood-Clothing-Bedroom-Furniture-Home-New-Maple-Shelf-/331463539591', '264.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/11051236', 4, '2015-02-26 10:48:10'),
(2, 331463539592, 1, '93.22', 'Large Gift Basket Boxes Theme Mom Lady Lotion Body Slippers Anniversary Women', 'B0006B6U34', 'http://thumbs.ebaystatic.com/pict/3314635395926464_1.jpg', 'http://www.ebay.com/itm/Large-Gift-Basket-Boxes-Theme-Mom-Lady-Lotion-Body-Slippers-Anniversary-Women-/331463539592', '68.9', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B0006B6U34', 5, '2015-02-26 10:48:16'),
(2, 231467306984, 2, '171.82', 'Celestron Telescope Eye Piece Filter Accessory Kit Set Astronomical Astronomy', 'B00006RH5I', 'http://thumbs.ebaystatic.com/pict/2314673069846464_1.jpg', 'http://www.ebay.com/itm/Celestron-Telescope-Eye-Piece-Filter-Accessory-Kit-Set-Astronomical-Astronomy-/231467306984', '127', '15.00', 3, 0, 0, 'http://www.amazon.com/dp/B00006RH5I', 6, '2015-02-26 10:48:23'),
(2, 231467307130, 1, '182.09', 'New 1000-Watt AC/DC Portable Gas Generator Power RV Quiet Electric Emergency', 'B00J261PGQ', 'http://thumbs.ebaystatic.com/pict/2314673071306464_1.jpg', 'http://www.ebay.com/itm/New-1000-Watt-AC-DC-Portable-Gas-Generator-Power-RV-Quiet-Electric-Emergency-/231467307130', '134.59', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00J261PGQ', 7, '2015-02-26 10:48:30'),
(2, 331465521508, 1, '37.87', 'Tommy Hilfiger Girl Cologne Spray Fragrance Perfume Parfum Scent Lady 3.4oz', 'OS12332921', 'http://thumbs.ebaystatic.com/pict/3314655215086464_1.jpg', 'http://www.ebay.com/itm/Tommy-Hilfiger-Girl-Cologne-Spray-Fragrance-Perfume-Parfum-Scent-Lady-3-4oz-/331465521508', '27.99', '15.00', 5, 0, 0, 'http://www.overstock.com/search/12332921', 8, '2015-02-26 10:48:35'),
(2, 231486854803, 1, '189.40', '24 piece Brown Contemporary Bed in a Bag Sheet Set Sham Queen Valance Pillow', 'OS13532715', 'http://thumbs.ebaystatic.com/pict/2314868548036464_1.jpg', 'http://www.ebay.com/itm/24-piece-Brown-Contemporary-Bed-Bag-Sheet-Set-Sham-Queen-Valance-Pillow-/231486854803', '139.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/13532715', 0, '2015-03-02 08:39:50'),
(2, 231469834122, 1, '109.39', 'Mr. Heater Portable 9,000 BTU Ice Fishing Hunting Camping Propane Garage Porch', 'B002G51BZU', 'http://thumbs.ebaystatic.com/pict/2314698341226464_1.jpg', 'http://www.ebay.com/itm/Mr-Heater-Portable-9-000-BTU-Ice-Fishing-Hunting-Camping-Propane-Garage-Porch-/231469834122', '80.85', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002G51BZU', 1, '2015-03-03 16:01:34'),
(2, 231469834123, 1, '0.00', 'Furniture China Cabinet Plate Silverware Buffet Hutch Mission Glass Doors Dining', 'OS16070876', 'http://thumbs.ebaystatic.com/pict/2314698341236464_1.jpg', 'http://www.ebay.com/itm/Furniture-China-Cabinet-Plate-Silverware-Buffet-Hutch-Mission-Glass-Doors-Dining-/231469834123', '', '15.00', 0, 0, 0, 'http://www.overstock.com/search/16070876', 9, '2015-02-26 10:57:26'),
(2, 331468195897, 1, '93.27', 'Indoor Outdoor Mr. Heater Propane Garage Porch Fishing Camping Camping 9,000 BTU', 'B001CFRF7I', 'http://thumbs.ebaystatic.com/pict/3314681958976464_1.jpg', 'http://www.ebay.com/itm/Indoor-Outdoor-Mr-Heater-Propane-Garage-Porch-Fishing-Camping-Camping-9-000-BTU-/331468195897', '68.94', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B001CFRF7I', 11, '2015-02-26 10:48:55'),
(2, 331468240306, 1, '255.69', 'Leather Storage Ottoman Living Room Den Office Furniture Sofa Chair Brown', 'WFNFN1183', 'http://thumbs.ebaystatic.com/pict/3314682403066464_1.jpg', 'http://www.ebay.com/itm/Leather-Storage-Ottoman-Living-Room-Den-Office-Furniture-Sofa-Chair-Brown-/331468240306', '188.99', '15.00', 30, 0, 0, 'http://www.wayfair.com/Home-Loft-Concept-Leeds-Storage-Cocktail-Ottoman-X1361330-NFN1183.html', 2, '2015-03-03 16:01:42'),
(2, 331469298593, 1, '270.57', 'All Clad Pots Stainless Steel Cookware Set 12 Piece Emeril Pan Cooking Chef ', 'B0085C96DC', 'http://thumbs.ebaystatic.com/pict/3314692985936464_1.jpg', 'http://www.ebay.com/itm/All-Clad-Pots-Stainless-Steel-Cookware-Set-12-Piece-Emeril-Pan-Cooking-Chef-/331469298593', '199.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B0085C96DC', 13, '2015-02-26 10:49:10'),
(2, 331471657180, 1, '135.28', 'Dirt Devil SD40050 Dash Bagless Canister Vaccum Floors Carpet Rugs Cleaning ', 'B00N4MHJH8', 'http://thumbs.ebaystatic.com/pict/3314716571806464_1.jpg', 'http://www.ebay.com/itm/Dirt-Devil-SD40050-Dash-Bagless-Canister-Vaccum-Floors-Carpet-Rugs-Cleaning-/331471657180', '99.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00N4MHJH8', 3, '2015-03-03 16:01:48'),
(2, 231478182138, 1, '58.16', 'Hamilton Beach Coffee Brew 12 Cup Programmable Maker Digital Timer Automatic', 'B001K66LPQ', 'http://thumbs.ebaystatic.com/pict/2314781821386464_1.jpg', 'http://www.ebay.com/itm/Hamilton-Beach-Coffee-Brew-12-Cup-Programmable-Maker-Digital-Timer-Automatic-/231478182138', '42.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B001K66LPQ', 19, '2015-02-26 11:32:14'),
(2, 331478427676, 1, '51.24', 'Gift Basket Box Caviar Cheese Godiva Chocolate Mocha Cookies Salmon Mustard', 'B0006B6XIQ', 'http://thumbs.ebaystatic.com/pict/3314784276766464_1.jpg', 'http://www.ebay.com/itm/Gift-Basket-Box-Caviar-Cheese-Godiva-Chocolate-Mocha-Cookies-Salmon-Mustard-/331478427676', '37.87', '15.00', 10, 0, 0, 'http://www.amazon.com/dp/B0006B6XIQ', 20, '2015-02-26 11:32:21'),
(2, 331478427763, 1, '81.16', 'Italy Gift Basket Olives Chocolate Candies Crackers Cheese Salami Coffee', 'B005EF0HU4', 'http://thumbs.ebaystatic.com/pict/3314784277636464_1.jpg', 'http://www.ebay.com/itm/Italy-Gift-Basket-Olives-Chocolate-Candies-Crackers-Cheese-Salami-Coffee-/331478427763', '59.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005EF0HU4', 21, '2015-02-26 11:32:27'),
(2, 231483582533, 1, '334.16', 'White Kitchen Cart Island Furniture Mobile Storage Butcher Block Cookware Wood', 'OS14165678', 'http://thumbs.ebaystatic.com/pict/2314835825336464_1.jpg', 'http://www.ebay.com/itm/White-Kitchen-Cart-Island-Furniture-Mobile-Storage-Butcher-Block-Cookware-Wood-/231483582533', '246.99', '15.00', 6, 0, 0, 'http://www.overstock.com/search/14165678', 0, '2015-03-09 23:07:09'),
(2, 331483695737, 4, '79.80', 'BARSKA Telescope Tripod Astronomical Magnifying Scope Astronomy Kids Adult', 'B000BY2DJQ', 'http://thumbs.ebaystatic.com/pict/3314836957376464_1.jpg', 'http://www.ebay.com/itm/BARSKA-Telescope-Tripod-Astronomical-Magnifying-Scope-Astronomy-Kids-Adult-/331483695737', '58.98', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000BY2DJQ', 23, '2015-02-26 11:32:39'),
(2, 231484435289, 1, '54.10', 'Hamilton Beach 12 Cup Digital Coffee Maker Black Stainless Steel Pot Digital', 'B0041A5H5S', 'http://thumbs.ebaystatic.com/pict/2314844352896464_1.jpg', 'http://www.ebay.com/itm/Hamilton-Beach-12-Cup-Digital-Coffee-Maker-Black-Stainless-Steel-Pot-Digital-/231484435289', '39.99', '15.00', 27, 0, 0, 'http://www.amazon.com/dp/B0041A5H5S', 1, '2015-03-09 23:07:18'),
(2, 331486233581, 1, '273.13', 'Champion Power Equipment Portable Generator Gas Snow Storms 1500 Watt Portable', 'B009E26LLC', 'http://thumbs.ebaystatic.com/pict/3314862335816464_1.jpg', 'http://www.ebay.com/itm/Champion-Power-Equipment-Portable-Generator-Gas-Snow-Storms-1500-Watt-Portable-/331486233581', '201.88', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B009E26LLC', 25, '2015-02-26 11:32:53'),
(2, 231486854801, 1, '945.71', 'All Clad Stainless 2 Qt Sauce Pot Pan Double Boiler Lid Cookware Cooking Kitchen', 'HNGSEB125', 'http://thumbs.ebaystatic.com/pict/2314868548016464_1.jpg', 'http://www.ebay.com/itm/All-Clad-Stainless-2-Qt-Sauce-Pot-Pan-Double-Boiler-Lid-Cookware-Cooking-Kitchen-/231486854801', '699<span>.99', '15.00', 100, 0, 0, 'http://search.hayneedle.com/search/index.cfm?Ntt=GSEB125', 1, '2015-02-26 10:56:52'),
(2, 231486854802, 1, '119.05', 'Tiffany Style Table Lamp Light Colorful Furniture Den Living Room Desk Deco', 'OS1135221', 'http://thumbs.ebaystatic.com/pict/2314868548026464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Table-Lamp-Light-Colorful-Furniture-Den-Living-Room-Desk-Deco-/231486854802', '87.99', '15.00', 13, 1, 0, 'http://www.overstock.com/search/1135221', 2, '2015-03-09 23:07:25'),
(2, 331486251313, 1, '0.00', 'Cherry Low Saddleback Bar Stool Wood Sturdy Dinning Furniture Kitchen Seat Chair', 'OS12960331', 'http://thumbs.ebaystatic.com/pict/3314862513136464_1.jpg', 'http://www.ebay.com/itm/Cherry-Low-Saddleback-Bar-Stool-Wood-Sturdy-Dinning-Furniture-Kitchen-Seat-Chair-/331486251313', '98.99', '15.00', 0, 0, 0, 'http://www.overstock.com/search/12960331', 0, '2015-02-22 17:50:52'),
(2, 231486854804, 1, '166.86', 'Jump Starter Portable Battery Booster Charger Pack 1500 Amps Car Boat Peak', 'B000JFHNQA', 'http://thumbs.ebaystatic.com/pict/2314868548046464_1.jpg', 'http://www.ebay.com/itm/Jump-Starter-Portable-Battery-Booster-Charger-Pack-1500-Amps-Car-Boat-Peak-/231486854804', '123.33', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000JFHNQA', 7, '2015-02-26 10:57:17'),
(2, 331486251314, 1, '17.59', 'Nautica Classic Men''s 3.4-ounce Eau de Toilette Spray Cologne Fragrance Perfume', 'OS12332140', 'http://thumbs.ebaystatic.com/pict/3314862513146464_1.jpg', 'http://www.ebay.com/itm/Nautica-Classic-Mens-3-4-ounce-Eau-Toilette-Spray-Cologne-Fragrance-Perfume-/331486251314', '13', '15.00', 5, 0, 0, 'http://www.overstock.com/search/12332140', 3, '2015-03-09 23:07:32'),
(2, 231486856912, 1, '209.62', 'Tiffany Style Victorian Lighted Base Table Lamp Stained Glass Vintage Bronze', 'OS12935667', 'http://thumbs.ebaystatic.com/pict/2314868569126464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Victorian-Lighted-Base-Table-Lamp-Stained-Glass-Vintage-Bronze-/231486856912', '154.94', '15.00', 20, 0, 0, 'http://www.overstock.com/search/12935667', 28, '2015-02-26 11:33:08'),
(2, 231486857294, 1, '142.03', 'Tiffany Style Table Lamp Vintage Antique Bronze Living Room Den Family', 'OS1582007', 'http://thumbs.ebaystatic.com/pict/2314868572946464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Table-Lamp-Vintage-Antique-Bronze-Living-Room-Den-Family-/231486857294', '104.98', '15.00', 18, 0, 0, 'http://www.overstock.com/search/1582007', 4, '2015-03-09 23:07:39'),
(14, 110156225356, 168, '167.75', 'Mr. Heater MH18B, Portable Propane Heater ', 'B0002WRHE8', 'http://i.ebayimg.sandbox.ebay.com/00/s/NTAwWDQ5Nw==/z/r04AAOSwwIFU0rPH/$_1.JPG?set_id=8800005007', 'http://cgi.sandbox.ebay.com/Mr-Heater-MH18B-Portable-Propane-Heater-/110156225356', '123.99', '15.00', 0, 1, 0, 'http://www.amazon.com/dp/B0002WRHE8', 0, '2015-02-22 18:56:48'),
(2, 231487680193, 1, '119.06', 'Black & Decker 20-Volt MAX Lithium-Ion Drill and Power Tool Project Kit Hammer', 'B00C625KVE', 'http://thumbs.ebaystatic.com/pict/2314876801936464_1.jpg', 'http://www.ebay.com/itm/Black-Decker-20-Volt-MAX-Lithium-Ion-Drill-and-Power-Tool-Project-Kit-Hammer-/231487680193', '88', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00C625KVE', 5, '2015-03-09 23:07:48'),
(2, 331487038286, 1, '78.44', 'Stalwart 18 Volt Cordless Drill Set Power Tool Bits Driver Deluxe Combo ', 'B002TFHOVC', 'http://thumbs.ebaystatic.com/pict/3314870382866464_1.jpg', 'http://www.ebay.com/itm/Stalwart-18-Volt-Cordless-Drill-Set-Power-Tool-Bits-Driver-Deluxe-Combo-/331487038286', '57.98', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002TFHOVC', 6, '2015-03-09 23:07:58'),
(2, 331487949611, 1, '113.63', 'Cuisinart Coffee 12-Cup Programmable Coffeemaker Digital Stainless Steel', 'B005IR4W7W', 'http://thumbs.ebaystatic.com/pict/3314879496116464_1.jpg', 'http://www.ebay.com/itm/Cuisinart-Coffee-12-Cup-Programmable-Coffeemaker-Digital-Stainless-Steel-/331487949611', '83.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005IR4W7W', 32, '2015-02-26 11:33:31'),
(2, 231489497153, 1, '74.37', 'New 1500w Electric Portable Ceramic Oscillating Space Heater Room Remote Decor', 'B000N22JX6', 'http://thumbs.ebaystatic.com/pict/2314894971536464_1.jpg', 'http://www.ebay.com/itm/New-1500w-Electric-Portable-Ceramic-Oscillating-Space-Heater-Room-Remote-Decor-/231489497153', '54.97', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000N22JX6', 33, '2015-02-26 11:33:39'),
(2, 231489524142, 1, '108.22', 'Chicco Capri Lightweight Stroller Tangerine Travel Carrier 4 Wheel Folding New ', 'B000FFS9VM', 'http://thumbs.ebaystatic.com/pict/2314895241426464_1.jpg', 'http://www.ebay.com/itm/Chicco-Capri-Lightweight-Stroller-Tangerine-Travel-Carrier-4-Wheel-Folding-New-/231489524142', '79.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000FFS9VM', 34, '2015-02-26 11:33:46'),
(2, 331488839630, 1, '86.01', 'Merry Garden Foldable Adirondack Chair  Wood Patio Lawn Furniture', 'B0044FW4RE', 'http://thumbs.ebaystatic.com/pict/3314888396306464_1.jpg', 'http://www.ebay.com/itm/Merry-Garden-Foldable-Adirondack-Chair-Wood-Patio-Lawn-Furniture-/331488839630', '63.57', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B0044FW4RE', 7, '2015-03-09 23:08:08'),
(2, 231489563863, 1, '214.83', 'Adirondack Chair Wood Poly Furniture Natural Patio New Natural Feel Outdoor', 'B0055FSKQ6', 'http://thumbs.ebaystatic.com/pict/2314895638636464_1.jpg', 'http://www.ebay.com/itm/Adirondack-Chair-Wood-Poly-Furniture-Natural-Patio-New-Natural-Feel-Outdoor-/231489563863', '158.79', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B0055FSKQ6', 36, '2015-02-26 11:34:01'),
(2, 331488861036, 1, '216.46', 'Baby Trend Double Twin Carbon Green Stroller Nursery Deluxe New Toddler Kid Play', 'B008TKG7FA', 'http://thumbs.ebaystatic.com/pict/3314888610366464_2.jpg', 'http://www.ebay.com/itm/Baby-Trend-Double-Twin-Carbon-Green-Stroller-Nursery-Deluxe-New-Toddler-Kid-Play-/331488861036', '159.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B008TKG7FA', 37, '2015-02-26 11:34:08'),
(2, 331489910911, 1, '1352.93', 'All Clad 14 Piece Set Kitchen Pots Pans Cookware Sets Cooking  Stainless Steel', 'WFAAC1475', 'http://thumbs.ebaystatic.com/pict/3314899109116464_1.jpg', 'http://www.ebay.com/itm/All-Clad-14-Piece-Set-Kitchen-Pots-Pans-Cookware-Sets-Cooking-Stainless-Steel-/331489910911', '1149.99', '0.00', 30, 0, 0, 'http://www.wayfair.com/All-Clad-Stainless-Steel-14-Piece-Cookware-Set-I-401716-AAC1475.html', 38, '2015-02-26 11:34:14'),
(2, 231490569600, 1, '166.86', 'Jump Starter Portable Battery Booster Charger Pack 1500 Amps Car Boat Peak', 'B000JFHNQA', 'http://thumbs.ebaystatic.com/pict/2314905696006464_1.jpg', 'http://www.ebay.com/itm/Jump-Starter-Portable-Battery-Booster-Charger-Pack-1500-Amps-Car-Boat-Peak-/231490569600', '123.33', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000JFHNQA', 39, '2015-02-26 11:34:21'),
(2, 231459847160, 1, '166.86', 'Jump Starter Portable Battery Booster Charger Pack 1500 Amps Car Boat Peak', 'B000JFHNQA', 'http://thumbs.ebaystatic.com/pict/2314598471606464_1.jpg', 'http://www.ebay.com/itm/Jump-Starter-Portable-Battery-Booster-Charger-Pack-1500-Amps-Car-Boat-Peak-/231459847160', '123.33', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000JFHNQA', 10, '2015-02-26 10:57:32'),
(2, 331478497918, 1, '74.25', ' 20v Volt Power Drill Electric Screwdriver Cordless Tool Driver Ion Max Compact ', 'B005NNF0YU', 'http://thumbs.ebaystatic.com/pict/3314784979186464_1.jpg', 'http://www.ebay.com/itm/20v-Volt-Power-Drill-Electric-Screwdriver-Cordless-Tool-Driver-Ion-Max-Compact-/331478497918', '54.88', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005NNF0YU', 11, '2015-02-26 10:57:36'),
(2, 331454467636, 1, '79.80', 'BARSKA Telescope Tripod Astronomical Magnifying Scope Astronomy Kids Adult', 'B000BY2DJQ', 'http://thumbs.ebaystatic.com/pict/3314544676366464_1.jpg', 'http://www.ebay.com/itm/BARSKA-Telescope-Tripod-Astronomical-Magnifying-Scope-Astronomy-Kids-Adult-/331454467636', '58.98', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000BY2DJQ', 12, '2015-02-26 10:57:42'),
(2, 231456016151, 1, '1148.95', 'Hutch Mission Vintage Furniture Dining Room Wood China Cabinet Storage Espresso', 'HNHMS1268', 'http://thumbs.ebaystatic.com/pict/2314560161516464_1.jpg', 'http://www.ebay.com/itm/Hutch-Mission-Vintage-Furniture-Dining-Room-Wood-China-Cabinet-Storage-Espresso-/231456016151', '0', '15.00', 0, 0, 0, '', 13, '2015-02-26 10:57:43'),
(2, 331452419942, 1, '74.25', ' 20v Volt Power Drill Electric Screwdriver Cordless Tool Driver Ion Max Compact ', 'B005NNF0YU', 'http://thumbs.ebaystatic.com/pict/3314524199426464_1.jpg', 'http://www.ebay.com/itm/20v-Volt-Power-Drill-Electric-Screwdriver-Cordless-Tool-Driver-Ion-Max-Compact-/331452419942', '54.88', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005NNF0YU', 14, '2015-02-26 10:57:47'),
(2, 331452405941, 1, '235.95', 'All Clad Stainless 2 Qt Sauce Pot Pan Double Boiler Lid Cookware Cooking Kitchen', 'HNGSEB125', 'http://thumbs.ebaystatic.com/pict/3314524059416464_1.jpg', 'http://www.ebay.com/itm/All-Clad-Stainless-2-Qt-Sauce-Pot-Pan-Double-Boiler-Lid-Cookware-Cooking-Kitchen-/331452405941', '0', '15.00', 0, 0, 0, '', 15, '2015-02-26 10:57:47'),
(2, 331450686763, 1, '137.99', 'Tiffany Style Contemporary Table Lamp Light Bright Contemporary Glass Desk Table', 'OS14978469', 'http://thumbs.ebaystatic.com/pict/3314506867636464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Contemporary-Table-Lamp-Light-Bright-Contemporary-Glass-Desk-Table-/331450686763', '101.99', '15.00', 8, 0, 0, 'http://www.overstock.com/search/14978469', 16, '2015-02-26 10:57:50'),
(2, 331450686767, 1, '228.63', 'Tiffany Style Mission Table Floor Lamp Set Stain Glass Vintage Combo 60w Antique', 'OS15789669', 'http://thumbs.ebaystatic.com/pict/3314506867676464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Mission-Table-Floor-Lamp-Set-Stain-Glass-Vintage-Combo-60w-Antique-/331450686767', '168.99', '15.00', 17, 0, 0, 'http://www.overstock.com/search/15789669', 17, '2015-02-26 10:57:53'),
(2, 331450686771, 1, '117.95', 'Cherry Low Saddleback Bar Stool Wood Sturdy Dinning Furniture Kitchen Seat Chair', 'OS12960331', 'http://thumbs.ebaystatic.com/pict/3314506867716464_1.jpg', 'http://www.ebay.com/itm/Cherry-Low-Saddleback-Bar-Stool-Wood-Sturdy-Dinning-Furniture-Kitchen-Seat-Chair-/331450686771', '0', '15.00', 0, 0, 0, 'http://www.overstock.com/search/12960331', 18, '2015-02-26 10:57:56'),
(2, 231454408860, 1, '17.59', 'Nautica Classic Men''s 3.4-ounce Eau de Toilette Spray Cologne Fragrance Perfume', 'OS12332140', 'http://thumbs.ebaystatic.com/pict/2314544088606464_1.jpg', 'http://www.ebay.com/itm/Nautica-Classic-Mens-3-4-ounce-Eau-Toilette-Spray-Cologne-Fragrance-Perfume-/231454408860', '13', '15.00', 5, 0, 0, 'http://www.overstock.com/search/12332140', 20, '2015-02-26 10:58:02'),
(2, 331450686761, 1, '94.69', 'Aztec Lighting Bronze Tiffany Style Table Lamp Contemporary Furniture Desk Light', 'OS13883369', 'http://thumbs.ebaystatic.com/pict/3314506867616464_1.jpg', 'http://www.ebay.com/itm/Aztec-Lighting-Bronze-Tiffany-Style-Table-Lamp-Contemporary-Furniture-Desk-Light-/331450686761', '69.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/13883369', 21, '2015-02-26 10:58:05'),
(2, 231469879899, 1, '189.40', '24 piece Brown Contemporary Bed in a Bag Sheet Set Sham Queen Valance Pillow', 'OS13532715', 'http://thumbs.ebaystatic.com/pict/2314698798996464_1.jpg', 'http://www.ebay.com/itm/24-piece-Brown-Contemporary-Bed-Bag-Sheet-Set-Sham-Queen-Valance-Pillow-/231469879899', '139.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/13532715', 22, '2015-02-26 10:58:08'),
(2, 231469879900, 2, '81.16', 'Italy Gift Basket Olives Chocolate Candies Crackers Cheese Salami Coffee', 'B005EF0HU4', 'http://thumbs.ebaystatic.com/pict/2314698799006464_1.jpg', 'http://www.ebay.com/itm/Italy-Gift-Basket-Olives-Chocolate-Candies-Crackers-Cheese-Salami-Coffee-/231469879900', '59.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005EF0HU4', 23, '2015-02-26 10:58:13'),
(2, 331468242234, 2, '51.24', 'Gift Basket Box Caviar Cheese Godiva Chocolate Mocha Cookies Salmon Mustard', 'B0006B6XIQ', 'http://thumbs.ebaystatic.com/pict/3314682422346464_1.jpg', 'http://www.ebay.com/itm/Gift-Basket-Box-Caviar-Cheese-Godiva-Chocolate-Mocha-Cookies-Salmon-Mustard-/331468242234', '37.87', '15.00', 10, 0, 0, 'http://www.amazon.com/dp/B0006B6XIQ', 24, '2015-02-26 10:58:17'),
(2, 231441964959, 1, '109.39', 'Mr. Heater Portable 9,000 BTU Ice Fishing Hunting Camping Propane Garage Porch', 'B002G51BZU', 'http://thumbs.ebaystatic.com/pict/2314419649596464_1.jpg', 'http://www.ebay.com/itm/Mr-Heater-Portable-9-000-BTU-Ice-Fishing-Hunting-Camping-Propane-Garage-Porch-/231441964959', '80.85', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002G51BZU', 25, '2015-02-26 10:58:23'),
(2, 231441964960, 1, '93.27', 'Indoor Outdoor Mr. Heater Propane Garage Porch Fishing Camping Camping 9,000 BTU', 'B001CFRF7I', 'http://thumbs.ebaystatic.com/pict/2314419649606464_1.jpg', 'http://www.ebay.com/itm/Indoor-Outdoor-Mr-Heater-Propane-Garage-Porch-Fishing-Camping-Camping-9-000-BTU-/231441964960', '68.94', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B001CFRF7I', 26, '2015-02-26 10:58:27'),
(2, 231441002038, 1, '169.95', 'Furniture China Cabinet Plate Silverware Buffet Hutch Mission Glass Doors Dining', 'OS16070876', 'http://thumbs.ebaystatic.com/pict/2314410020386464_1.jpg', 'http://www.ebay.com/itm/Furniture-China-Cabinet-Plate-Silverware-Buffet-Hutch-Mission-Glass-Doors-Dining-/231441002038', '0', '15.00', 0, 0, 0, 'http://www.overstock.com/search/16070876', 27, '2015-02-26 10:58:30'),
(2, 331431372882, 2, '171.82', 'Celestron Telescope Eye Piece Filter Accessory Kit Set Astronomical Astronomy', 'B00006RH5I', 'http://thumbs.ebaystatic.com/pict/3314313728826464_1.jpg', 'http://www.ebay.com/itm/Celestron-Telescope-Eye-Piece-Filter-Accessory-Kit-Set-Astronomical-Astronomy-/331431372882', '127', '15.00', 3, 0, 0, 'http://www.amazon.com/dp/B00006RH5I', 28, '2015-02-26 10:58:33'),
(2, 231436977185, 1, '182.09', 'New 1000-Watt AC/DC Portable Gas Generator Power RV Quiet Electric Emergency', 'B00J261PGQ', 'http://thumbs.ebaystatic.com/pict/2314369771856464_1.jpg', 'http://www.ebay.com/itm/New-1000-Watt-AC-DC-Portable-Gas-Generator-Power-RV-Quiet-Electric-Emergency-/231436977185', '134.59', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00J261PGQ', 29, '2015-02-26 10:58:37'),
(2, 331452419944, 1, '209.62', 'Tiffany Style Victorian Lighted Base Table Lamp Stained Glass Vintage Bronze', 'OS12935667', 'http://thumbs.ebaystatic.com/pict/3314524199446464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Victorian-Lighted-Base-Table-Lamp-Stained-Glass-Vintage-Bronze-/331452419944', '154.94', '15.00', 20, 0, 0, 'http://www.overstock.com/search/12935667', 30, '2015-02-26 10:58:40'),
(2, 231490859741, 1, '88.95', 'Indoor Outdoor Mr. Heater Propane Garage Porch Fishing Camping Camping 9,000 BTU', 'B001CFRF7I', 'http://thumbs.ebaystatic.com/pict/2314908597416464_1.jpg', 'http://www.ebay.com/itm/Indoor-Outdoor-Mr-Heater-Propane-Garage-Porch-Fishing-Camping-Camping-9-000-BTU-/231490859741', '68.94', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B001CFRF7I', 0, '0000-00-00 00:00:00'),
(2, 331490179437, 1, '189.40', '24 piece Brown Contemporary Bed in a Bag Sheet Set Sham Queen Valance Pillow', 'OS13532715', 'http://thumbs.ebaystatic.com/pict/3314901794376464_1.jpg', 'http://www.ebay.com/itm/24-piece-Brown-Contemporary-Bed-Bag-Sheet-Set-Sham-Queen-Valance-Pillow-/331490179437', '139.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/13532715', 15, '2015-02-26 11:31:51'),
(2, 231490859563, 1, '81.16', 'Italy Gift Basket Olives Chocolate Candies Crackers Cheese Salami Coffee', 'B005EF0HU4', 'http://thumbs.ebaystatic.com/pict/2314908595636464_1.jpg', 'http://www.ebay.com/itm/Italy-Gift-Basket-Olives-Chocolate-Candies-Crackers-Cheese-Salami-Coffee-/231490859563', '59.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005EF0HU4', 16, '2015-02-26 11:31:57'),
(2, 331490179550, 1, '51.24', 'Gift Basket Box Caviar Cheese Godiva Chocolate Mocha Cookies Salmon Mustard', 'B0006B6XIQ', 'http://thumbs.ebaystatic.com/pict/3314901795506464_1.jpg', 'http://www.ebay.com/itm/Gift-Basket-Box-Caviar-Cheese-Godiva-Chocolate-Mocha-Cookies-Salmon-Mustard-/331490179550', '37.87', '15.00', 10, 0, 0, 'http://www.amazon.com/dp/B0006B6XIQ', 17, '2015-02-26 11:32:02'),
(2, 331490179840, 1, '223.22', 'Tiffany Style Victorian Lighted Base Table Lamp Stained Glass Vintage Bronze', 'OS12935667', 'http://thumbs.ebaystatic.com/pict/3314901798406464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Victorian-Lighted-Base-Table-Lamp-Stained-Glass-Vintage-Bronze-/331490179840', '164.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/12935667', 4, '2015-03-02 08:40:17'),
(2, 231490857408, 1, '143.29', 'Black & Decker 20-Volt Combo Drill Driver Impact Tool Cordless Power Bundle', 'B00FJWY2EO', 'http://thumbs.ebaystatic.com/pict/2314908574086464_1.jpg', 'http://www.ebay.com/itm/Black-Decker-20-Volt-Combo-Drill-Driver-Impact-Tool-Cordless-Power-Bundle-/231490857408', '105.91', '15.00', 27, 0, 0, 'http://www.amazon.com/dp/B00FJWY2EO', 0, '2015-03-28 06:13:17'),
(2, 231504249839, 1, '166.34', 'Celestron 21061 AstroMaster 70AZ Refractor Telescope ', 'B000MLHMAS', 'http://thumbs.ebaystatic.com/pict/2315042498396464_1.jpg', 'http://www.ebay.com/itm/Celestron-21061-AstroMaster-70AZ-Refractor-Telescope-/231504249839', '122.95', '15.00', 3, 0, 0, 'http://www.amazon.com/dp/B000MLHMAS', 1, '2015-04-07 05:41:00'),
(2, 231490857458, 1, '1352.88', 'All Clad 14 Piece Set Kitchen Pots Pans Cookware Sets Cooking  Stainless Steel', 'WFAAC1475', 'http://thumbs.ebaystatic.com/pict/2314908574586464_1.jpg', 'http://www.ebay.com/itm/All-Clad-14-Piece-Set-Kitchen-Pots-Pans-Cookware-Sets-Cooking-Stainless-Steel-/231490857458', '1149.95', '0.00', 30, 0, 0, 'http://www.wayfair.com/All-Clad-Stainless-Steel-14-Piece-Cookware-Set-I-401716-AAC1475.html', 1, '2015-03-28 06:13:25'),
(2, 231490857510, 1, '37.84', 'Humming Nectar Feeders Bird Squirrel Proof Unique Feeders Blown Glass Stained ', 'B00KLTA5HS', 'http://thumbs.ebaystatic.com/pict/2314908575106464_1.jpg', 'http://www.ebay.com/itm/Humming-Nectar-Feeders-Bird-Squirrel-Proof-Unique-Feeders-Blown-Glass-Stained-/231490857510', '27.97', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00KLTA5HS', 42, '2015-02-26 11:34:39'),
(2, 231490857562, 1, '231.34', 'Rocker Gaming Chair Wireless Xbox Play Station 3 Wii PC Seat Gamer Sound Speaker', 'B002S7H920', 'http://thumbs.ebaystatic.com/pict/2314908575626464_1.jpg', 'http://www.ebay.com/itm/Rocker-Gaming-Chair-Wireless-Xbox-Play-Station-3-Wii-PC-Seat-Gamer-Sound-Speaker-/231490857562', '170.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002S7H920', 2, '2015-03-28 06:13:31'),
(2, 331490178836, 1, '166.86', 'Jump Starter Portable Battery Booster Charger Pack 1500 Amps Car Boat Peak', 'B000JFHNQA', 'http://thumbs.ebaystatic.com/pict/3314901788366464_1.jpg', 'http://www.ebay.com/itm/Jump-Starter-Portable-Battery-Booster-Charger-Pack-1500-Amps-Car-Boat-Peak-/331490178836', '123.33', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000JFHNQA', 44, '2015-02-26 11:34:52'),
(2, 231490858842, 1, '133.94', 'DEWALT Tools 18-Volt Power Cordless Compact Drill Driver Screw Battery Kit ', 'B002RLR0EY', 'http://thumbs.ebaystatic.com/pict/2314908588426464_1.jpg', 'http://www.ebay.com/itm/DEWALT-Tools-18-Volt-Power-Cordless-Compact-Drill-Driver-Screw-Battery-Kit-/231490858842', '99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002RLR0EY', 45, '2015-02-26 11:34:59'),
(2, 331490179048, 1, '79.80', 'BARSKA Telescope Tripod Astronomical Magnifying Scope Astronomy Kids Adult', 'B000BY2DJQ', 'http://thumbs.ebaystatic.com/pict/3314901790486464_1.jpg', 'http://www.ebay.com/itm/BARSKA-Telescope-Tripod-Astronomical-Magnifying-Scope-Astronomy-Kids-Adult-/331490179048', '58.98', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000BY2DJQ', 46, '2015-02-26 11:35:05'),
(2, 231490859104, 1, '74.25', ' 20v Volt Power Drill Electric Screwdriver Cordless Tool Driver Ion Max Compact ', 'B005NNF0YU', 'http://thumbs.ebaystatic.com/pict/2314908591046464_1.jpg', 'http://www.ebay.com/itm/20v-Volt-Power-Drill-Electric-Screwdriver-Cordless-Tool-Driver-Ion-Max-Compact-/231490859104', '54.88', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005NNF0YU', 47, '2015-02-26 11:35:11'),
(2, 231490859148, 1, '133.93', 'Tiffany Style Contemporary Table Lamp Light Bright Contemporary Glass Desk Table', 'OS14978469', 'http://thumbs.ebaystatic.com/pict/2314908591486464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Contemporary-Table-Lamp-Light-Bright-Contemporary-Glass-Desk-Table-/231490859148', '98.99', '15.00', 3, 0, 0, 'http://www.overstock.com/search/14978469', 3, '2015-03-28 06:13:38'),
(2, 231490859197, 1, '228.63', 'Tiffany Style Mission Table Floor Lamp Set Stain Glass Vintage Combo 60w Antique', 'OS15789669', 'http://thumbs.ebaystatic.com/pict/2314908591976464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Mission-Table-Floor-Lamp-Set-Stain-Glass-Vintage-Combo-60w-Antique-/231490859197', '168.99', '15.00', 17, 0, 0, 'http://www.overstock.com/search/15789669', 4, '2015-03-28 06:13:45'),
(2, 231490859270, 1, '121.75', 'Tiffany Style Table Lamp Light Colorful Furniture Den Living Room Desk Deco', 'OS1135221', 'http://thumbs.ebaystatic.com/pict/2314908592706464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Table-Lamp-Light-Colorful-Furniture-Den-Living-Room-Desk-Deco-/231490859270', '89.99', '15.00', 12, 0, 0, 'http://www.overstock.com/search/1135221', 50, '2015-02-26 11:35:25'),
(2, 231490859373, 1, '17.59', 'Nautica Classic Men''s 3.4-ounce Eau de Toilette Spray Cologne Fragrance Perfume', 'OS12332140', 'http://thumbs.ebaystatic.com/pict/2314908593736464_1.jpg', 'http://www.ebay.com/itm/Nautica-Classic-Mens-3-4-ounce-Eau-Toilette-Spray-Cologne-Fragrance-Perfume-/231490859373', '13', '15.00', 5, 0, 0, 'http://www.overstock.com/search/12332140', 51, '2015-02-26 11:35:31'),
(2, 331490179376, 1, '94.69', 'Aztec Lighting Bronze Tiffany Style Table Lamp Contemporary Furniture Desk Light', 'OS13883369', 'http://thumbs.ebaystatic.com/pict/3314901793766464_1.jpg', 'http://www.ebay.com/itm/Aztec-Lighting-Bronze-Tiffany-Style-Table-Lamp-Contemporary-Furniture-Desk-Light-/331490179376', '69.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/13883369', 5, '2015-03-28 06:13:52'),
(2, 231490859654, 1, '109.39', 'Mr. Heater Portable 9,000 BTU Ice Fishing Hunting Camping Propane Garage Porch', 'B002G51BZU', 'http://thumbs.ebaystatic.com/pict/2314908596546464_1.jpg', 'http://www.ebay.com/itm/Mr-Heater-Portable-9-000-BTU-Ice-Fishing-Hunting-Camping-Propane-Garage-Porch-/231490859654', '80.85', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002G51BZU', 53, '2015-02-26 11:35:41'),
(2, 331490179725, 1, '171.82', 'Celestron Telescope Eye Piece Filter Accessory Kit Set Astronomical Astronomy', 'B00006RH5I', 'http://thumbs.ebaystatic.com/pict/3314901797256464_1.jpg', 'http://www.ebay.com/itm/Celestron-Telescope-Eye-Piece-Filter-Accessory-Kit-Set-Astronomical-Astronomy-/331490179725', '127', '15.00', 3, 0, 0, 'http://www.amazon.com/dp/B00006RH5I', 54, '2015-02-26 11:35:47'),
(2, 331490179787, 1, '182.09', 'New 1000-Watt AC/DC Portable Gas Generator Power RV Quiet Electric Emergency', 'B00J261PGQ', 'http://thumbs.ebaystatic.com/pict/3314901797876464_1.jpg', 'http://www.ebay.com/itm/New-1000-Watt-AC-DC-Portable-Gas-Generator-Power-RV-Quiet-Electric-Emergency-/331490179787', '134.59', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00J261PGQ', 55, '2015-02-26 11:35:53'),
(2, 231495864791, 1, '78.15', 'BARSKA Telescope Tripod Astronomical Magnifying Scope Astronomy Kids Adult', 'B000BY2DJQ', 'http://thumbs.ebaystatic.com/pict/2314958647916464_1.jpg', 'http://www.ebay.com/itm/BARSKA-Telescope-Tripod-Astronomical-Magnifying-Scope-Astronomy-Kids-Adult-/231495864791', '57.76', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B000BY2DJQ', 15, '2015-03-09 23:09:13'),
(2, 331495849268, 1, '82.95', 'Indoor Outdoor Mr. Heater Propane Garage Porch Fishing Camping Camping 9,000 BTU', 'B001CFRF7I', 'http://thumbs.ebaystatic.com/pict/3314958492686464_1.jpg', 'http://www.ebay.com/itm/Indoor-Outdoor-Mr-Heater-Propane-Garage-Porch-Fishing-Camping-Camping-9-000-BTU-/331495849268', '61.31', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B001CFRF7I', 6, '2015-03-28 06:13:58'),
(2, 231495865700, 1, '97.64', 'Mr. Heater Portable 9,000 BTU Ice Fishing Hunting Camping Propane Garage Porch', 'B002G51BZU', 'http://thumbs.ebaystatic.com/pict/2314958657006464_1.jpg', 'http://www.ebay.com/itm/Mr-Heater-Portable-9-000-BTU-Ice-Fishing-Hunting-Camping-Propane-Garage-Porch-/231495865700', '87.75', '15.00', 27, 0, 0, 'http://www.amazon.com/dp/B002G51BZU', 17, '2015-03-09 23:09:32'),
(2, 331495851784, 1, '74.25', ' 20v Volt Power Drill Electric Screwdriver Cordless Tool Driver Ion Max Compact ', 'B005NNF0YU', 'http://thumbs.ebaystatic.com/pict/3314958517846464_1.jpg', 'http://www.ebay.com/itm/20v-Volt-Power-Drill-Electric-Screwdriver-Cordless-Tool-Driver-Ion-Max-Compact-/331495851784', '54.88', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005NNF0YU', 7, '2015-03-28 06:14:06'),
(2, 231495869754, 1, '148.81', 'DEWALT Tools 18-Volt Power Cordless Compact Drill Driver Screw Battery Kit ', 'B002RLR0EY', 'http://thumbs.ebaystatic.com/pict/2314958697546464_1.jpg', 'http://www.ebay.com/itm/DEWALT-Tools-18-Volt-Power-Cordless-Compact-Drill-Driver-Screw-Battery-Kit-/231495869754', '109.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B002RLR0EY', 8, '2015-03-28 06:14:11'),
(2, 231495870132, 1, '121.72', 'Cuisinart Coffee 12-Cup Programmable Coffeemaker Digital Stainless Steel', 'B005IR4W7W', 'http://thumbs.ebaystatic.com/pict/2314958701326464_1.jpg', 'http://www.ebay.com/itm/Cuisinart-Coffee-12-Cup-Programmable-Coffeemaker-Digital-Stainless-Steel-/231495870132', '89.97', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B005IR4W7W', 9, '2015-03-28 06:14:18'),
(2, 331499557588, 1, '135.28', 'Dirt Devil SD40050 Dash Bagless Canister Vaccum Floors Carpet Rugs Cleaning ', 'B00N4MHJH8', 'http://thumbs.ebaystatic.com/pict/3314995575886464_1.jpg', 'http://www.ebay.com/itm/Dirt-Devil-SD40050-Dash-Bagless-Canister-Vaccum-Floors-Carpet-Rugs-Cleaning-/331499557588', '99.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00N4MHJH8', 10, '2015-03-28 06:14:23'),
(2, 231495662416, 0, '37.87', 'Tommy Hilfiger Girl Cologne Spray Fragrance Perfume Parfum Scent Lady 3.4oz', 'OS12332921', 'http://i.ebayimg.com/00/s/NjUwWDY1MA==/z/-iAAAOSw6aVUnyGs/$_1.JPG?set_id=880000500F', 'http://www.ebay.com/itm/Tommy-Hilfiger-Girl-Cologne-Spray-Fragrance-Perfume-Parfum-Scent-Lady-3-4oz-/231495662416', '27.99', '15.00', 5, 0, 0, 'http://www.overstock.com/search/12332921', 0, '0000-00-00 00:00:00'),
(2, 231504061194, 78, '78.15', 'BARSKA Telescope Tripod Astronomical Magnifying Scope Astronomy Kids Adult', 'B000BY2DJQ', 'http://i.ebayimg.com/00/s/MTIwMFgxMjAw/z/45sAAOSwdpxUXB~p/$_1.JPG?set_id=880000500F', 'http://www.ebay.com/itm/BARSKA-Telescope-Tripod-Astronomical-Magnifying-Scope-Astronomy-Kids-Adult-/231504061194', '57.76', '15.00', 30, 1, 0, 'http://www.amazon.com/dp/B000BY2DJQ', 0, '2015-03-13 07:46:15'),
(2, 331503314273, 183, '182.58', 'Instant Pot IP-DUO60 7-in-1 Programmable Pressure Cooker, 6Qt/1000W, Stainless ', 'B00FLYWNYQ', 'http://i.ebayimg.com/00/s/MjkwWDUwMA==/z/q48AAOSwpDdVAZ5H/$_1.JPG?set_id=8800005007', 'http://www.ebay.com/itm/Instant-Pot-IP-DUO60-7-in-1-Programmable-Pressure-Cooker-6Qt-1000W-Stainless-/331503314273', '134.95', '15.00', 30, 1, 0, 'http://www.amazon.com/dp/B00FLYWNYQ', 0, '2015-04-07 05:40:55'),
(2, 331503955456, 1, '257.05', 'Furniture of America 5-shelf Shoe Cabinet with Two Upper Storage Bins', 'OS11923225', 'http://thumbs.ebaystatic.com/pict/3315039554566464_1.jpg', 'http://www.ebay.com/itm/Furniture-America-5-shelf-Shoe-Cabinet-Two-Upper-Storage-Bins-/331503955456', '189.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/11923225', 2, '2015-04-07 05:41:08'),
(2, 231504335996, 1, '96.05', 'Keurig DeskPro Coffee Maker', 'OS14027050', 'http://thumbs.ebaystatic.com/pict/2315043359966464_1.jpg', 'http://www.ebay.com/itm/Keurig-DeskPro-Coffee-Maker-/231504335996', '70.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/14027050', 3, '2015-04-07 05:41:15'),
(2, 331504421673, 1, '164.37', 'Keter Rockwood Deck Box New Outdoor Storage 150 Gallon ', 'B00BK3TLQ0', 'http://thumbs.ebaystatic.com/pict/3315044216736464_2.jpg', 'http://www.ebay.com/itm/Keter-Rockwood-Deck-Box-New-Outdoor-Storage-150-Gallon-/331504421673', '121.49', '15.00', 4, 0, 0, 'http://www.amazon.com/dp/B00BK3TLQ0', 4, '2015-04-07 05:41:19'),
(2, 231504816014, 1, '297.63', 'KidKraft Sectional Outdoor Patio Furniture Kid Pool Umbrella Summer Deck New BBQ', 'B00BTT8S5A', 'http://thumbs.ebaystatic.com/pict/2315048160146464_1.jpg', 'http://www.ebay.com/itm/KidKraft-Sectional-Outdoor-Patio-Furniture-Kid-Pool-Umbrella-Summer-Deck-New-BBQ-/231504816014', '229.99', '15.00', 11, 0, 0, 'http://www.amazon.com/dp/B00BTT8S5A', 5, '2015-04-07 05:41:22'),
(2, 331505282555, 1, '65.74', 'Keter Ottomans Table Outdoor Bench Wicker Garden Patio Brown Lawn Patio BBQ', 'B00F8FLEPC', 'http://thumbs.ebaystatic.com/pict/3315052825556464_1.jpg', 'http://www.ebay.com/itm/Keter-Ottomans-Table-Outdoor-Bench-Wicker-Garden-Patio-Brown-Lawn-Patio-BBQ-/331505282555', '49.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00F8FLEPC', 6, '2015-04-07 05:41:26'),
(2, 331505967571, 1, '211.05', 'Caravan Valencia Resin Wicker Steel Frame Rocking Chair Patio Furniture NEW', 'OS13036305', 'http://thumbs.ebaystatic.com/pict/3315059675716464_2.jpg', 'http://www.ebay.com/itm/Caravan-Valencia-Resin-Wicker-Steel-Frame-Rocking-Chair-Patio-Furniture-NEW-/331505967571', '155.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/13036305', 7, '2015-04-07 05:41:33'),
(2, 331505971850, 1, '960.57', 'Wicker Furniture Outdoor Sofa Cushion Pillow Patio Deck Weather Resistant Couch', 'OS14796605', 'http://thumbs.ebaystatic.com/pict/3315059718506464_2.jpg', 'http://www.ebay.com/itm/Wicker-Furniture-Outdoor-Sofa-Cushion-Pillow-Patio-Deck-Weather-Resistant-Couch-/331505971850', '709.99', '15.00', 4, 0, 0, 'http://www.overstock.com/search/14796605', 8, '2015-04-07 05:41:40'),
(2, 331505977864, 1, '275.99', 'International Caravan Valencia Resin Wicker Steel Furniture Glider Chair Patio', 'OS13036306', 'http://thumbs.ebaystatic.com/pict/3315059778646464_1.jpg', 'http://www.ebay.com/itm/International-Caravan-Valencia-Resin-Wicker-Steel-Furniture-Glider-Chair-Patio-/331505977864', '203.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/13036306', 9, '2015-04-07 05:41:47'),
(2, 231506363793, 1, '284.08', 'Miracle Gro AeroGarden Ultra LED Indoor Garden with Gourmet Herb Seed Kit ', 'B00O9GRTC8', 'http://thumbs.ebaystatic.com/pict/2315063637936464_1.jpg', 'http://www.ebay.com/itm/Miracle-Gro-AeroGarden-Ultra-LED-Indoor-Garden-Gourmet-Herb-Seed-Kit-/231506363793', '199.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B00O9GRTC8', 10, '2015-04-07 05:41:51'),
(2, 331506359150, 1, '64.93', 'Compact Indoor Medicinal Herb Garden Starter Kit', 'OS12037469', 'http://thumbs.ebaystatic.com/pict/3315063591506464_2.jpg', 'http://www.ebay.com/itm/Compact-Indoor-Medicinal-Herb-Garden-Starter-Kit-/331506359150', '47.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/12037469', 11, '2015-04-07 05:41:58'),
(2, 331506707677, 1, '269.24', 'Gronomics Rustic Elevated Garden Bed, Unfinished Plant Herb Flower Patio', 'B006MRE8LM', 'http://thumbs.ebaystatic.com/pict/3315067076776464_1.jpg', 'http://www.ebay.com/itm/Gronomics-Rustic-Elevated-Garden-Bed-Unfinished-Plant-Herb-Flower-Patio-/331506707677', '233.84', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B006MRE8LM', 12, '2015-04-07 05:42:03'),
(2, 231507305917, 1, '81.11', 'Gardener Gardening Tools Folding Seat Tote Gardener Flowers Plants ', 'OS13440845', 'http://thumbs.ebaystatic.com/pict/2315073059176464_1.jpg', 'http://www.ebay.com/itm/Gardener-Gardening-Tools-Folding-Seat-Tote-Gardener-Flowers-Plants-/231507305917', '59.95', '15.00', 20, 0, 0, 'http://www.overstock.com/search/13440845', 13, '2015-04-07 05:42:10'),
(2, 231507335662, 1, '228.63', 'Patio Furniture Home Outdoor Wicker Brown Stackable Chair Patio Deck (Set of 2)', 'OS13468674', 'http://thumbs.ebaystatic.com/pict/2315073356626464_1.jpg', 'http://www.ebay.com/itm/Patio-Furniture-Home-Outdoor-Wicker-Brown-Stackable-Chair-Patio-Deck-Set-2-/231507335662', '168.99', '15.00', 11, 0, 0, 'http://www.overstock.com/search/13468674', 25, '2015-04-07 05:42:15'),
(2, 231508192745, 1, '68.49', 'BARSKA Telescope Tripod Astronomical Magnifying Scope Astronomy Kids Adult', 'B000BY2DJQ', 'http://thumbs.ebaystatic.com/pict/2315081927456464_1.jpg', 'http://www.ebay.com/itm/BARSKA-Telescope-Tripod-Astronomical-Magnifying-Scope-Astronomy-Kids-Adult-/231508192745', '51.98', '15.00', 36, 0, 0, 'http://www.amazon.com/dp/B000BY2DJQ', 26, '2015-03-28 06:16:12'),
(2, 331510374181, 1, '37.87', 'Tommy Hilfiger Girl Cologne Spray Fragrance Perfume Parfum Scent Lady 3.4oz', 'OS12332921', 'http://thumbs.ebaystatic.com/pict/3315103741816464_1.jpg', 'http://www.ebay.com/itm/Tommy-Hilfiger-Girl-Cologne-Spray-Fragrance-Perfume-Parfum-Scent-Lady-3-4oz-/331510374181', '27.99', '15.00', 5, 0, 0, 'http://www.overstock.com/search/12332921', 27, '2015-03-28 06:16:19'),
(2, 331511698264, 1, '81.16', 'Stainless Steel 24 piece BBQ Set Case Grilling Smoking Deck Fathersday Tools', 'OS1021776', 'http://thumbs.ebaystatic.com/pict/3315116982646464_2.jpg', 'http://www.ebay.com/itm/Stainless-Steel-24-piece-BBQ-Set-Case-Grilling-Smoking-Deck-Fathersday-Tools-/331511698264', '59.99', '15.00', 6, 0, 0, 'http://www.overstock.com/search/1021776', 28, '2015-03-28 06:16:26'),
(2, 231512300829, 1, '89.28', 'Heavy Duty Grill Travel Camping Camp Fishing Hunting Outdoor Cooking Cookware', 'OS13534261', 'http://thumbs.ebaystatic.com/pict/2315123008296464_2.jpg', 'http://www.ebay.com/itm/Heavy-Duty-Grill-Travel-Camping-Camp-Fishing-Hunting-Outdoor-Cooking-Cookware-/231512300829', '65.99', '15.00', 4, 0, 0, 'http://www.overstock.com/search/13534261', 29, '2015-03-28 06:16:33'),
(2, 331511795658, 1, '49.52', 'Enamel Camping 24 Piece Blue Tableware Set Camp Hiking Cookware Dish Plate', 'OS16553478', 'http://thumbs.ebaystatic.com/pict/3315117956586464_2.jpg', 'http://www.ebay.com/itm/Enamel-Camping-24-Piece-Blue-Tableware-Set-Camp-Hiking-Cookware-Dish-Plate-/331511795658', '36.6', '15.00', 20, 0, 0, 'http://www.overstock.com/search/16553478', 30, '2015-03-28 06:16:39'),
(2, 331512700825, 1, '41.25', 'Calvin Klein Eternity Women''s 3.4 ounce Eau de Parfum Spray Cologne Fragrance', 'OS11898960', 'http://thumbs.ebaystatic.com/pict/3315127008256464_1.jpg', 'http://www.ebay.com/itm/Calvin-Klein-Eternity-Womens-3-4-ounce-Eau-Parfum-Spray-Cologne-Fragrance-/331512700825', '30.49', '15.00', 5, 0, 0, 'http://www.overstock.com/search/11898960', 31, '2015-03-28 06:16:47'),
(2, 331512705901, 1, '85.15', 'Coleman 100 quart Blue Insulated Camping Food Storage Cooler Pop Beer Wine ', 'OS11883819', 'http://thumbs.ebaystatic.com/pict/3315127059016464_1.jpg', 'http://www.ebay.com/itm/Coleman-100-quart-Blue-Insulated-Camping-Food-Storage-Cooler-Pop-Beer-Wine-/331512705901', '62.94', '15.00', 20, 0, 0, 'http://www.overstock.com/search/11883819', 32, '2015-03-28 06:16:54'),
(2, 231513347974, 1, '105.52', 'Keter Rattan Cool Bar Outside Patio Furniture Pool Party Beverage BBQ', 'B007O1CAZQ', 'http://thumbs.ebaystatic.com/pict/2315133479746464_1.jpg', 'http://www.ebay.com/itm/Keter-Rattan-Cool-Bar-Outside-Patio-Furniture-Pool-Party-Beverage-BBQ-/231513347974', '76.99', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B007O1CAZQ', 33, '2015-03-28 06:17:00'),
(2, 331514647839, 1, '223.72', 'Sundsvall Console Table - Black', 'HNWI038', 'http://thumbs.ebaystatic.com/pict/3315146478396464_1.jpg', 'http://www.ebay.com/itm/Sundsvall-Console-Table-Black-/331514647839', '0', '15.00', 0, 0, 0, '', 34, '2015-03-28 06:34:45'),
(2, 331514648360, 1, '175.87', 'Belham Living Hampton Chair Side Table - Black', 'HNDONU035', 'http://thumbs.ebaystatic.com/pict/3315146483606464_1.jpg', 'http://www.ebay.com/itm/Belham-Living-Hampton-Chair-Side-Table-Black-/331514648360', '0', '15.00', 0, 0, 0, '', 35, '2015-03-28 06:34:45');
INSERT INTO `user_products` (`UserID`, `ItemID`, `Qty`, `Price`, `Title`, `SKU`, `Image`, `ItemUrl`, `VendorPrice`, `ProfitRatio`, `VendorQty`, `max_quantity`, `product_active`, `VendorUrl`, `sort`, `lastUpdate`) VALUES
(2, 331515283305, 1, '86.01', 'Garden Foldable Adirondack Chair Fir Wood Patio Lawn Furniture Deck Outdoor', 'B0044FW4RE', 'http://thumbs.ebaystatic.com/pict/3315152833056464_1.jpg', 'http://www.ebay.com/itm/Garden-Foldable-Adirondack-Chair-Fir-Wood-Patio-Lawn-Furniture-Deck-Outdoor-/331515283305', '63.57', '15.00', 30, 0, 0, 'http://www.amazon.com/dp/B0044FW4RE', 36, '2015-03-28 06:17:10'),
(2, 231517135990, 1, '137.99', 'Tiffany Style Table Lamp Vintage Antique Bronze Living Room Den Family Office', 'OS1582007', 'http://thumbs.ebaystatic.com/pict/2315171359906464_1.jpg', 'http://www.ebay.com/itm/Tiffany-Style-Table-Lamp-Vintage-Antique-Bronze-Living-Room-Den-Family-Office-/231517135990', '101.99', '15.00', 20, 0, 0, 'http://www.overstock.com/search/1582007', 37, '2015-03-28 06:17:17'),
(14, 110157631902, 224, '0.00', 'Sundsvall Console Table - Black', 'HNWI038', 'http://i.ebayimg.com/00/s/ODAwWDgwMA==/z/DTsAAOSwBahVFltX/$_1.JPG?set_id=8800005007', 'http://cgi.sandbox.ebay.com/Sundsvall-Console-Table-Black-/110157631902', '0', '15.00', 0, 1, 0, '', 0, '2015-03-28 07:42:59'),
(14, 110157228862, 0, '108.22', 'Wusthof 8 Piece Stainless Steak Knife Set', 'HN-WUST059', 'http://i.ebayimg.com/00/s/ODAwWDgwMA==/z/qlYAAOSwBLlVA9zm/$_1.JPG?set_id=8800005007', 'http://cgi.sandbox.ebay.com/Wusthof-8-Piece-Stainless-Steak-Knife-Set-/110157228862', '', '15.00', 0, 0, 0, '', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `user_products_cron`
--

DROP TABLE IF EXISTS `user_products_cron`;
CREATE TABLE IF NOT EXISTS `user_products_cron` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
