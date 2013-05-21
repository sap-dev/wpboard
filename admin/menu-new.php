<?php
	/**
	*
	* @package com.Itschi.ACP.bots.new
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
	$agent = (isset($_POST['agent'])) ? strip($_POST['agent']) : '';
	$icon = (isset($_POST['icon'])) ? strip($_POST['icon']) : '';

	if (isset($_POST['submit'])) {
		if (!$name || !$agent) {
			$error = 1;
		} else {
			if ($id) {
				$db->query('
					UPDATE ' . MENU_TABLE . "
					SET	menu_text	=	'" . $db->chars($name) . "',
						menu_link	=	'" . $db->chars($agent) . "'
						menu_icon	=	'" . $db->chars($icon) . "'
					WHERE menu_id = " . $id
				);
			} else {
				$db->query('
					INSERT INTO ' . MENU_TABLE . "
					(menu_text, menu_link, menu_icon) VALUES
					('" . $db->chars($name) . "', '" . $db->chars($agent) . "', '" . $db->chars($icon) . "')
				");
			}

			header('Location: menu.php');
		}
	} else if ($id) {
		$res = $db->query('
			SELECT *
			FROM ' . MENU_TABLE . '
			WHERE menu_id = ' . $id
		);

		$row = $db->fetch_array($res);

		$db->free_result($res);

		if (!$row['menu_id']) {
			message_box('Der Einrag existiert nicht!', '../', 'zurück');
			exit;
		}

		$name = $row['menu_text'];
		$icon = $row['menu_icon'];
		$agent = $row['menu_link'];
	}

	template::assign(array(
		'MENU_BUTTON'	=>	6,
		'ERROR'		=>	$error,
		'NAME'		=>	htmlspecialchars($name),
		'AGENT'		=>	htmlspecialchars($agent),
		'ICON'		=>	htmlspecialchars($icon),
		'ID'		=>	$id
	));

	template::display('menu-new', true);
?>