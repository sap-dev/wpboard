<?php
	/**
	*
	* @package com.Itschi.base.functions.upload
	* @since 2007/05/25
	*
	*/

	function resize($path, $new_path, $new_width, $new_height, $cut) {
		$size = getimagesize($path);

		$height_skaliert = (int)$size[1]*$new_width/$size[0];

		if (($cut) ? ($new_height < $height_skaliert) : ($new_height > $height_skaliert)) {
			$height_skaliert = $height_skaliert;
			$width_skaliert = $new_width;
		} else {
			$width_skaliert = (int)$size[0]*$new_height/$size[1];
			$height_skaliert = $new_height;
		}

		switch ($size[2]) {
			case 1:	// GIF
				$image_func = 'imagecreatefromGIF';
				$image_out = 'imageGIF';
				$q = 100;
			break;

			case 2:	// JPG
				$image_func = 'imagecreatefromJPEG';
				$image_out = 'imageJPEG';
				$q = 100;
			break;

			case 3:	// PNG
				$image_func = 'imagecreatefromPNG';
				$image_out = 'imagePNG';
				$q = 9;
			break;

			default:
				return false;
		}

		$old_image = $image_func($path);

		$new_image_skaliert = imagecreatetruecolor($width_skaliert,$height_skaliert);
		$bg = imagecolorallocatealpha($new_image_skaliert, 255, 255, 255, 127);
		ImageFill($new_image_skaliert, 0, 0, $bg);
		imagecopyresampled($new_image_skaliert, $old_image, 0,0,0,0, $width_skaliert, $height_skaliert, $size[0], $size[1]);

		if ($cut) {
			$new_image_cut = imagecreatetruecolor($new_width, $new_height);
			$bg = imagecolorallocatealpha($new_image_cut, 255, 255, 255, 127);
			imagefill($new_image_cut, 0, 0, $bg);
			imagecopy($new_image_cut, $new_image_skaliert, 0,0,0,0, $width_skaliert, $height_skaliert);
		}

		$image_out(($cut) ? $new_image_cut : $new_image_skaliert, $new_path, $q);

		return true;
	}
?>