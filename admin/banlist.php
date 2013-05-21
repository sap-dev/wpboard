<?php
	/**
	*
	* @package Itschi
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
			message_box('Willst du die Sperre wirklich löschen?', 'banlist.php?delete=' . (int)$_GET['delete'] . '&u=' . (int)$_GET['u'] . '&ok=1', 'Sperre löschen', 'banlist.php', 'Abbrechen');
			exit;
		} else {
			$db->query('
				DELETE
				FROM ' . BANLIST_TABLE . '
				WHERE ban_id = ' . (int)$_GET['delete']
			);

			$db->query('
				UPDATE ' . USERS_TABLE . '
				SET user_ban = 0
				WHERE user_id = ' . (int)$_GET['u']
			);
		}
	}

	$db->query('
		DELETE
		FROM ' . BANLIST_TABLE . '
		WHERE ban_time < ' . time()
	);

	$res = $db->query('
		SELECT COUNT(*)
		FROM ' . BANLIST_TABLE
	);

	$num = $db->result($res, 0);
	$db->free_result($res);

	$perpage = 20;
	$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;
	$pages_num = ceil($num/$perpage);

	$res = $db->query('
		SELECT b.*, u.user_id, u.username
		FROM ' . BANLIST_TABLE . ' b
			LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = b.user_id
		ORDER BY u.username DESC
		LIMIT ' . ($page * $perpage - $perpage) . ',' . $perpage
	);

	while ($row = $db->fetch_array($res)) {
		$res2 = $db->query("
			SELECT username
			FROM " . USERS_TABLE . "
			WHERE user_id = '".$row['by_id']."'
		");
		
		$row2 = $db->fetch_array($res2);
		
		template::assignBlock('banlist', array(
			'ID'		=>	$row['ban_id'],
			'USERNAME'	=>	$row['username'],
			'USER_ID'	=>	$row['user_id'],
			'BY'		=>	$row2['username'],
			'BY_ID'		=>	$row['by_id'],
			'REASON'	=>	htmlspecialchars($row['ban_reason']),
			'TIME'		=>	date('d.m.Y, H:i', $row['ban_time'])
		));
	}

	$db->free_result($res);

	template::assign(array(
		'MENU_BUTTON'	=>	4,
		'NUM'		=>	$num,
		'PAGE'		=>	$page,
		'PAGES_NUM'	=>	$pages_num,
		'PAGES'		=>	($pages_num > 1) ? pages($pages_num, $page, 'banlist.php?page=') : ''
	));

	template::display('banlist', true);
?>