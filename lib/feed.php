<?php
	/**
	*
	* @package com.Itschi.base.feed
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib;

	function feed($limit) {
		global $db, $user, $tpl, $config, $phpdate;

		$i = 0;
		$more = false;
		$user_level = ($user->row) ? $user->row['user_level'] + 1 : 0;

		$res = $db->query('
			SELECT p.post_id, p.post_time, p.enable_bbcodes, p.enable_smilies, u.user_id, u.username, u.user_level, u.user_avatar, p.post_text, p.forum_id, f.forum_name, t.topic_id, t.topic_title
			FROM ' . POSTS_TABLE . ' p
			LEFT JOIN ' . TOPICS_TABLE . ' t ON t.topic_id = p.topic_id
			LEFT JOIN ' . FORUMS_TABLE . ' f ON f.forum_id = p.forum_id
			LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = p.user_id
			WHERE f.forum_level <= ' . $user_level . '
			ORDER BY p.post_id DESC LIMIT ' . ($limit + 1)
		);

		while ($row = $db->fetch_array($res)) {
			if ($i == $limit) {
				$more = true;
				continue;
			}
			
			$postTime = getTimeDifference($row['post_time'], time());
			
			\template::assignBlock('feed', array(
				'AVATAR'	=>	($row['user_avatar']) ? $row['user_avatar'] : $config['default_avatar'],
				'POST_ID'	=>	$row['post_id'],
				'TOPIC_ID'	=>	$row['topic_id'],
				'FORUM_NAME'	=>	$row['forum_name'],
				'FORUM_ID'		=>	$row['forum_id'],
				'USERNAME'	=>	$row['username'],
				'USER_ID'	=>	$row['user_id'],
				'USER_LEGEND'	=>	$user->legend($row['user_level']),
				'TOPIC_TITLE'	=>	htmlspecialchars(substr($row['topic_title'], 0, 30)) . ((strlen($row['topic_title']) > 30) ? '...' : ''),
				'POST_TEXT'	=>	replace(substr($row['post_text'], 0, 120), $row['enable_bbcodes'], $row['enable_smilies'], 1),
				'POST_TIME'	=>	$postTime,
				'MORE'		=>	(strlen($row['post_text']) > 120)
			));

			$i++;
		}

		$db->free_result($res);

		\template::assign(array(
			'MORE'	=>	$more,
			'LIMIT'	=>	$limit
		));
	}
?>