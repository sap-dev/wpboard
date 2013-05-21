<?php
	/**
	*
	* @package com.Itschi.forum.viewtopic
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	$last_time = 0;

	$res = $db->query(
		(($user->row) ? '
			SELECT t.*, f.*, tr.mark_time, fr.mark_time as forum_mark_time
			FROM ' . TOPICS_TABLE . ' t
				LEFT JOIN ' . TOPICS_TRACK_TABLE . ' tr ON tr.topic_id = t.topic_id AND tr.user_id = ' . $user->row['user_id'] . '
				LEFT JOIN ' . FORUMS_TRACK_TABLE . ' fr ON fr.forum_id = t.forum_id AND fr.user_id = ' . $user->row['user_id'] . '
		' : '
			SELECT t.*, f.*
			FROM ' . TOPICS_TABLE . ' t
		') . '
			LEFT JOIN ' . FORUMS_TABLE . ' f ON f.forum_id = t.forum_id
		WHERE t.topic_id = ' . (int)$_GET['id']
	);

	$row = $db->fetch_array($res);
	$db->free_result($res);

	$user_level = ($user->row) ? $user->row['user_level'] + 1 : 0;

	if (!$row) {
		message_box('Das Thema existiert nicht', 'forum.php', 'zurück zum Forum');
	} else if ($user_level < $row['forum_level']) {
		message_box('Du bist nicht berechtigt das Thema zu sehen', 'forum.php', 'zurück zum Forum');
	}

	if ($user->row) {
		if (isset($_GET['delete'])) {
			// include 'lib/functions/topic.php';

			functions::topic()->delete_topic_post($_GET['delete']);
		} else if (isset($_POST['option'])) {
			// include 'lib/functions/topic.php';

			functions::topic()->poll_vote($row, $_POST['option']);
		} else if (isset($_GET['close'])) {
			// include 'lib/functions/topic.php';

			functions::topic()->close_topic($row);
		} else if (isset($_GET['important'])) {
			// include 'lib/functions/topic.php';

			functions::topic()->important_topic($row);
		}
	}

	$track_post = '';

	function set_page($topic_id, $post_id) {
		global $db, $config;

		$res = $db->query('
			SELECT COUNT(*) FROM ' . POSTS_TABLE . '
			WHERE topic_id = ' . $topic_id . '
				AND post_id <= ' . (int)$post_id
		);

		$row = $db->result($res, 0);
		$db->free_result($res);

		$_GET['page'] = ceil($row/$config['posts_perpage']);
	}

	if (isset($_GET['p'])) {
		set_page($row['topic_id'], $_GET['p']);
	}

	if (isset($_GET['view'])) {
		$res2 = $db->query('
			SELECT post_id
			FROM ' . POSTS_TABLE . '
			WHERE topic_id = ' . $row['topic_id'] . '
				 AND post_time > ' . (int)max($row['mark_time'], $row['forum_mark_time'], $user->row['user_register']) . '
			ORDER BY post_time ASC LIMIT 1
		');

		$row2 = $db->fetch_array($res2);
		$db->free_result($res2);

		$track_post = $row2['post_id'];
		set_page($row['topic_id'], $row2['post_id']);
	}

	$db->query('
		UPDATE ' . TOPICS_TABLE . '
		SET topic_views = topic_views + 1
		WHERE topic_id = ' . $row['topic_id']
	);

	if ($row['poll_title']) {
		if ($user->row) {
			$res = $db->query('
				SELECT topic_id FROM ' . POLL_VOTES_TABLE . '
				WHERE topic_id = ' . $row['topic_id'] . '
					AND user_id = ' . $user->row['user_id']
			);

			$voted = $db->fetch_array($res);
			$db->free_result($res);
		} else {
			$voted = false;
		}

		$user_voted = (!$user->row || isset($_GET['result']) || ($row['poll_time'] < time() && $row['poll_time'] != 0) || $voted);

		$res2 = $db->query('

			SELECT option_id, option_text, option_votes
			FROM ' . POLL_OPTIONS_TABLE . '
			WHERE topic_id = ' . $row['topic_id'] . '
			ORDER BY option_id
		');

		while ($row2 = $db->fetch_array($res2)) {
			$pro = ($user_voted) ? (int)@round($row2['option_votes']/$row['poll_votes']*100, 0) : '';

			template::assignBlock('options', array(
				'ID'	=>	$row2['option_id'],
				'VOTES'	=>	$row2['option_votes'],
				'PRO'	=>	$pro,
				'TEXT'	=>	htmlspecialchars($row2['option_text']),
				'PIXEL'	=>	ceil($pro*2.5+7)
			));
		}

		$db->free_result($res2);

		template::assign(array(
			'POLL_TITLE'	=>	htmlspecialchars($row['poll_title']),
			'POLL_VOTES'	=>	$row['poll_votes'],
			'USER_VOTED'	=>	$user_voted
		));
	}

	$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;
	$pages_num = ceil(($row['topic_posts']+1)/$config['posts_perpage']);

	$res2 = $db->query('
		SELECT p.*, u.user_website, u.user_icq, u.user_skype, u.user_register, u.user_id, u.username, u.user_rank, u.user_level, u.user_posts, u.user_avatar, u.user_signatur, u.user_signatur_bbcodes, u.user_signatur_smilies, u.user_login, u.user_lastvisit,u.user_signatur_urls
		FROM ' . POSTS_TABLE . ' p
			LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = p.user_id
		WHERE p.topic_id = ' . $row['topic_id'] . '
		ORDER BY p.post_id ASC
		LIMIT ' . ($page * $config['posts_perpage'] - $config['posts_perpage']) . ', ' . $config['posts_perpage']
	);

	$i = 0;
	$post_num = 0;
	while ($row2 = $db->fetch_array($res2)) {
		$last_time = $row2['post_time'];
		++$i;

		$is_online = ($row2['user_lastvisit'] > time() - 300);

	
	if ($is_online) {
		$online_time = (floor((time() - $row2['user_login']) / 60));
	 } else if(empty($row['user_login'])) {
		$online_time = date('d.m.y H:i', $row2['user_register']);
	 } else {
		$online_time = date('d.m.y H:i', $row2['user_login']);
	}


		$post_num++;
		template::assignBlock('posts', array(
			'ID'			=>	$row2['post_id'],
			'TIME'			=>	date('d.m.y H:i', $row2['post_time']),
			'USER_REGISTER'	=>	date('d.m.y H:i', $row2['user_register']),
			'TEXT'			=>	replace($row2['post_text'], $row2['enable_bbcodes'], $row2['enable_smilies'], $row2['enable_urls']),
			'TRACK'			=>	($track_post == $row2['post_id']) ? 'post' : $row2['post_id'],
			'IS_TOPIC'		=>	$row2['is_topic'],
			'IS_ONLINE'		=>	$is_online,
			'POSTS_NUM'		=>	$post_num,
			'USER_WEBSITE'	=>	$row2['user_website'],
			'USER_ICQ'		=>	$row2['user_icq'],
			'USER_SKYPE'	=>	$row2['user_skype'],
			'EDIT_USER_ID'		=>	$row2['post_edit_user_id'],
			'EDIT_USERNAME'		=>	$row2['post_edit_username'],
			'EDIT_TIME'		=>	($row2['post_edit_user_id']) ? date('d.m.y H:i', $row2['post_edit_time']) : '',
			'EDIT_USER_LEGEND'	=>	$user->legend($row2['post_edit_user_level']),
			'USERNAME'		=>	$row2['username'],
			'USER_ID'		=>	$row2['user_id'],
			'USER_POSTS'		=>	number_format($row2['user_posts'], 0, '', '.'),
			'USER_AVATAR'		=>	($row2['user_avatar']) ? $row2['user_avatar'] : $config['default_avatar'],
			'USER_LEGEND'		=>	$user->legend($row2['user_level']),
			'USER_RANK'		=>	$user->rank($row2['user_id'], $row2['user_rank'], $row2['user_posts']),
			'USER_RANK_ICON'	=>	$user->rank_icon($row2['user_id'], $row2['user_rank'], $row2['user_posts']),
			'USER_SIGNATUR'		=>	($row2['enable_signatur'] && $row2['user_signatur']) ? replace($row2['user_signatur'], $row2['user_signatur_bbcodes'], $row2['user_signatur_smilies'], $row2['user_signatur_urls']) : false,
			'FIRST_POST'	=>	($i == 1)
		));
	}

	$db->free_result($res2);

	if ($user->row && max($user->row['user_register'], $row['forum_mark_time'], $row['mark_time']) < $last_time) {
		// include 'lib/functions/topic.php';

		functions::topic()->mark_topic($row['topic_id'], $row['forum_id'], $row['forum_last_post_time'], $row['forum_mark_time'], $last_time);
	}
	
	$lRes = $db->query("SELECT * FROM " . LABEL_TABLE . " WHERE label_id = '" . $row['topic_label'] . "'");
	$label = $db->fetch_array($lRes);
	$labela = '<div class="badge" style="background: '.$label['label_color'].'">'.$label['label_text'].'</div>';

	template::assign(array(
		'TITLE_TAG'			=>	'Forum | ' . htmlspecialchars($row['topic_title']) . ' | ',
		'IS_MOD'			=>	($user->row['user_level'] == ADMIN || $user->row['user_level'] == MOD),
		'FORUM_ID'			=>	$row['forum_id'],
		'FORUM_NAME'		=>	$row['forum_name'],
		'LABEL'				=>	$labela,
		'LABEL_EXIST'		=>	$row['topic_label'],
		'FORUM_CLOSED'		=>	$row['forum_closed'],
		'TOPIC_ID'			=>	$row['topic_id'],
		'TOPIC_TITLE'		=>	htmlspecialchars($row['topic_title']),
		'TOPIC_POSTS'		=>	number_format($row['topic_posts'], 0, '', '.'),
		'TOPIC_CLOSED'		=>	$row['topic_closed'],
		'TOPIC_IMPORTANT' 	=>	$row['topic_important'],
		'AVATAR'			=>	$config['default_avatar'],
		'PAGE'				=>	$page,
		'PAGES_NUM'			=>	$pages_num,
		'PAGES'				=>	($pages_num > 1) ? pages($pages_num, $page, 'viewtopic.php?id=' . $row['topic_id'] . '&page=') : ''
	));

	template::display('viewtopic');
?>