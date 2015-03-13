<?
require(__DIR__ . '/resample.php');

class Watermark
{
	public static function add_watermark($img)
	{
		if(preg_match('%^http://.*.jpg%i', trim($img)))
			$img_string = get_http_page($img);

		if(preg_match('%^data:image/jpeg;base64,%', trim($img)))
			$img_string = base64_decode(preg_replace('%^data:image/jpeg;base64,%', '', trim($img)));

		if($img_string)
		{
			$img_res = imagecreatefromstring($img_string);
			$img_res = Image\resample($img_res);
			imagefilter($img_res, IMG_FILTER_BRIGHTNESS, 2);

			$watermark = imagecreatefrompng('watermark/mnmerc2014.png');

			imagealphablending($img_res, TRUE); 

			//imagecopy($img_res, $watermark, imagesx($img_res)*0.95 - imagesx($watermark) - 5, imagesy($img_res)*0.95 - imagesy($watermark) - 5, 0, 0, imagesx($watermark), imagesy($watermark));

			ob_start(); 
			    imagejpeg($img_res); 
			    $contents = ob_get_contents(); 
			ob_end_clean(); 

			unset($img_res);

			gc_collect_cycles();

			return "data:image/jpeg;base64," . base64_encode($contents);				
		}
		else
			return $img;
	}

	
}
