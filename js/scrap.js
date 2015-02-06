if (!($ = window.jQuery)) 
{
    script = document.createElement( 'script' );
   script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js'; 
    script.onload=releasetheKraken;
    document.body.appendChild(script);
} 
else {
    Process();
}
 
function Process() {	
	items = 
	{
		shop_name: $('a.orderInfo-shop:first').text(),
		shop_url: $('a.orderInfo-shop:first').attr('href'),
		total_price: parseFloat($('span.price:first').text()),
	}
	data=encodeURIComponent(JSON.stringify(items))

	console.log(data)
}