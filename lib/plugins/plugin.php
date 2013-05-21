<?php
	/**
	*
	* @package com.Itschi.base.plugins
	* @since 2007/05/25
	*
	*/

	require 'plugin.interface.php';

	abstract class plugin implements pluginInterface {
		protected static $package;
		protected static $permissions = array();

		private static $objs = NULL;

		/**
		 * @name init_classes
		 *
		 * @return void
		 */
		public static function init_classes() {
			if (self::$objs != NULL) return;

			self::$objs = array();

			self::$objs['SQL'] = new SQL();
			self::$objs['TPL'] = new TPL();
			self::$objs['utils'] = new utils();
		}

		/**
		 *	@name init
		 *
		 *	@return void
		 */

		public static function init($package) {
			self::$package = $package;

			self::$permissions = self::getPermissionList();
		}

		/**
		 *	@name logError
		 *
		 *	@return void
		 */

		protected static function logError($error, $type = '') {
			if (!empty($type)) {
				$type = '<b>'.htmlspecialchars($type).'</b>: ';
			}

			$error = preg_replace('^\*([\S]+)\*^m', '<b>$1</b>', $error);

			echo '
				<link rel="stylesheet" href="styles/error.css" />

				<div id="fatalError">
					<div class="title"><h2>Es ist ein Fehler aufgetreten.</h2></div>

					<div class="error">
						<code>
							'.$type.''.$error.'
						</code>
					</div>

					<div class="info">
						<small>Verursacht durch: '.htmlspecialchars(self::$package).'</small>
					</div>
				</div>
			';

			exit;
		}

		/**
		 *	@name getPermissions
		 *
		 *	@return array
		 */

		protected static function getPermissionList() {
			global $db;

			$res = $db->query("SELECT permissions FROM " . PLUGINS_TABLE . " WHERE package = '" . self::$package . "'");
			$row = $db->fetch_object($res);

			if (isset($row->permissions)) {
				$json = @json_decode($row->permissions, true);

				return (is_array($json) ? $json : array());
			} else {
				return array();
			}
		}

		/**
		 * @name hasPermission
		 *
		 * @return bool
		 */

		protected static function hasPermission($type, $options = array()) {
			global $prefix;
			$permissions = self::$permissions;

			switch ($type) {
				case 'TPL':
				case 'HTTP':
					return $permissions[$type] != 0 || is_array($permissions[$type]);
					break;

				case 'SQL':
					if ($options[0] == 'accessTables') {
												return true;
					} else if ($options[0] == 'createTables') {
						if ($permissions['SQL']['createTables'] != 1) {
							self::logError('This plugin does not have the required permissions to create tables.', 'SQL');
							return false;
						}

						return true;
					} else {
						return (isset($permissions['SQL']) && (count($permissions['SQL']['accessTables'] > 0) || $permissions['SQL']['createTables'] == 1));
					}

					break;

				default:
					return false;
			}
		}

		/**
		 *	@name run
		 *
		 *	@return void
		 */

		public static function run() {
			global $root;
			
			$pluginFile = $root.'/plugins/'.self::$package.'/files/main.php'; // <- evil

			if (is_file($pluginFile)) {

				// to be changed
				include $pluginFile;

			} else {
				echo '
					<div class="pluginInfo"><b>Warnung:</b> Plugin "'.htmlspecialchars(self::$package).'" ist unvollständig. (<i>main.php missing</i>)</div>
				';
			}
		}

		/*
			== Class Instances =========================================
			   Do not modify or web server will explode.
			   It is highly recommended to avoid even looking at this code.
			   Why are you still reading this?
			============================================================
		*/

		/**
		 *	@name SQL
		 *
		 *	@return object
		 */

		public static function SQL() {
			return (isset(self::$objs['SQL']) ? self::$objs['SQL'] : NULL);
		}

		/**
		 *	@name TPL
		 *
		 *	@return object
		 */

		public static function TPL() {
			return (isset(self::$objs['TPL']) ? self::$objs['TPL'] : NULL);
		}

		/**
		 *	@name utils
		 *
		 *	@return object
		 */

		public static function utils() {
			return (isset(self::$objs['utils']) ? self::$objs['utils'] : NULL);
		}

	}
?>
