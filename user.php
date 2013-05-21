<?php
	/**
	*
	* @package com.Itschi.user
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	$res = $db->query('
		SELECT *
		FROM ' . USERS_TABLE . '
		WHERE user_id = ' . (int)$_GET['id']
	);

	$row = $db->fetch_array($res);
	$db->free_result($res);

	if (!$row) {
		message_box('Das Mitglied existiert nicht.', 'forum.php', 'weiter zum Forum');
	}

	$error = '';
	$is_online = ($row['user_lastvisit'] > time() - 300);

	if ($row['user_ban']) {
		$res2 = $db->query('
			SELECT ban_id, ban_time, ban_reason
			FROM ' . BANLIST_TABLE . '
			WHERE user_id = ' . $row['user_id']
		);

		$row2 = $db->fetch_array($res2);

		if ($row2['ban_time'] < time() || !$row2) {
			$db->query('
				UPDATE ' . USERS_TABLE . '
				SET user_ban = 0
				WHERE user_id = ' . $row['user_id']
			);
			
			header("Location: ".$_SERVER['PHP_SELF'].'?id='.$row['user_id']);
		}
		
		$db->free_result($res2);
	}

	if ($is_online) {
		$online_time = (floor((time() - $row['user_login']) / 60));
	 } else if(empty($row['user_login'])) {
		$online_time = date('d.m.y H:i', $row['user_register']);
	 } else {
		$online_time = date('d.m.y H:i', $row['user_login']);
	}

	template::assign(array(
		'TITLE_TAG'	=>	$row['username'] . ' | ',
		'ID'		=>	$row['user_id'],
		'USER_USERNAME'	=>	$row['username'],
		'USER_USERSTATUS'	=>	$row['user_status'],
		'BAN'		=>	$row['user_ban'],
		'IS_ONLINE'	=>	$is_online,
		'ONLINE_TIME'	=>	$online_time,
		'ICQ'		=>	htmlspecialchars($row['user_icq']),
		'UEBER'		=>	htmlspecialchars($row['user_ueber']),
		'SKYPE'		=>	htmlspecialchars($row['user_skype']),
		'AVATAR'	=>	($row['user_avatar']) ? $row['user_avatar'] : $config['default_avatar'],
		'RANK'		=>	$user->rank($row['user_id'], $row['user_rank'], $row['user_posts']),
		'RANK_ICON'	=>	$user->rank_icon($row['user_id'], $row['user_rank'], $row['user_posts']),
		'WEBSITE'	=>	($row['user_website']) ? ((!preg_match('#^http(s|)://#i', $row['user_website'])) ? 'http://' : '') . htmlspecialchars($row['user_website']) : '',
		'POINTS'	=>	number_format($row['user_points'], 0, '', '.'),
		'POSTS'		=>	number_format($row['user_posts'], 0, '', '.'),
		'PRO'		=>	number_format(@round($row['user_posts'] / ($config['posts_num'] + $config['topics_num'])*100, 2), 1, ',', ''),
		'PRODAY'	=>	($config['posts_num'] && $row['user_posts']) ? number_format(@round($row['user_posts']/((time() - $row['user_register'])/86400), 2), 1, ',', '') : 0,
		'SIGNATUR'	=>	($row['user_signatur']) ? replace($row['user_signatur'], $row['user_signatur_bbcodes'], $row['user_signatur_smilies'], $row['user_signatur_urls']) : false,
		'REGISTER'	=>	date('d.m.Y', $row['user_register']),
		'LAST_VISIT'=>	date('d.m.Y H:i:s', $row['user_lastvisit'])
	));

	template::display('user');
?>
