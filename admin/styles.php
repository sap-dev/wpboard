<?php
	/**
	*
	* @package com.Itschi.ACP.styles
	* @since 2007/05/25
	*
	*/

	require '../base.php';
	require_once '../lib/styles.php';

	use \Itschi\lib as lib;

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$styles = new lib\styles();
	$availableStyles = $styles->styles;

	if (!empty($_GET['activateStyle'])) {
		$styles->activate(htmlspecialchars($_GET['activateStyle']));
		header("Location: ./styles.php");
	}

	if (!empty($_GET['removeStyle'])) {
		$styles->remove(htmlspecialchars($_GET['removeStyle']));
		header("Location: ./styles.php");
	}

	foreach ($availableStyles as $style) {
		$title = $style['title'];
		$author = $style['author'];
		$URL = $style['URL'];
		$version = $style['version'];
		$minVersion = $style['minVersion'];
		$maxVersion = $style['maxVersion'];
		$dir = $style['dir'];
		$dirName = $style['dirName'];
		$installed = $style['installed'];

		$cssFiles = glob($dir . '/*.css');

		if (!empty($title) && !empty($author) && !empty($version) && count($cssFiles) > 0) {
			// compatible?
			$minVersion = str_replace('.', '', $minVersion);
			$maxVersion = str_replace('.', '', $maxVersion);
			$currVersion = str_replace('.', '', VERSION);

			if ($minVersion && $maxVersion) {
				$compatible = ($currVersion <= $maxVersion && $currVersion >= $minVersion);
			} else if ($minVersion) {
				$compatible = $currVersion >= $minVersion;
			} else if ($maxVersion) {
				$compatible = $currVersion <= $maxVersion;
			} else {
				$compatible = true;
			}

			template::assignBlock('styles', array(
				'TITLE'			=>	htmlspecialchars($title),
				'AUTHOR'		=>	htmlspecialchars($author),
				'URL'			=>	$URL,
				'VERSION'		=>	htmlspecialchars($version),
				'MINVERSION'	=>	($minVersion) ? htmlspecialchars($style['minVersion']) : '-',
				'MAXVERSION'	=>	($maxVersion) ? htmlspecialchars($style['maxVersion']) : '-',
				'DIR'			=>	$dir,
				'DIRNAME'		=>	$dirName,
				'NONREMOVABLE'	=>	strtolower($dirName) == 'standard',
				'COMPATIBLE'	=>	$compatible,
				'ACTIVATED'		=>	$config['theme'] == $dirName
			));
		} else {
			template::assignBlock('badStyles', array(
				'DIR'			=>	$dir,
				'DIRNAME'		=>	$dirName,
				'NONREMOVABLE'	=>	strtolower($dirName) == 'standard'
			));
		}
	}

	template::display('styles');
?>