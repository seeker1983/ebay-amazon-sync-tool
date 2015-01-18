<?

function calculate_rec_price($price, $profit = 0.15)
{
	return number_format($price * (1 + $profit) / 0.85, 2);
}

function get_vendor_from_sku($sku)
{
	foreach(array(
		array( 'pattern'=> '%^WF%', 'data' =>'<font color=green> Wayfair </font>'),
		array( 'pattern'=> '%^OS%', 'data' =>'<font color=blue> Overstock </font>'),
		array( 'pattern'=> '%^HN%', 'data' =>'<font color=pink> Hayneedle </font>'),
		array( 'pattern'=> '%^B%', 'data' =>'<font color=cyan> Amazon </font>'),
		array( 'pattern'=> '%.*%', 'data' =>'<font color=red> Unknown SKU </font>'),
		) as $rep)
			if(preg_match($rep['pattern'], $sku))
				return $rep['data'];
}

function get_url_from_sku($sku)
{
	foreach(array(
		array( 'pattern'=> '%^WF(.*)%', 'data' =>'http://www.wayfair.com/keyword.php?keyword=\1'),
		array( 'pattern'=> '%^OS(.*)%', 'data' =>'http://www.overstock.com/search/\1'),
		array( 'pattern'=> '%^HN(.*)%', 'data' =>'http://search.hayneedle.com/search/index.cfm?Ntt=\1'),
		array( 'pattern'=> '%(^B.*)%', 'data' =>'http://www.amazon.com/dp/\1'),
		array( 'pattern'=> '%.*%', 'data' =>'-'),
		) as $rep)
			if(preg_match($rep['pattern'], $sku))
				return preg_replace($rep['pattern'], $rep['data'], $sku);
}

