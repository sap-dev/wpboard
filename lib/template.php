<?php
	/**
	*
	* @package com.Itschi.base.template
	* @since 2007/05/25
	*
	*/

	abstract class template {
		protected static $root;
		protected static $vars = array();
		protected static $blocks = array();
		private static $end = false;
		private static $debug = false;
		private static $areasChecked = false;

		/**
		 *	@name 	menu
		 *			Holds all menu-items so plugins can add some.
		 */
		
		protected static $menu = array();

		/**
		 *	@name 	style
		 *			Holds style-directory and style-directory's name.
		 */

		public static $style = array(
			'dir'	=>	'standard',
			'dirName'	=>	'standard',
		);

		/**
		 *	@name 	areas
		 *			Holds all available (registered) areas.
		 */

		private static $areas = array(
			'menuPlugin'
		);

		private static $importantAreas = array(
			'header' => 0,
			'footer' => 0
		);

		public static function init() {
			global $db, $root, $config;

			$json = @json_decode(@file_get_contents($root.'/styles/' . $config['theme'] . '/style.json'), true);

			if (!is_array($json)) {
				$json = @json_decode(@file_get_contents($root.'/styles/standard/style.json'), true);

				if (!is_array($json)) {
					self::logError('no template to display');
				}

				$config['theme'] = 'standard';
			}

			self::$style = array(
				'title'		=>	$json['title'],
				'author'	=>	$json['author'],
				'version'	=>	$json['version'],
				'dir'		=>	$root . 'styles/' . $config['theme'] . '/',
				'dirName'	=>	$config['theme']
			);

			if (!file_exists(self::$style['dir'] . 'functions.php')) {
				self::logError('functions.php missing.', 'TPL');
			} else {
				include self::$style['dir'] . 'functions.php';

				if (!function_exists('initializeStyle')) {
					self::logError('function initializeStyle() missing.', 'TPL');
				} else {
					initializeStyle();
				}
			}

			self::$root = $root;
		}

		/**
		 *	@name 	logError
		 *			prints an error
		 *
		 *	@param 	string $error
		 *	@return void
		 */

		public static function logError($error) {
			$error = preg_replace('^\*([\S]+)\*^m', '<b>$1</b>', htmlspecialchars($error));

			echo '
				<link rel="stylesheet" href="styles/error.css" />

				<div id="fatalError">
					<div class="title"><h2>Es ist ein Fehler aufgetreten.</h2></div>

					<div class="error">
						<code>
							<b>TPL:</b> '.$error.'
						</code>
					</div>

					<div class="info">
						<small>Verursacht durch: '.htmlspecialchars(self::$style['title']).'</small>
					</div>
				</div>
			';
			die();
			exit;
		}

		/**
		 *	@name 	assign
		 *			Assigns one ore more variables for use in a template.
		 *
		 *	@param 	mixed $vars
		 *	@param 	string $value
		 *	@return true
		 */

		public static function assign($vars, $value = '') {
			if (is_array($vars)) {
				foreach($vars as $k => $v) {
					self::$vars[$k] = $v;
				}
			} else {
				self::$vars[$vars] = $value;
			}

			return true;
		}

		/**
		 *	@name 	assignBlock
		 *			Assigns an array (a block) for use in a template (mainly for foreach()).
		 *
		 *	@param 	string $name
		 *	@param 	array $values
		 *	@return true
		 */

		public static function assignBlock($name, $values) {
			self::$blocks[$name][] = $values;
			return true;
		}
		
		
		public static function getLanguage($lang, $code) {
			global $db, $config;
			echo $row['lang_lang'];
			$res = $db->query("
				SELECT *
				FROM " . LANGUAGE_TABLE . "
				WHERE lang_lang = '".$lang."' AND
				lang_code = '".$code."'"
			);

			$row = $db->fetch_array($res);
			echo $db->num_rows;
			if(!$row['lang_translate']) {
				return self::logError('Language Code ('.$code.', Language: '.$lang.') does not exist', 'LANG');
			}
			return $row['lang_translate'];
		}

		/**
		 *	@name 	getVar
		 *			Returns the value of a variable assigned with assign.
		 *
		 *	@param 	string $var
		 *	@return string
		 */

		public static function getVar($var) {
			return self::$vars[$var];
		}

		/**
		 *	@name 	getPage
		 *			Gets the name of the file currently displayed.
		 *
		 *	@return string
		 */

		public static function getPage() {
			return self::getVar('PAGE');
		}

		/**
		 *	@name 	getStyleDirName
		 *			Returns style's directory name.
		 *
		 *	@return string
		 */

		public static function getStyleDirName() {
			return self::$style['dirName'];
		}

		/**
		 *	@name 	areaAvailable
		 *			Checks whether an area is avaialble (registered) or not.
		 *
		 *	@return boolean
		 */

		public static function areaAvailable($area) {
			foreach (self::$areas as $a => $c) {
				if ($a == $area) return true;
			}

			return false;
		}

		/**
		 *	@name 	registerArea
		 *			Registers an area and marks it as available for plugins.
		 *
		 *	@return void
		 */

		public static function registerArea($area) {
			if (is_array($area)) {
				foreach ($area as $a) {
					self::$areas[$a] = '';
				}
			} else {
				self::$areas[$area] = '';
			}

			self::checkRegisteredAreas();
		}

		/**
		 *	@name 	checkRegisteredAreas
		 *			Checks once (!) whether important areas are defined.
		 *
		 *	@return void
		 */

		private static function checkRegisteredAreas() {
			if (!self::$areasChecked) {
				foreach (self::$areas as $k => $v) {
					if (isset(self::$importantAreas[$k])) {
						self::$importantAreas[$k] = 1;
					}
				}

				foreach (self::$importantAreas as $k => $v) {
					if ($v === 0) {
						self::logError('Area "'.$k.'" is not implemented.', 'TPL');
					}
				}

				self::$areasChecked = true;
			}
		}

		/**
		 *	@name 	displayArea
		 *			Displays an area's content in a template.
		 *
		 *	@return mixed
		 */

		public static function displayArea($area) {
			if (!self::areaAvailable($area)) {
				return self::logError('Area *' . htmlspecialchars($area) . '* not registered.', 'TPL');
			}

			echo self::$areas[$area];
			return self::$areas[$area];
		}

		/**
		 *	@name 	addToArea
		 *			Adds content to a registered and available area.
		 *
		 *	@return mixed
		 */

		public static function addToArea($area, $content) {
			if (!self::areaAvailable($area)) {
				return self::logError('Area *' . htmlspecialchars($area) . '* not registered.', 'TPL');
			}

			self::$areas[$area] .= $content;
		}

		/**
		 *	@name 	addToMenu
		 *			Adds an item to the template's menu.
		 *
		 *	@param 	string $title
		 *	@param 	string $link
		 *	@return boolean
		 */

		public static function addToMenu($title, $link) {
			if (!preg_match('^(ht|f)tps?\:\/\/^', $link)) {
				self::$menu[$link] = $title;
				return true;
			}

			return false;
		}

		/**
		 *	@name 	getMenu
		 *			Returns an array containing all menu items for use in a template.
		 *
		 *	@return array
		 */

		public static function getMenu() {
			return self::$menu;
		}

		/**
		 *	@name 	end
		 *			Prohibits more templates to display.
		 *
		 *	@return void
		 */

		public static function end() {
			self::$end = true;
		}

		/**
		 *	@name 	isDebug
		 *			Returns true if template-system is in debug-mode.
		 *
		 *	@return boolean
		 */

		public static function isDebug() {
			return self::$debug;
		}

		/**
		 *	@name 	fetch
		 *			fetch a template-file.
		 *
		 *	@param 	string $section
		 * 	@return string
		 */

		public static function fetch($section) {
			global $token, $user, $db, $root, $phpdate, $config;

			if (!self::$end) {
				page_vars();
				ob_start();

				if (self::$debug) {
					echo '<!--';
					print_r(self::$vars);
					print_r(self::$blocks);
					echo '-->';
				}

				$file = (preg_match('^/admin/^', $_SERVER['SCRIPT_NAME'])) ? './template/' . $section . '.php' : self::$style['dir'] . $section . '.php';

				if (!is_file($file)) {
					self::logError('File "'.$section.'.php" does not exist.');
				} else {
					include $file;
				}

				$content = ob_get_contents();
				ob_end_clean();

				if (is_object($token)) {
					$content = $token->auto_append($content);
				}

				return $content;
			}
		}

		public static function fetch_plugin_tpl($package, $section, $admin) {
			global $token, $user, $db, $root, $phpdate, $language;

			if (!self::$end) {
				page_vars();
				ob_start();

				if (self::$debug) {
					echo '<!--';
					print_r(self::$vars);
					print_r(self::$blocks);
					echo '-->';
				}
				if($admin == "adminTPL") {
					$file = self::$root.'plugins/'.$package.'/files/templates/admin/'.$section.'.php';
				} else {		
					$file = 'plugins/'.$package.'/files/templates/'.$section.'.php';
				}
				if (!is_file($file)) {
					self::logError('File "plugins/'.$package.'/files/templates/'.$section.'.php" does not exist.');
				} else {
					include $file;
				}

				$content = ob_get_contents();
				ob_end_clean();

				if (is_object($token)) {
					$content = $token->auto_append($content);
				}

				return $content;
			}
		}


		/**
		 *	@name 	display
		 *			Displays a template-file.
		 *
		 *	@param 	string $section
		 * 	@return void
		 */

		public static function display($section) {
			global $language;
			if (!self::$end) {
				$content = self::fetch($section);
				if (!is_null($content)) {
					echo $content;
				}
			}
		}

		public static function display_plugin_tpl($package, $section, $admin) {
			if (!self::$end) {
				$content = self::fetch_plugin_tpl($package, $section, $admin);
				if (!is_null($content)) {
					echo $content;
				}
			}
		}
		
	

		public static function strShort($str, $lengh) {
			if(strlen($str) > $lengh) {
				$points = '...';	
			}
			$str = substr($str, 0, $lengh);
			return $str.$points;
		}


	}
?>