<?php
	/**
	*
	* 	@package com.Itschi.base.plugins.utils
	* 	@since 2013/02/06
	*
	*	DO NOT MODIFY ANY OF THESE FUNCTIONS.
	*	These functions are essential for the use of plugins.
	*	Editing may cause your cat to be eaten by your subwoofer
	*	or serious frozen air around your head.
	*	It may also cause headaches.
	*/

	// TMP CHARSET
	if (!defined('CHARSET')) {
		define('CHARSET', 'UTF-8');
	}

	final class utils extends plugin {
		/*
			@arrayIndexCache
		*/
		private static $arrayIndexCache = array();

		/*
			String functions
		*/

		public function makeClickable($str) {
			return make_clickable($str);
		}

		public function replace($text, $bbcodes, $smilies, $make_clickable, $html = true) {
			return replace($text, $bbcodes, $smilies, $make_clickable, $html);
		}

		public function strToUpper($str) {
			return mb_strtoupper($str, CHARSET);
		}

		public function strToLower($str) {
			return mb_strtolower($str, CHARSET);
		}

		public function strLength($str, $raw = FALSE) {
			return ($raw ? strlen($str) : mb_strlen($str, CHARSET));
		}

		public function strSubstr($str, $start, $length = NULL) {
			if ($length == NULL) $length = $this->strLength($str);

			return mb_substr($str, $start, $length, CHARSET);
		}

		/*
			Array functions
		*/

		/*
			@name	arrayClearIndexCache

			@author	marco.a

			@return	void
		*/
		public static function arrayClearIndexCache() {
			self::$arrayIndexCache = array();
		}

		/*
			@name	arrayGetIndexCache

			@author	marco.a

			@return	array
		*/
		public static function arrayGetIndexCache() {
			return (is_array(self::$arrayIndexCache) ? self::$arrayIndexCache : array());
		}

		/*
			@name	arraySearchIndexByValue

			@author	marco.a

			@param	array	$array	the array to search in
			@param	mixed	$value	value to be searched
			@param	bool	$strictType	enable or disable strict value comparison between $value and array item [disabled]
			@param	bool	$collectIndexes	immediately return first found index [enabled]
			@param	string	$cacheName	cache result in an array [empty -> disabled]

			@return	mixed	index, -1 when not found or array (when multiple indexes are found and $collectIndexes is TRUE)
		*/
		public static function arraySearchIndexByValue($array, $value, $strictType = FALSE, $collectIndexes = TRUE, $cacheName = '') {
			if (!empty($cacheName)) {
				if (!isset(self::$arrayIndexCache[$cacheName])) {
					self::$arrayIndexCache[$cacheName] = 0;
				} else {
					return self::$arrayIndexCache[$cacheName];
				}
			}

			$counter = 0;
			$index = -1;
			$tmp = array();

			$arrayKey = NULL;
			$arrayValue = NULL;

			foreach ($array as $arrayKey => $arrayValue) {
				if (($arrayValue == $value && $strictType == FALSE) || ($arrayValue === $value && $strictType == TRUE)) {
					array_push($tmp, $arrayKey);

					if ($collectIndexes == FALSE) break;
				}
			}

			$index = (sizeof($tmp) == 1 ? $tmp[0] : ($collectIndexes ? $tmp : -1));

			if (!empty($cacheName)) {
				self::$arrayIndexCache[$cacheName] = $index;
			}

			return $index;
		}

		/*
			@name	miscScanDir

			@author	marco.a

			@param	string	$directory	directory path
			@param	callback	$callback	callback function
			@param	bool	$dirAfterFiles	call callback with directory after files (<- kack englisch? ka wie man das schreiben soll)

			@return	false or return value of callback
		*/
		public static function miscScanDir($directory, $callback, $dirAfterFiles = TRUE) {
			if (!is_dir($directory) || !is_callable($callback)) return FALSE;

			$handle = opendir($directory);

			if ($handle == FALSE) return FALSE;

			while (($file = readdir($handle)) !== FALSE) {
				if ($file == '.' || $file == '..') continue;

				$absolutePath = $directory.DIRECTORY_SEPARATOR.$file;

				if (is_file($absolutePath)) {
					$callback($file, $absolutePath, false);
				} else if (is_dir($absolutePath)) {
					if ($dirAfterFiles == FALSE) $callback($file, $absolutePath, true);

					self::miscScanDir($absolutePath, $callback, $dirAfterFiles);

					if ($dirAfterFiles) $callback($file, $absolutePath, true);
				}
			}

			closedir($handle);

			return $callback;
		}

		/*
			site functions
		*/

		public function loggedIn() {
			global $user;

			return $user->row;
		}

		public function login_box() {
			if (!$this->loggedIn()) login_box();
		}

		public function message_box($message, $link, $link_text, $link2 = '', $link2_text = '', $refresh = false) {
			message_box($message, $link, $link_text, $link2, $link2_text, $refresh);
		}

		public function pages($gesamt, $pages, $link) {
			pages($gesamt, $pages, $link);
		}

		public function strip($var) {
			return (STRIP) ? stripslashes($var) : $var;
		}
		public function getVar($var) {
			global $config;
			return $config[$var];
		}
	}

?>
