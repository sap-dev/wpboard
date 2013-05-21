<?php
	/**
	*
	* @package com.Itschi.forum.index
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	if ($user->row && isset($_GET['mark'])) {
		// include 'lib/functions/topic.php';

		functions::topic()->mark_forum();
	}

	if ($config['enable_bots']) {
		$bots = $cache->get('bots');
	}

	$online = array();

	$res = $db->query('

		SELECT u.username, u.user_id, u.user_level, o.online_agent
		FROM ' . ONLINE_TABLE . ' o
			LEFT JOIN ' . USERS_TABLE . ' u ON o.user_id > 0 AND u.user_id = o.user_id
		WHERE o.online_lastvisit > ' . (time() - 300)
	);

	while ($row = $db->fetch_array($res)) {
		if ($row['user_id']) {
			$online[strtolower($row['username'])] = array(
				'IS_BOT'	=>	false,
				'LEGEND'	=>	$user->legend($row['user_level']),
				'USERNAME'	=>	$row['username'],
				'ID'		=>	$row['user_id']
			);
		} else if ($config['enable_bots']) {
			foreach ($bots as $bot) {
				if (preg_match('/' . preg_quote($bot['bot_agent'], '/') . '/', $row['online_agent'])) {
					$online[strtolower($bot['bot_name'])] = array(
						'IS_BOT'	=>	true,
						'BOT_NAME'	=>	$bot['bot_name']
					);

					break;
				}
			}
		}
	}

	$db->free_result($res);

	ksort($online);

	$s = '';
	foreach ($online as $row) {
		$row['SEPARATOR'] = $s;

		template::assignBlock('online', $row);

		$s = ', ';
	}

	$res = $db->query(
		($user->row) ? '
			SELECT f.*, t.mark_time
			FROM ' . FORUMS_TABLE . ' f
				LEFT JOIN ' . FORUMS_TRACK_TABLE . ' t ON t.forum_id = f.forum_id AND t.user_id = ' . $user->row['user_id'] . '
			WHERE ' . ($user->row['user_level'] + 1) . ' >= f.forum_level AND forum_toplevel = 0
			ORDER BY f.forum_order
		' : '
			SELECT *
			FROM ' . FORUMS_TABLE . '
			WHERE forum_level = 0 AND forum_toplevel = 0
			ORDER BY forum_order
	');

	while ($row = $db->fetch_array($res)) {
		$forum = array(
			'NAME'		=>	$row['forum_name'],
			'ID'		=>	$row['forum_id'],
			'IS_CATEGORY'	=>	$row['is_category']
		);

		$subforums = array();
		$fRes = $db->query("SELECT * FROM " . FORUMS_TABLE . " WHERE forum_toplevel = '" . $row['forum_id'] . "'");
		while ($fRow = $db->fetch_array($fRes)) {
			$subforums[] = $fRow;
		}

		$tRes = $db->query("SELECT * FROM " . TOPICS_TABLE . " WHERE topic_id = '" . $row['forum_last_post_topic_id'] . "'");
		$tRes2 = $db->fetch_array($tRes);

		$uRes = $db->query("SELECT * FROM " . USERS_TABLE . " WHERE user_id = '" . $row['forum_last_post_user_id'] . "'");
		$row2 = $db->fetch_array($uRes);
		
		$lRes = $db->query("SELECT * FROM " . LABEL_TABLE . " WHERE label_id = '" . $tRes2['topic_label'] . "'");
		$label = $db->fetch_array($lRes);
		
		$labela = '<div class="badge" style="background: '.$label['label_color'].'">'.$label['label_text'].'</div>';

		if (!$row['is_category']) {
			$forum = array_merge($forum, array(
				'ID'						=>	$row['forum_id'],
				'ICON'						=>	(($row['forum_closed']) ? 'closed' : '') . (($user->row['user_id'] && max($row['mark_time'], $user->row['user_register']) < $row['forum_last_post_time']) ? 'new' : '') . 'topic',
				'TOPICS'					=>	number_format($row['forum_topics'], 0, '', '.'),
				'POSTS'						=>	number_format($row['forum_posts'], 0, '', '.'),
				'DESCRIPTION'				=>	$row['forum_description'],
				'LAST_POST_TIME'			=>	date('d.m.y H:i', $row['forum_last_post_time']),
				'LAST_POST_USER_LEGEND'		=>	$user->legend($row['forum_last_post_user_level']),
				'LAST_POST_ID'				=>	$row['forum_last_post_id'],
				'LAST_POST_USER_ID'			=>	$row['forum_last_post_user_id'],
				'LAST_POST_USERNAME'		=>	$row['forum_last_post_username'],
				'LAST_POST_TOPIC_ID'		=>	$row['forum_last_post_topic_id'],
				'LABEL'						=>	$labela,
				'LEVEL'						=>	$row['forum_level'],
				'LABEL_EXIST'				=>	$tRes2['topic_label'],
				'USER_AVATAR'				=>	($row2['user_avatar']) ? $row2['user_avatar'] : $config['default_avatar'],
				'LAST_POST_TOPIC_TITLE'		=>	template::strShort($tRes2['topic_title'], 20),
				'READ'						=>	(($user->row['user_id'] && max($row['mark_time'], $user->row['user_register']) < $row['forum_last_post_time']) ? false : true),
				'SUBFORUMS'					=>	$subforums
			));
		}

		template::assignBlock('forums', $forum);
	}

	$db->free_result($res);

	template::assign(array(
		'TITLE_TAG'		=>	'Forum | ',
		'USERS'		=>	number_format($config['users_num'], 0, '', '.'),
		'TOPICS'		=>	number_format($config['topics_num'], 0, '', '.'),
		'POSTS'		=>	number_format($config['posts_num'], 0, '', '.'),
		'ONLINE_USER'		=>	$online,
		'NEWEST_USERNAME'	=>	$config['newest_username'],
		'NEWEST_USER_ID'	=>	$config['newest_user_id'],
		'NEWEST_USER_LEGEND'	=>	$user->legend($config['newest_user_level'])
	));

	template::display('forum');
?>