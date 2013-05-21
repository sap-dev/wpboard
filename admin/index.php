<?php
	/**
	 *	@package	com.Itschi.ACP.index
	 */

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	if (isset($_POST['user'])) {
		$res = $db->query('
			SELECT user_id
			FROM ' . USERS_TABLE . "
			WHERE username = '" . $db->chars($_POST['user']) . "'
		");

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if ($row) {
			header('Location: user.php?id=' . $row['user_id']);

			exit;
		}
	}

	$sync = (isset($_GET['sync'])) ? $_GET['sync'] : -1;

	function sync($id) {
		global $db;

		switch ($id) {
			case 1:
				$res = $db->query('
					SELECT COUNT(*)
					FROM ' . USERS_TABLE . "
					WHERE user_unlock = ''
				");

				config_set('users_num', $db->result($res, 0));
				$db->free_result($res);

				$res = $db->query('
					SELECT COUNT(*)
					FROM ' . POSTS_TABLE . '
					WHERE is_topic = 0
				');

				config_set('posts_num', $db->result($res, 0));
				$db->free_result($res);

				$res = $db->query('
					SELECT COUNT(*)
					FROM ' . TOPICS_TABLE . '
				');

				config_set('topics_num', $db->result($res, 0));
				$db->free_result($res);

				$res = $db->query('
					SELECT user_id, username, user_level
					FROM ' . USERS_TABLE . "
						WHERE user_unlock = ''
					ORDER BY user_id DESC LIMIT 1
				");

				$row = $db->fetch_array($res);
				$db->free_result($res);

				config_set('newest_user_id', $row['user_id']);
				config_set('newest_username', $row['username']);
				config_set('newest_user_level', $row['user_level']);

				$res = $db->query('
					SELECT user_id
					FROM ' . USERS_TABLE
				);

				while ($row = $db->fetch_array($res)) {
					$res2 = $db->query('
						SELECT COUNT(*)
						FROM ' . POSTS_TABLE . '
						WHERE user_id = ' . $row['user_id']
					);

					$row2 = $db->result($res2, 0);
					$db->free_result($res2);

					$db->query('
						UPDATE ' . USERS_TABLE . '
						SET user_posts = ' . $row2 . '
						WHERE user_id = ' . $row['user_id']
					);
				}

				$db->free_result($res);
			break;

			case 2:
				$res = $db->query('
					SELECT topic_id
					FROM ' . TOPICS_TABLE
				);

				while ($row = $db->fetch_array($res)) {
					$res2 = $db->query('
						SELECT p.post_id, p.user_id, p.post_time, u.username, u.user_level
						FROM ' . POSTS_TABLE . ' p
							LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = p.user_id
						WHERE p.topic_id = ' . $row['topic_id'] . '
						ORDER BY p.post_id DESC
					');

					$row2 = $db->fetch_array($res2);
					$db->free_result($res2);

					$res3 = $db->query('
						SELECT COUNT(*)
						FROM ' . POSTS_TABLE . '
						WHERE topic_id = ' . $row['topic_id']
					);

					$row3 = $db->result($res3, 0);
					$db->free_result($res3);

					$db->query('
						UPDATE ' . TOPICS_TABLE . '
						SET	topic_posts = ' . ($row3 - 1) . ',
							topic_last_post_id = ' . $row2['post_id'] . ",
							topic_last_post_username = '" . $row2['username'] . "',
							topic_last_post_user_level = " . (int)$row2['user_level'] . ',
							topic_last_post_user_id = ' . (int)$row2['user_id'] . ',
							topic_last_post_time = ' . $row2['post_time'] . '
						WHERE topic_id = ' . $row['topic_id']
					);
				}

				$db->free_result($res);
			break;

			case 3:
				$res = $db->query('
					SELECT forum_id
					FROM ' . FORUMS_TABLE . '
					WHERE is_category = 0
				');

				while ($row = $db->fetch_array($res)) {
					$res2 = $db->query('
						SELECT topic_id, topic_last_post_user_id, topic_last_post_username, topic_last_post_user_level, topic_last_post_time, topic_last_post_id, topic_posts
						FROM ' . TOPICS_TABLE . '
						WHERE forum_id = ' . $row['forum_id'] . '
						ORDER BY topic_last_post_time DESC
					');

					$row2 = $db->fetch_array($res2);
					$db->free_result($res2);

					$res3 = $db->query('
						SELECT COUNT(*) as topics, SUM(topic_posts) as posts
						FROM ' . TOPICS_TABLE . '
						WHERE forum_id = ' . $row['forum_id']
					);

					$row3 = $db->fetch_array($res3);
					$db->free_result($res3);

					$db->query('
						UPDATE ' . FORUMS_TABLE . '
						SET	forum_posts = ' . (int)$row3['posts'] . ',
							forum_topics = ' . (int)$row3['topics'] . ",
							forum_last_post_username = '" . $row2['topic_last_post_username'] . "',
							forum_last_post_user_level = " . (int)$row2['topic_last_post_user_level'] . ',
							forum_last_post_id = ' . (int)$row2['topic_last_post_id'] . ',
							forum_last_post_user_id = ' . (int)$row2['topic_last_post_user_id'] . ',
							forum_last_post_time = ' . (int)$row2['topic_last_post_time'] . ',
							forum_last_post_topic_id = ' . (int)$row2['topic_id'] . '
						WHERE forum_id = ' . $row['forum_id']
					);
				}

				$db->free_result($res);
			break;

			case 4:
				$path = '../lib/cache/';

				if ($handle = @opendir($path)) {
					while ($file = @readdir($handle)) {
						if ($file != '.' && $file != '..' && !is_dir($path . $file)) {
							@unlink($path . $file);
						}
					}

					closedir($handle);
				}

			break;
		}
	}

	if ($sync == 0) {
		sync(1);
		sync(2);
		sync(3);
		sync(4);
	} else {
		sync($sync);
	}

	if ($sync >= 0) {
		$config = config_vars();
	}

	$res = $db->query('
		SELECT COUNT(*)
		FROM ' . FORUMS_TABLE . '
	');

	$forums_num = $db->result($res, 0);
	$db->free_result($res);

	template::assign(array(
		'MENU_BUTTON'		=>	1,
		'ERROR'			=>	($_POST) ? 1 : 0,
		'FORUMS_NUM'		=>	$forums_num,
		'TOPICS_NUM'		=>	number_format($config['topics_num'], 0, '', '.'),
		'POSTS_NUM'		=>	number_format($config['posts_num'], 0, '', '.'),
		'USERS_NUM'		=>	number_format($config['users_num'], 0, '', '.'),
		'NEWEST_USERNAME'	=>	$config['newest_username'],
		'NEWEST_USER_ID'	=>	$config['newest_user_id'],
		'NEWEST_USER_LEVEL'	=>	$user->legend($config['newest_user_level']),
		'SYNC'			=>	$sync,
		'VERSION'		=>	VERSION
	));

	template::display('index', true);
?>