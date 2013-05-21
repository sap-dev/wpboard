<?php
	/**
	*
	* @package com.Itschi.base.styles.ACP
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib;

	class styles {
		public $styles = array();

		public function __construct() {
			global $config;

			$files = glob('../styles/*', GLOB_ONLYDIR);
			foreach ($files as $file) {
				$json = @json_decode(file_get_contents($file . '/style.json'), true);

				if ($json) {
					$this->styles[] = array(
						'title'			=>	$json['title'],
						'author'		=>	$json['author'],
						'URL'			=>	$json['URL'],
						'version'		=>	$json['version'],
						'minVersion'	=>	$json['minVersion'],
						'maxVersion'	=>	$json['maxVersion'],
						'dir'			=>	$file,
						'dirName'		=>	basename($file),
						'installed'		=>	$config['theme'] == basename($file)
					);
				}
			}
		}

		public function activate($style) {
			global $db, $cache;

			$styleDir = '../styles/' . $style;

			if (file_exists($styleDir . '/style.json')) {
				config_set('theme', $db->chars($style));
				$cache->delete('config');
			}
		}

		public function remove($style) {
			global $db;

			$styleDir = '../styles/' . $style;
			if (is_dir($styleDir)) {
				$this->deleteDir($styleDir);
			}
		}

		protected function deleteDir($dirPath) {
			$files = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::CHILD_FIRST
			);

			foreach ($files as $fileinfo) {
				$todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
				$todo($fileinfo->getRealPath());
			}

			rmdir($dirPath);
		}
	}
?>