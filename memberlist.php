<?php
	/**
	*
	* @package Itschi
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	$char = (isset($_GET['q'])) ? $_GET['q'] : '';
	$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;
	$mode = ($_GET['mode'] == 'team') ? 'team' : '';

	$perpage = 15;
	$query = '';

	if ($mode == 'team' && !$char) {
		$query = "user_level > 0 AND ";
	} else if ($mode == 'team' && $char) {
		$query = "user_rank = '".$char."' AND ";
	} else if ($mode != 'team' && $char) {
		$query = "username " . (($char == 1) ? "NOT REGEXP '^[a-z]'" : "LIKE '" . $db->chars(substr($char, 0, 1)) . "%'") . " AND ";
	}

	$res = $db->query("
		SELECT COUNT(*)
		FROM " . USERS_TABLE . "
		WHERE " . $query . " user_unlock = ''
	");

	$num = $db->result($res, 0);
	$db->free_result($res);

	$res = $db->query("
		SELECT user_id, username, user_register, user_rank, user_posts, user_avatar, user_level
		FROM " . USERS_TABLE . "
		WHERE " . $query . " user_unlock = ''
		ORDER BY " . (($char) ? 'username' : 'user_id') . "
		LIMIT " . ($page * $perpage - $perpage) . ',' . $perpage . "
	");

	while ($row = $db->fetch_array($res)) {
		template::assignBlock('members', array(
			'USERNAME'	=>	$row['username'],
			'ID'		=>	$row['user_id'],
			'AVATAR'	=>	($row['user_avatar']) ? $row['user_avatar'] : $config['default_avatar'],
			'RANK'		=>	$user->rank($row['user_id'], $row['user_rank'], $row['user_posts']),
			'RANK_ICON'	=>	$user->rank_icon($row['user_id'], $row['user_rank'], $row['user_posts']),
			'LEGEND'	=>	$user->legend($row['user_level']),
			'REGISTER'	=>	date('d.m.Y', $row['user_register']),
			'POSTS	'	=>	number_format($row['user_posts'], 0, '', '.')
		));
	}

	$db->free_result($res);

	$pages_num = ceil($num/$perpage);

	template::assign(array(
		'TITLE_TAG'			=>	'Mitglieder | ',
		'CHAR'				=>	$char,
		'CHAR_UPPERCASE'	=>	strtoupper($char),
		'MODE'				=>	$mode,
		'NUM'				=>	number_format($num, 0, '', '.'),
		'PAGE'				=>	$page,
		'PAGES_NUM'			=>	$pages_num,
		'PAGES'				=>	($pages_num > 1) ? pages($pages_num, $page, 'memberlist.php?mode=' . $mode . '&q=' . htmlspecialchars(substr($char, 0, 1)) . '&page=') : ''
	));

	template::display('memberlist');
?>
