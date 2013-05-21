<?php
	/**
	*
	* @package com.Itschi.base.plugins.ACP
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib;

	class plugins {
		/**
		 *	@name 	install
		 *			Installs a plugin.
		 *
		 *	@param 	integer $id
		 *	@return void
		 */

		public function install($id) {
			global $db;

			$id = (int) $id;
			$res = $db->query('
				SELECT package, dependencies
				FROM ' . PLUGINS_TABLE . '
				WHERE id = ' . $id . ' and installed = 0
			');

			if ($db->num_rows($res) != 1) {
				message_box('Das Plugin konnte nicht installiert werden.', './plugins.php', 'zurück');
			} else {
				$row = $db->fetch_array($res);

				$dependencies = @json_decode($row['dependencies']);
				if (is_array($dependencies)) {
					if (!self::checkAllDependencies($row['package'], $dependencies)) {
						message_box('Das Plugin konnte nicht installiert werden.<br>Es wurden nicht alle Abhängigkeit erfüllt.', './plugins.php', 'zurück');
					} else {
						$db->unbuffered_query(sprintf('UPDATE %s SET installed = \'1\' WHERE id = %d', PLUGINS_TABLE, $id));
						include('../plugins/'.$row['package'].'/files/install.php');
						message_box('Das Plugin wurde installiert.', './plugins.php', 'weiter');
					}
				} else {
					$db->unbuffered_query(sprintf('UPDATE %s SET installed = \'1\' WHERE id = %d', PLUGINS_TABLE, $id));
					include('../plugins/'.$row['package'].'/files/install.php');
					message_box('Das Plugin wurde installiert.', './plugins.php', 'weiter');
				}
			}

			$db->free_result($res);
		}

		/**
		 *	@name 	uninstall
		 *			Uninstalls a plugin.
		 *
		 *	@param 	integer $id
		 *	@return void
		 */

		public function uninstall($id) {
			global $db;

			$id = (int) $id;
			$res = $db->query('
				SELECT package
				FROM ' . PLUGINS_TABLE . '
				WHERE id = ' . $id . ' and installed = 1
			');

			if ($db->num_rows($res) != 1) {
				message_box('Das Plugin konnte nicht deinstalliert werden.', './plugins.php', 'zurück');
			} else {
				$db->unbuffered_query(sprintf('UPDATE %s SET installed = \'0\' WHERE id = %d', PLUGINS_TABLE, $id));
				message_box('Das Plugin wurde deinstalliert.', './plugins.php', 'weiter');
			}

			$db->free_result($res);
		}

		/**
		 *	@name 	syncLocal
		 *			Scans the plugins-folder ans updates them in the database.
		 *
		 *	@return void
		 */

		public function syncLocal() {
			global $db;

			$files = glob('../plugins/*', GLOB_ONLYDIR);
			foreach ($files as $file) {
				$json = @json_decode(file_get_contents($file . '/plugin.json'), true);

				if ($json) {
					$package = $db->chars($json['package']);
					$name = $db->chars($json['name']);
					$permissions = @json_encode($json['permissions']);
					$dependencies = @json_encode($json['dependencies']);
					$minVersion = $db->chars($json['minVersion']);
					$maxVersion = $db->chars($json['maxVersion']);
					$URL = $db->chars($json['URL']);

					$res = $db->query("
						SELECT id
						FROM " . PLUGINS_TABLE . "
						WHERE package = '".$package."'
					");

					$row = $db->fetch_object($res);

					if (!isset($row->id)) {
						$db->query("
							INSERT INTO " . PLUGINS_TABLE . "
							(title, package, permissions, dependencies, minVersion, maxVersion, URL, datum, installed)
							VALUES ('".$name."', '".$package."', '".$permissions."', '".$dependencies."', '".$minVersion."', '".$maxVersion."', '".$URL."', '".time()."', '0')
						");
					} else {
						$db->query("
							UPDATE " . PLUGINS_TABLE . " SET
								`title` = '".$name."',
								`permissions` = '".$permissions."',
								`dependencies` =  '".$dependencies."',
								`minVersion` =  '".$minVersion."',
								`maxVersion` =  '".$maxVersion."',
								`URL` =  '".$URL."',
								`datum` =  '".time()."'
							WHERE `id` = " . $row->id
						);
					}
				}
			}
		}

		/**
		 *	@name 	removeFolder
		 *			Removes a folder recursively.
		 *
		 *	@param 	string $dir
		 *	@return boolean
		 *
		 *	-- @todo	Move this function to a global space so it can be used everywhere.
		 *	--			Itschi needs a global utils class instead of single functions.
		 */

		public static function removeFolder($dir) {
			if (!is_dir($dir) || is_link($dir)) {
				@unlink($dir);

				return true;
			}

			foreach (scandir($dir) as $file) {
				if ($file == '.' || $file == '..') {
					continue;
				}
				if (!self::removeFolder($dir . DIRECTORY_SEPARATOR . $file)) {
					chmod($dir . DIRECTORY_SEPARATOR . $file, 0777);
					if (!self::removeFolder($dir . DIRECTORY_SEPARATOR . $file)) {
						return false;
					}
				};
			}

			return rmdir($dir);
		}

		/**
		 *	@name 	isInstalled
		 *			Checks whether a plugin is installed or not.
		 *
		 *	@param 	string $package
		 *	@return bool
		 */

		public static function isInstalled($package) {
			global $db;

			$res = $db->query('
				SELECT id
				FROM ' . PLUGINS_TABLE . '
				WHERE package = \'' . $db->chars($package) . '\' and installed = 1
			');

			$result = (bool)$db->num_rows($res);
			$db->free_result($res);

			return $result;
		}

		/**
		 *	@name 	checkDependency
		 *			Returns true when a dependency-package is installed.
		 *
		 *	@param 	string $package
		 *	@param 	string $dependencyPackage
		 *	@return boolean
		 */

		public static function checkDependency($package, $dependencyPackage) {
			if ($package == $dependencyPackage) {
				return true;
			}

			return self::isInstalled($dependencyPackage);
		}

		/**
		 *	@name 	checkAppDependencies
		 *			Loops through $dependencyList and looks for unfulfilled dependencies.
		 *
		 *	@param 	string $package
		 *	@param 	array $dependencyList
		 *	@return boolean
		 */

		public static function checkAllDependencies($package, $dependencyList) {
			$fulfilled = true;
			if (is_array($dependencyList)) {
				foreach($dependencyList as $dependencyPackage) {
					$value = self::checkDependency($package, $dependencyPackage);
					if ($fulfilled && !$value) {
						$fulfilled = false;
					}
				}
			}

			return $fulfilled;
		}
	}
?>