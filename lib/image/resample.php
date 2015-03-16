<?
namespace image;

function resample($img)
{
//    imagecopy($dst, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));

//	imagecopyresampled($dst, $img, imagesx($img)*0.05, imagesy($img)*0.05, 0, 0, imagesx($img)*0.9, imagesy($img)*0.9, imagesx($img), imagesy($img));
//	imagecopyresampled($dst, $img, imagesx($img)*0.05, imagesy($img)*0.05, 0, 0, imagesx($img)*0.9, imagesy($img)*0.9, imagesx($img), imagesy($img));
	$width = imagesx($img);
	$height = imagesy($img);

	if(imagesx($img)>1200 or imagesy($img)>1200)
	{
		if(imagesx($img) > imagesy($img))
		{
			$width = 1200;
			$height = 1200 * imagesy($img) / imagesx($img);
		}
		else
		{
			$height = 1200;
			$width = 1200 * imagesx($img) / imagesy($img);
		}
	}

	if(imagesx($img)<500 or imagesy($img)<500)
	{
		if(imagesx($img) > imagesy($img))
		{
			$width = 500;
			$height = 500 * imagesy($img) / imagesx($img);
		}
		else
		{
			$height = 500;
			$width = 500 * imagesx($img) / imagesy($img);
		}
	}

    $dst = imagecreatetruecolor($width, $height);

	$white = imagecolorallocate($dst, 255, 255, 255);
	imagefill($dst, 0, 0, $white);

	imagecopyresampled($dst, $img, imagesx($img)*0.05, imagesy($img)*0.05, 0, 0, $width*0.9, $height*0.9, imagesx($img), imagesy($img));
//	imagecopyresampled($dst, $img, 0, 0, 0, 0,$width, $height, imagesx($img), imagesy($img));
//	imagecopyresampled(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)

	return $dst;
}

