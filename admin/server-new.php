<?php
	/**
	*
	* @package com.Itschi.ACP.server.new
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$error = '';

	$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
	$name = (isset($_POST['name'])) ? strip($_POST['name']) : '';
	$url = (isset($_POST['url'])) ? strip($_POST['url']) : '';

	if (isset($_POST['submit'])) {
		if (!$name || !$url) {
			$error = 1;
		} else {
			if ($id) {
				$db->query('
					UPDATE ' . SERVER_TABLE . "
					SET	server_name	=	'" . $db->chars($name) . "',
						server_url	=	'" . $db->chars($url) . "'
					WHERE server_id = " . $id
				);
			} else {
				$db->query('
					INSERT INTO ' . SERVER_TABLE . "
					(server_name, server_url) VALUES
					('" . $db->chars($name) . "', '" . $db->chars($url) . "')
				");
			}

			//$cache->delete('bots');//TODO

			header('Location: plugins.php');
		}
	} else if ($id) {
		$res = $db->query('
			SELECT *
			FROM ' . SERVER_TABLE . '
			WHERE server_id = ' . $id
		);

		$row = $db->fetch_array($res);

		$db->free_result($res);

		if (!$row['server_id']) {
			message_box('Der Server existiert nicht!', '../', 'zurück');
			exit;
		}

		$name = $row['server_name'];
		$url = $row['server_url'];
	}

	template::assign(array(
		'MENU_BUTTON'	=>	6,
		'ERROR'		=>	$error,
		'NAME'		=>	htmlspecialchars($name),
		'URL'		=>	htmlspecialchars($url),
		'ID'		=>	$id
	));

	template::display('server-new', true);
?>