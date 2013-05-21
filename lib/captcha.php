<?php
	/**
	*
	* @package com.Itschi.base.captcha
	* @since 2007/05/25
	*
	*/

	session_start();
	srand ((double)microtime() * 1000000);

	$Z = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$path = '../images/captcha/';
	$Y = strlen($Z) - 1;
	$B = array($Z{rand(0,$Y)}, $Z{rand(0,$Y)}, $Z{rand(0,$Y)}, $Z{rand(0,$Y)});

	$_SESSION['sicherheitscode'] = md5($B[0].$B[1].$B[2].$B[3]);

	$I = imagecreatefromgif($path . 'captcha.gif');
	$X = array(
		ImageColorAllocate($I,96,135,91),
		ImageColorAllocate($I,84,111,124),
		ImageColorAllocate($I,137,88,85),
		ImageColorAllocate($I,107,100,117),
		ImageColorAllocate($I,165,148,132),
		ImageColorAllocate($I,127,97,80),
		ImageColorAllocate($I,134,157,168),
		ImageColorAllocate($I,232,218,211)
	);

	ImageTTFText($I, rand(40,48), rand(-20,20), rand(7,11), rand(30,60), $X[7], $path . 'font' . rand(1,3) . '.ttf', $Z{rand(0,$Y)});
	ImageTTFText($I, rand(33,38), rand(-20,20), rand(48,56), rand(30,60), $X[7], $path . 'font' . rand(1,3) . '.ttf', $Z{rand(0,$Y)});
	ImageTTFText($I, rand(53,58), rand(-20,20), rand(94,99), rand(30,60), $X[7], $path . 'font' . rand(1,3) . '.ttf', $Z{rand(0,$Y)});
	ImageTTFText($I, rand(23,28), rand(-20,20), rand(133,140), rand(30,60), $X[7], $path . 'font' . rand(1,3) . '.ttf', $Z{rand(0,$Y)});
	ImageTTFText($I, rand(23,28), rand(-20,20), rand(7,11), rand(30,60), $X[rand(0,6)], $path . 'font' . rand(1,3) . '.ttf', $B[0]);
	ImageTTFText($I, rand(23,28), rand(-20,20), rand(48,56), rand(30,60), $X[rand(0,6)], $path . 'font' . rand(1,3) . '.ttf', $B[1]);
	ImageTTFText($I, rand(23,28), rand(-20,20), rand(94,99), rand(30,60), $X[rand(0,6)], $path . 'font' . rand(1,3) . '.ttf', $B[2]);
	ImageTTFText($I, rand(23,28), rand(-20,20), rand(133,140), rand(30,60), $X[rand(0,6)], $path . 'font' . rand(1,3) . '.ttf', $B[3]);

	imageline($I, 0, 0, 210, 0, $X[7]);
	imageline($I, 0, 69, 210,69, $X[7]);
	imageline($I, 0, 0, 0, 69, $X[7]);
	imageline($I, 209, 0, 209, 69, $X[7]);
	imageline($I, rand(10,30), rand(10,30), rand(150,170), rand(50,65), $X[7]);
	imageline($I, rand(10,30), rand(50,65), rand(150,170), rand(10,30), $X[7]);

	header('Content-type: image/gif');
	ImageDestroy(ImageGIF($I));
?>