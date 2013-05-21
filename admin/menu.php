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
			message_box('Willst du den Men&uuml;eintrag wirklich löschen?', 'menu.php?delete=' . (int)$_GET['delete'] . '&ok=1', 'Men&uuml;eintrag löschen', 'menu.php', 'Abbrechen');
			exit;
		} else {
			$db->query('
				DELETE
				FROM ' . MENU_TABLE . '
				WHERE menu_id = ' . (int)$_GET['delete']
			);

		}
	}

	$res = $db->query('
		SELECT *
		FROM ' . MENU_TABLE . '
		ORDER BY menu_id ASC
	');

	while ($row = $db->fetch_array($res)) {
		template::assignBlock('menu', array(
			'ID'		=>	$row['menu_id'],
			'NAME'		=>	htmlspecialchars($row['menu_text']),
			'LINK'		=>	$row['menu_link'],
			'ICON'		=>	$row['menu_icon'],
		));
	}

	$bots_num = $db->num_rows($res);
	$db->free_result($res);

	template::assign(array(
		'MENU_BUTTON'	=>	66,
		'NUM'		=>	$bots_num
	));

	template::display('menu', true);
?>