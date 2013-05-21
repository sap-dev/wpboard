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

	if (isset($_POST['submit'])) {
		if (!$name || !$agent) {
			$error = 1;
		} else {
			if ($id) {
				$db->query('
					UPDATE ' . BOTS_TABLE . "
					SET	bot_name	=	'" . $db->chars($name) . "',
						bot_agent	=	'" . $db->chars($agent) . "'
					WHERE bot_id = " . $id
				);
			} else {
				$db->query('
					INSERT INTO ' . BOTS_TABLE . "
					(bot_name, bot_agent) VALUES
					('" . $db->chars($name) . "', '" . $db->chars($agent) . "')
				");
			}

			$cache->delete('bots');

			header('Location: bots.php');
		}
	} else if ($id) {
		$res = $db->query('
			SELECT *
			FROM ' . BOTS_TABLE . '
			WHERE bot_id = ' . $id
		);

		$row = $db->fetch_array($res);

		$db->free_result($res);

		if (!$row['bot_id']) {
			message_box('Der Bot existiert nicht!', '../', 'zurück');
			exit;
		}

		$name = $row['bot_name'];
		$agent = $row['bot_agent'];
	}

	template::assign(array(
		'MENU_BUTTON'	=>	6,
		'ERROR'		=>	$error,
		'NAME'		=>	htmlspecialchars($name),
		'AGENT'		=>	htmlspecialchars($agent),
		'ID'		=>	$id
	));

	template::display('bot-new', true);
?>