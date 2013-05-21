<?php
	/**
	*
	* @package com.Itschi.forum.search
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	if (!function_exists('stripos')) {
		function stripos($string, $find) {
			if (preg_match('#' . preg_quote($find, '#') . '#i', $string, $pos)) {
				return strpos($string, $pos[0]);
			}

			return false;
		}
	}

	$username = (isset($_GET['user'])) ? $_GET['user'] : '';
	$query = (isset($_GET['query'])) ? strip($_GET['query']) : '';

	if ($query || $username) {
		$sql = '';
		$forum = (isset($_GET['forum'])) ? $_GET['forum'] : '';
		$group = (isset($_GET['group'])) ? $_GET['group'] : '';
		$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;

		$user_level = ($user->row) ? $user->row['user_level'] + 1 : 0;

		if ($username) {
			$res = $db->query('
				SELECT user_id
				FROM ' . USERS_TABLE . "
				WHERE username = '" . $db->chars($username) . "'
			");

			$row = $db->fetch_array($res);
			$db->free_result($res);

			$sql .= ' AND p.user_id = ' . (int)$row['user_id'];
		}

		if ($forum) {
			$sql .= ' AND p.forum_id = ' . (int)$forum;
		}

		if ($group == 't') {
			$sql .= ' GROUP BY p.topic_id';
		}

		$res = $db->query('
			SELECT p.post_id
			FROM ' . POSTS_TABLE . ' p
				LEFT JOIN ' . TOPICS_TABLE . ' t ON t.topic_id = p.topic_id
				LEFT JOIN ' . FORUMS_TABLE . ' f ON f.forum_id = p.forum_id AND ' . $user_level . " >= f.forum_level
			WHERE	CONCAT(t.topic_title, p.post_text) LIKE '%" . $db->chars($query) . "%'
				" . $sql
		);

		$num = $db->num_rows($res);
		$db->free_result($res);

		$pages_num = ceil($num/15);

		$res = $db->query('
			SELECT p.*, f.forum_id, f.forum_name, t.topic_title, u.username, u.user_id, u.user_rank, u.user_level, u.user_avatar, u.user_posts
			FROM ' . POSTS_TABLE . ' p
				LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = p.user_id 
				LEFT JOIN ' . TOPICS_TABLE . ' t ON t.topic_id = p.topic_id 
				LEFT JOIN ' . FORUMS_TABLE . ' f ON f.forum_id = p.forum_id AND ' . $user_level . " >= f.forum_level
			WHERE 	CONCAT(t.topic_title, p.post_text) LIKE '%" . $db->chars($query) . "%' 
				" . $sql . '
			ORDER BY p.post_id DESC
			LIMIT ' . ($page * 15 - 15) . ', 15
		');

		while ($row = $db->fetch_array($res)) {
			$pos = stripos($row['post_text'], $query, 1);

			template::assignBlock('posts', array(
				'ID'			=>	$row['post_id'],
				'TEXT'			=>	str_ireplace($query, '<span class="result">' . $query . '</span>', (($pos > 100) ? '...' : '') . replace(substr($row['post_text'], (($pos - 100) < 0) ? 0 : $pos - 100, $pos + 100) . (($pos+100 < strlen($row['post_text'])) ? '...' : ''), $row['enable_bbcodes'], $row['enable_smilies'], 1)),
				'TIME'			=>	date('d.m.y H:i', $row['post_time']),
				'TOPIC_ID'		=>	$row['topic_id'],
				'TOPIC_TITLE'		=>	str_ireplace($query, '<span class="result">' . $query . '</span>', htmlspecialchars($row['topic_title'])),
				'USER_ID'		=>	$row['user_id'],
				'USERNAME'		=>	$row['username'],
				'USER_AVATAR'		=>	($row['user_avatar']) ? $row['user_avatar'] : $config['default_avatar'],
				'USER_LEGEND'		=>	$user->legend($row['user_level']),
				'USER_RANK'		=>	$user->rank($row['user_id'], $row['user_rank'], $row['user_posts']),
				'USER_RANK_ICON'	=>	$user->rank_icon($row['user_id'], $row['user_rank'], $row['user_posts'])
			));
		}

		$db->free_result($res);

		template::assign(array(
			'TITLE_TAG'	=>	'Suchen | ',
			'NUM'		=>	number_format($num, 0, '', '.'),
			'AVATAR'	=>	$config['default_avatar'],
			'PAGE'		=>	$page,
			'PAGES_NUM'	=>	$pages_num,
			'PAGES'		=>	($pages_num > 1) ? pages($pages_num, $page, 'search.php?query=' . htmlspecialchars($query) . '&forum=' . htmlspecialchars($forum) . '&user=' . htmlspecialchars($username) . '&group=' . htmlspecialchars($group) . '&page=') : ''
		));

		template::display('search_result');
	} else {
		$res = $db->query('

			SELECT forum_id, forum_name
			FROM ' . FORUMS_TABLE . '
			WHERE is_category = 0
			ORDER BY forum_order
		');

		while ($row = $db->fetch_array($res)) {
			template::assignBlock('forums', array(
				'ID'	=>	$row['forum_id'],
				'NAME'	=>	$row['forum_name']
			));
		}

		$db->free_result($res);

		template::assign('TITLE_TAG', 'Suchen | ');
		template::display('search');
	}
?>