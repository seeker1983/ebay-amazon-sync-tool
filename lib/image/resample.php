<?
namespace image;

function resample($img)
{
    $dst = imagecreatetruecolor(imagesx($img), imagesy($img));

	$white = imagecolorallocate($dst, 255, 255, 255);
	imagefill($dst, 0, 0, $white);

//    imagecopy($dst, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));

	imagecopyresampled($dst, $img, imagesx($img)*0.05, imagesy($img)*0.05, 0, 0, imagesx($img)*0.9, imagesy($img)*0.9, imagesx($img), imagesy($img));
//	imagecopyresampled(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)

	return $dst;
}

