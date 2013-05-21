<?php
	/**
	*
	* @package com.Itschi.ACP.smilies
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
			message_box('Willst du den Smilie wirklich löschen?', 'smilies.php?delete=' . (int)$_GET['delete'] . '&ok=1', 'Smilie löschen', 'smilies.php', 'Abbrechen');
			exit;
		} else {
			$db->query('
				DELETE
				FROM ' . SMILIES_TABLE . '
				WHERE smilie_id = ' . (int)$_GET['delete']
			);

			$cache->delete('smilies');
			$cache->delete('smilies_group');
		}
	}

	$res = $db->query('
		SELECT *
		FROM ' . SMILIES_TABLE . '
		ORDER BY smilie_id ASC
	');

	$num = $db->num_rows($res);

	while ($row = $db->fetch_array($res)) {
		template::assignBlock('smilies', array(
			'ID'		=>	$row['smilie_id'],
			'IMAGE'		=>	$row['smilie_image'],
			'EMOTION'	=>	htmlspecialchars($row['smilie_emotion'])
		));
	}

	$db->free_result($res);

	template::assign(array(
		'MENU_BUTTON'	=>	7,
		'NUM'		=>	$num
	));

	template::display('smilies', true);
?>