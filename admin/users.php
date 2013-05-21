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
			message_box('Willst du das Mitglied wirklich löschen?', 'users.php?delete=' . (int)$_GET['delete'] . '&ok=1', 'Mitglied löschen', 'users.php', 'Abbrechen');
			exit;
		} else {
			functions::user()->delete_user($_GET['delete']);
		}
	}

	$username = (isset($_GET['user'])) ? $_GET['user'] : '';
	$email = (isset($_GET['email'])) ? $_GET['email'] : '';
	$ip = (isset($_GET['ip'])) ? $_GET['ip'] : '';

	$sql = '';
	$sql .= ($username) ? "username LIKE '%" . $db->chars($username) . "%'" : '';
	$sql .= ($email) ? (($sql) ? ' AND ' : '') . "user_email LIKE '%" . $db->chars($email) . "%'" : '';
	$sql .= ($ip) ? (($sql) ? ' AND ' : '') . "user_ip LIKE '%" . $db->chars($ip) . "%'" : '';
	$sql = ($sql) ? ' WHERE ' . $sql : '';

	$res = $db->query('
		SELECT COUNT(*)
		FROM ' . USERS_TABLE . '
		' . $sql
	);

	$users_num = $db->result($res, 0);
	$db->free_result($res);

	$perpage = 20;
	$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;
	$pages_num = ceil($users_num/$perpage);

	$res = $db->query('
		SELECT user_id, username, user_posts, user_email
		FROM ' . USERS_TABLE . '
		' . $sql . '
		ORDER BY user_id ASC
		LIMIT ' . ($page * $perpage - $perpage) . ',' . $perpage
	);

	while ($row = $db->fetch_array($res)) {
		template::assignBlock('users', array(
			'ID'		=>	$row['user_id'],
			'USERNAME'	=>	$row['username'],
			'POSTS'		=>	$row['user_posts'],
			'EMAIL'		=>	htmlspecialchars($row['user_email'])
		));
	}

	$db->free_result($res);

	template::assign(array(
		'MENU_BUTTON'	=>	3,
		'NUM'		=>	number_format($users_num, 0, '', '.'),
		'S_USERNAME'	=>	htmlspecialchars($username),
		'S_EMAIL'	=>	htmlspecialchars($email),
		'S_IP'		=>	htmlspecialchars($ip),
		'PAGE'		=>	$page,
		'PAGES_NUM'	=>	$pages_num,
		'PAGES'		=>	($pages_num > 1) ? pages($pages_num, $page, 'users.php?user=' . $username . '&email=' . $email . '&ip=' . $ip . '&page=') : ''
	));

	template::display('users', true);
?>