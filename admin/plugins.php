<?php
	/**
	*
	* @package com.Itschi.ACP.plugins
	* @since 2007/05/25
	*
	*/
	require '../base.php';
	require_once '../lib/plugins/plugin.server.php';

	use \Itschi\lib as lib;

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$plugins = new lib\plugins();

	#region Options

	/**
	 * get admin.php of plugin folder
	 */
	
	if(isset($_GET['manage'])) {
		template::display('header');
		include('../plugins/'.$_GET['plugin'].'/files/admin.php');
		template::display('footer');
		die();
	}

	

	/**
	 * remove a plugin-server
	 */
	if (isset($_GET['removeServer'])) {
		$id = (int)$_GET['removeServer'];

		$db->unbuffered_query(sprintf('DELETE FROM %s WHERE server_id = %d', SERVER_TABLE, $id));
	}

	/**
	 * deletes the plugin (from disk)
	 */
	if (isset($_GET['removePlugin'])) {
		$id = (int)$_GET['removePlugin'];

		$res = $db->query('
			SELECT package
			FROM ' . PLUGINS_TABLE . '
			WHERE id = ' . $id . ' AND installed = 0
		');

		$row = $db->fetch_object($res);
		$db->free_result($res);

		$plugin_dir = '../plugins/' . $row->package;
		if(isset($row->package)) {
			if (!lib\plugins::removeFolder($plugin_dir)) {
				message_box('Der Plugin Ordner \'' . $plugin_dir . '\' konnte nicht gelöscht werden.', './plugins.php', 'zurück');
				exit;
			} else {
				$db->unbuffered_query(sprintf('DELETE FROM `%s` WHERE `id` = %d', PLUGINS_TABLE, $id));
			}
		} else {
				message_box('Diese Aktion ist nicht möglich.', './plugins.php', 'zurück');
		}
	}

	/**
	 * install the Plugin
	 */

	if (isset($_GET['install'])) {
		$id = (int)$_GET['install'];

		$plugins->install($id);
	}

	/**
	 * uninstall the Plugin
	 */

	if (isset($_GET['uninstall'])) {
		$id = (int)$_GET['uninstall'];

		$plugins->uninstall($id);
	}

	/**
	 * listed all plugins of a plugin-server
	 */

	if (isset($_GET['list'])) {
		$id = (int)$_GET['list'];

		if ($id <= 0) {
			message_box('Es ist ein Fehler aufgetreten.', './plugins.php', 'Zurück');
			exit;
		}

		$res = $db->query('
			SELECT *
			FROM ' . SERVER_TABLE . '
			WHERE server_id = ' . $id
		);

		$row = $db->fetch_object($res);
		$db->free_result($res);

		$server_url = lib\pluginServer::getPluginListURL($row->server_url);
		$server_data = @json_decode(@file_get_contents($server_url));

		$plugin_mess = '';

		/*
		 *		TODO: Code aufräumen und auf sicherheit prüfen!
		 */

		if (isset($_GET['download'])) {
			$URL = explode('/', str_replace('http://', '', $row->server_url));
			$host = $URL[0];
			$URL[0] = NULL;
			unset($URL[0]);

			$requestFile = implode($URL, '/').$_GET['download'].'.zip';

			$PluginRequest = HTTPRequest::alloc(HTTP::OPT_METHOD_GET | HTTP::OPT_USE_UTF);

			$PluginRequest->setOpt(HTTP::OPT_HOST, $host);
			$PluginRequest->setOpt(HTTP::OPT_TIMEOUT, 5);
			$PluginRequest->setOpt(HTTP::OPT_REQ_FILE, $requestFile);

			$plugin_mess = $PluginRequest->send(function(HTTPResponse $response) {
				global $plugins;

				if ($response->getErrorCode() == 0) {
					if ($response->getResponseCode() == 200) {
						/*
							save & extract & sync
						*/
						$plugin = md5($_GET['download']);
						$pluginFile = '../plugins/'.$plugin.'.zip';

						file_put_contents($pluginFile, $response->getResponse());

						$zip = new ZipArchive();

						if ($zip->open($pluginFile) !== true) {
							@unlink($pluginFile);

							return 'Konnte ZIP Datei nicht öffnen';
						}

						$zip->extractTo('../plugins/'.$_GET['download'].'/'); // ziemlich unsicher und kacke, aber vorerst OK
						$zip->close();

						$plugins->syncLocal();

						@unlink($pluginFile);

						return 'Plugin “'.htmlspecialchars($_GET['download']).'” wurde heruntergeladen';
					} else {
						return 'HTTP Status Code '.$response->getResponseCode();
					}
				} else {
					return htmlspecialchars($response->getErrorString());
				}
			});
		}

		$plugincount = 0;

		if (is_array($server_data)) {
			foreach ($server_data as $data) {
				++$plugincount;

				$res = $db->query('
					SELECT `package`
					FROM ' . PLUGINS_TABLE . '
					WHERE `package` = \'' . $db->chars($data->package) . '\'');

				// never trust user data
				template::assignBlock('plugins', array(
					'NAME'			=>	htmlspecialchars($data->name),
					'VERSION'		=>	htmlspecialchars($data->version),
					'DESCRIPTION'	=>	htmlspecialchars($data->description),
					'LASTUPDATE'	=>	htmlspecialchars($data->lastUpdate),
					'DEVELOPER'		=>	htmlspecialchars($data->developer),
					'PACKAGE'		=>	htmlspecialchars($data->package),
					'EXISTING'		=>	(bool) $db->num_rows($res),
				));

				$db->free_result($res);
			}
		}

		template::assign(array(
			'SERVERID'		=>	htmlspecialchars($row->server_id),
			'SERVERNAME'	=>	htmlspecialchars($row->server_name),
			'PLUGINCOUNT'	=>	$plugincount, // count is nasty
			'MESSAGE'		=>	$plugin_mess
		));

		template::display('plugin-list');
		exit; // exit da hier ein anderes template angezeigt wird
	}

	#endregion Options

	$plugins->syncLocal();

	// get plugin server from database
	$res = $db->query("
		SELECT *
		FROM " . SERVER_TABLE . "
		ORDER BY server_id ASC
	");

	$count = 0;

	while ($row = $db->fetch_object($res)) {
		$server_id 			= $row->server_id;
		$server_name 		= $row->server_name;
		$server_url 		= $row->server_url;

		$server_plugin_file = lib\pluginServer::getPluginListURL($server_url);
		$server_content = @file_get_contents($server_plugin_file);
		$server_status = @json_decode($server_content);
		unset($server_content);
		$server_status = ($server_status == NULL || $server_status == false ? false : true);

		// assign
		template::assignBlock('server', array(
			'ID'			=>	$server_id,
			'NAME'			=>	htmlspecialchars($server_name),
			'URL'			=>	htmlspecialchars(urldecode($server_url)),
			'SERVERSTATUS'	=> 	$server_status
		));
		++$count;
	}

	template::assign('pluginServerCount', $count);

	// get plugins from database
	$res = $db->query("
		SELECT *
		FROM " . PLUGINS_TABLE . "
		ORDER BY title ASC, installed ASC
	");

	while ($row = $db->fetch_object($res)) {
		$title = $row->title;
		$permissions = @json_decode($row->permissions, true);
		$p = $permissions;
		$dependencyList = @json_decode($row->dependencies, true);
		$minVersion = $row->minVersion;
		$maxVersion = $row->maxVersion;
		$package = $row->package;
		$URL = $row->URL;
		$version = $row->version;

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

		if ($permissions && ($p['SQL'] || $p['TPL'] || $p['HTTP'] || $p['FILES'] || $p['CACHE'])) {
			$pL = '<ul>';

			if ($p['SQL']) {
				$pL .= '
					<li class="main">Datenbank-Zugriff
						<ul>
				';

					if ($p['SQL']['createTables']) {
						$pL .= '<li>Tabellen erstellen</li>';
					}

					if ($p['SQL']['accessTables']) {
						$pL .= '<li>Diese Tabellen lesen und beschreiben: <ul>';

						foreach($p['SQL']['accessTables'] as $t) {
							if ($t != 'config' && $t != 'plugins' && $t != 'users') $pL .= '<li>'.$prefix.htmlspecialchars($t).'</li>';
						}

						$pL .= '
								</ul>
							</li>
						';
					}

				$pL .= '</ul>';
			}

			if ($p['TPL']) {
				$pL .= '<li class="main">HTML und JavaScript in Templates einfügen</li>';
			}

			if ($p['HTTP']) {
				$pL .= '
					<li class="main">Verbindung zu diesen externen Servern aufnehmen:
						<ul>
				';

				foreach($p['HTTP'] as $s) {
					$pL .= '<li>'.htmlspecialchars($s).'</li>';
				}

				$pL .= '
						</ul>
					</li>
				';
			}

			if ($p['FILES']) {
				$pL .= '
					<li class="main">Dateien im Plugin-Ordner:
						<ul>
				';

				if ($p['FILES']['accessFiles']) {
					$pL .= '<li>Lesen</li>';
				}

				if ($p['FILES']['writeFiles']) {
					$pL .= '<li>Beschreiben</li>';
				}

				$pL .= '
						</ul>
					</li>
				';
			}

			if ($p['CACHE']) {
				$pL .= '
					<li class="main">Cache...
						<ul>
				';

				if ($p['CACHE']['readCache']) {
					$pL .= '<li>Lesen</li>';
				}

				if ($p['CACHE']['writeCache']) {
					$pL .= '<li>Beschreiben</li>';
				}

				$pL .= '
						</ul>
					</li>
				';
			}
		}

		$dependencies = array();
		if (isset($dependencyList)) {
			foreach($dependencyList as $dPackage) {
				$dependencies[$dPackage] = $plugins->checkDependency($package, $dPackage);
			}
		}

		// assign
		template::assignBlock(($row->installed) ? 'plugins' : 'available', array(
			'NAME'			=>	htmlspecialchars($title),
			'PACKAGE'		=>	htmlspecialchars($package),
			'COMPATIBLE'	=>	$compatible,
			'VERSION'		=>	$version,
			'PERMISSIONS'	=>	$pL,
			'DEPENDENCIES'	=>	$dependencies,
			'MISSING_DEPENDENCY'	=>	in_array(0, $dependencies),
			'ID'			=>	$row->id,
			'MINVERSION'	=>	($minVersion) ? htmlspecialchars($row->minVersion) : '-',
			'MAXVERSION'	=>	($maxVersion) ? htmlspecialchars($row->maxVersion) : '-'
		));


		// count
		if ($row->installed) {
			$installed++;
		} else {
			$available++;
		}
	}

	template::assign(array(
		'AVAILABLE'	=>	$available > 0,
		'INSTALLED'	=>	$installed > 0
	));

	template::display('plugins');
?>