<?php
header('Content-Type: text/plain;charset=UTF-8');

/*
	This file gets gemoved in the final release.
*/

define('ROOT', dirname(__FILE__));

function scandir_r($dir, $callback) {

	if (!is_callable($callback)) return false;

	$dirHandle = opendir($dir);

	if ($dirHandle == false) return false;

	while (($file = readdir($dirHandle)) != false) {

		if (substr($file, 0, 1) == '.') continue;

		$ext = explode('.', $file);
		$ext = strtolower(end($ext));

		$path = $dir.'/'.$file;

		if (is_file($path)) {
			$callback($file, $path, $ext);
		} else {
			scandir_r($path, $callback);
		}

	}

}

scandir_r(ROOT, function($file, $path, $ext) {
	if ($ext == 'php' || $ext == 'html') {
		if ($file == 'fix_utf.php') {
			echo 'skipped fix_utf.php'.PHP_EOL.PHP_EOL;

			return;
		}

		echo 'got file '.$file.' -> '.$path.PHP_EOL;

		$content = file_get_contents($path);

		echo mb_strlen($content).' bytes read'.PHP_EOL.PHP_EOL;

		$content = str_replace(
		array(
			'&uuml;',
			'&ouml;',
			'&auml;',
			'&Uuml;',
			'&Ouml;',
			'&Auml;'
		),
		array(
			'ü',
			'ö',
			'ä',
			'Ü',
			'Ö',
			'Ä'
		), $content);

		if (file_put_contents($path, $content, LOCK_EX) != false) {
			echo mb_strlen($content).' bytes wrote';
		} else {
			echo 'ERROR WRITING FILE';
		}

		echo PHP_EOL.PHP_EOL;
	}
});
?>