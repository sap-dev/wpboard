<?php
	/**
	*
	* @package com.Itschi.ACP.bots
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	if (isset($_GET['delete'])) {
		if (empty($_GET['ok'])) {
			message_box('Willst du den Bot wirklich löschen?', 'bots.php?delete=' . (int)$_GET['delete'] . '&ok=1', 'Bot löschen', 'bots.php', 'Abbrechen');
			exit;
		} else {
			$db->query('
				DELETE
				FROM ' . BOTS_TABLE . '
				WHERE bot_id = ' . (int)$_GET['delete']
			);

			$cache->delete('bots');
		}
	}

	$res = $db->query('
		SELECT *
		FROM ' . BOTS_TABLE . '
		ORDER BY bot_name ASC
	');

	while ($row = $db->fetch_array($res)) {
		template::assignBlock('bots', array(
			'ID'	=>	$row['bot_id'],
			'NAME'	=>	htmlspecialchars($row['bot_name']),
			'AGENT'	=>	htmlspecialchars($row['bot_agent'])
		));
	}

	$bots_num = $db->num_rows($res);
	$db->free_result($res);

	template::assign(array(
		'MENU_BUTTON'	=>	6,
		'NUM'		=>	$bots_num
	));

	template::display('bots', true);
?>