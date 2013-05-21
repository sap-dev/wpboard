<?php
	/**
	*
	* @package com.Itschi.movetopic
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	if (!$user->row) {
		login_box();
		exit;
	}

	if ($user->row['user_level'] != ADMIN && $user->row['user_level'] != MOD) {
		message_box('Du bist dazu nicht berechtigt', '/forum/', 'zurück zum Forum');
	}

	$forum_id = (isset($_POST['forum_id'])) ? (int)$_POST['forum_id'] : 0;

	if ($forum_id) {
		foreach ($_POST as $key => $value) {
			if ($value == 'true') {
				$res = $db->query('
					SELECT topic_id, forum_id, topic_posts
					FROM ' . TOPICS_TABLE . '
					WHERE topic_id = ' . (int)$key
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row['topic_id']) {
					$db->query('
						UPDATE ' . TOPICS_TABLE . '
						SET forum_id = ' . $forum_id . '
						WHERE topic_id = ' . $row['topic_id']
					);

					$db->query('
						UPDATE ' . POSTS_TABLE . '
						SET forum_id = ' . $forum_id . '
						WHERE topic_id = ' . $row['topic_id']
					);

					$res2 = $db->query('
						SELECT topic_id, topic_last_post_user_id, topic_last_post_username, topic_last_post_user_level, topic_last_post_time, topic_last_post_id
						FROM ' . TOPICS_TABLE . '
						WHERE forum_id = ' . $row['forum_id'] . '
						ORDER BY topic_last_post_time DESC
					');

					$row2 = $db->fetch_array($res2);
					$db->free_result($res2);

					$prevForumTime = $row2['topic_last_post_time'];

					$db->query('
						UPDATE ' . FORUMS_TABLE . '
						SET	forum_topics = forum_topics - 1,
							forum_posts = forum_posts - ' . $row['topic_posts'] . ',
							forum_last_post_id = ' . $row2['topic_last_post_id'] . ',
							forum_last_post_user_id = ' . $row2['topic_last_post_user_id'] . ",
							forum_last_post_username = '" . $row2['topic_last_post_username'] . "',
							forum_last_post_user_level = " . $row2['topic_last_post_user_level'] . ',
							forum_last_post_time = ' . $row2['topic_last_post_time'] . ',
							forum_last_post_topic_id = ' . $row2['topic_id'] . '
						WHERE forum_id = ' . $row['forum_id']
					);

					$res2 = $db->query('
						SELECT forum_id, topic_id, topic_last_post_user_id, topic_last_post_username, topic_last_post_user_level, topic_last_post_time, topic_last_post_id, topic_posts
						FROM ' . TOPICS_TABLE . '
						WHERE forum_id = ' . $forum_id . '
						ORDER BY topic_last_post_time DESC
					');

					$row2 = $db->fetch_array($res2);
					$db->free_result($res2);

					$db->query('
						UPDATE ' . FORUMS_TABLE . '
						SET	forum_topics = forum_topics + 1,
							forum_posts = forum_posts + ' . $row['topic_posts'] . ',
							forum_last_post_id = ' . $row2['topic_last_post_id'] . ',
							forum_last_post_user_id = ' . $row2['topic_last_post_user_id'] . ",
							forum_last_post_username = '" . $row2['topic_last_post_username'] . "',
							forum_last_post_user_level = " . $row2['topic_last_post_user_level'] . ',
							forum_last_post_time = ' . $row2['topic_last_post_time'] . ',
							forum_last_post_topic_id = ' . $row2['topic_id'] . '
						WHERE forum_id = ' . $row2['forum_id']
					);

					$db->query('
						UPDATE ' . TOPICS_TRACK_TABLE . '
						SET forum_id = ' . $forum_id . '
						WHERE topic_id = ' . $row['topic_id']
					);

					$db->query('
						UPDATE ' . FORUMS_TRACK_TABLE . '
						SET mark_time = ' . $row2['topic_last_post_time'] . '
						WHERE forum_id = ' . $row2['forum_id'] . '
							AND mark_time < ' . $row2['topic_last_post_time'] . '
							AND mark_time >= ' . $prevForumTime
					);
				}
			}
		}

		message_box('Die Themen wurden verschoben', 'viewforum.php?id=' . $forum_id, 'weiter zum Forum', '', '', 3);
		exit;
	}

	$res = $db->query('
		SELECT t.*, f.*
		FROM ' . TOPICS_TABLE . ' t
			LEFT JOIN ' . FORUMS_TABLE . ' f ON f.forum_id = t.forum_id
		WHERE t.topic_id = ' . (int)$_GET['id']
	);

	$row = $db->fetch_array($res);
	$db->free_result($res);


	$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;
	$pages_num = ceil($row['forum_topics']/20);

	$res2 = $db->query('
		SELECT *
		FROM ' . TOPICS_TABLE . '
		WHERE forum_id = ' . $row['forum_id'] . '
		ORDER BY topic_important DESC, topic_last_post_time DESC
		LIMIT ' . ($page * 20 - 20) . ', 20'
	);

	while ($row2 = $db->fetch_array($res2)) {
		template::assignBlock('topics', array(
			'ID'			=>	$row2['topic_id'],
			'TIME'			=>	date('d.m.y H:i', $row2['topic_time']),
			'TITLE'			=>	htmlspecialchars($row2['topic_title']),
			'POSTS'			=>	number_format($row2['topic_posts'], 0, '', '.'),
			'VIEWS'			=>	number_format($row2['topic_views'], 0, '', '.'),
			'LAST_POST_ID'		=>	$row2['topic_last_post_id'],
			'LAST_POST_TIME'	=>	date('d.m.y H:i', $row2['topic_last_post_time']),
			'LAST_POST_USERNAME'	=>	$row2['topic_last_post_username'],
			'LAST_POST_USER_ID'	=>	$row2['topic_last_post_user_id'],
			'LAST_POST_USER_LEGEND'	=>	$user->legend($row2['topic_last_post_user_level']),
			'USERNAME'		=>	$row2['username'],
			'USER_ID'		=>	$row2['user_id'],
			'USER_LEGEND'		=>	$user->legend($row2['user_level'])
		));
	}

	$db->free_result($res2);

	$res2 = $db->query('
		SELECT forum_id, forum_name
		FROM ' . FORUMS_TABLE . '
		WHERE is_category = 0
		ORDER BY forum_order ASC
	');

	while ($row2 = $db->fetch_array($res2)) {
		template::assignBlock('forums', array(
			'ID'	=>	$row2['forum_id'],
			'NAME'	=>	$row2['forum_name']
		));
	}

	$db->free_result($res2);

	template::assign(array(
		'TITLE_TAG'	=>	'Forum | Thema verschieben | ',
		'TOPIC_ID'	=>	$row['topic_id'],
		'FORUM_ID'	=>	$row['forum_id'],
		'FORUM_NAME'	=>	$row['forum_name'],
		'FORUM_TOPICS'	=>	number_format($row['forum_topics'], 0, '', '.'),
		'PAGES_NUM'	=>	$pages_num,
		'PAGE'		=>	$page,
		'PAGES'		=>	($pages_num > 1) ? pages($pages_num, $page, 'movetopic.php?id=' . $row['topic_id'] . '&page=') : ''
	));

	template::display('movetopic');
?>