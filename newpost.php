<?php
	/**
	*
	* @package com.Itschi.forum.newpost
	* @since 2007/05/25
	*
	*/

	require 'base.php';


	if (!$user->row) {
		login_box();
	}

	$error = '';
	$submit = (isset($_POST['submit'])) ? true : false;
	$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
	$edit = (isset($_GET['edit']) && $_GET['edit'] > 0) ? 1 : 0;
	$text = (isset($_POST['text'])) ? strip($_POST['text']) : '';
	$bbcodes = (isset($_POST['bbcodes'])) ? 1 : 0;
	$smilies = (isset($_POST['smilies'])) ? 1 : 0;
	$urls = (isset($_POST['urls'])) ? 1 : 0;
	$signatur = ($submit || isset($_POST['preview'])) ? isset($_POST['signatur']) : 1;
	$preview = (!empty($_POST['preview'])) ? replace(trim($text), !$bbcodes, !$smilies, !$urls) : '';

	if (!$edit) {
		$title_tag = 'Forum | Beitrag schreiben | ';

		$res = $db->query('
			SELECT t.*, f.*, fr.mark_time
			FROM ' . TOPICS_TABLE . ' t, ' . FORUMS_TABLE . ' f
				LEFT JOIN ' . FORUMS_TRACK_TABLE . ' fr ON fr.forum_id = f.forum_id AND fr.user_id = ' . $user->row['user_id'] . '
			WHERE t.topic_id = ' . $id . '
				AND f.forum_id = t.forum_id
		');

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row) {
			message_box('Das Thema existiert nicht', 'forum.php', 'zurück zum Forum');
		}

		if ($submit) {
			$res = $db->query('
				SELECT post_id
				FROM ' . POSTS_TABLE . '
				WHERE user_id = ' . $user->row['user_id'] . '
				AND post_time > ' . strtotime('-1 day')
			);

			$posts = $db->num_rows($res);
			$db->free_result($res);

			if (!$text) {
				$error = 1;
			} else if ($posts >= $config['posts_perday']) {
				$error = 2;
			} else if ($row['topic_closed']) {
				$error = 3;
			} else if ($row['forum_closed']) {
				$error = 4;
			} else if ($config['max_post_chars'] && strlen($text) > $config['max_post_chars']) {
				$error = 5;
			} else {
				$db->query('
					INSERT INTO ' . POSTS_TABLE . '
					(enable_bbcodes, enable_smilies, enable_urls, enable_signatur, user_id, forum_id, topic_id, post_time, post_text) VALUES 
					(' . (int)!$bbcodes . ', ' . (int)!$smilies . ', ' . (int)!$urls . ', ' . (int)$signatur . ', ' . $user->row['user_id'] . ', ' . $row['forum_id'] . ', ' . $row['topic_id'] . ', ' . time() . ", '" . $db->chars(trim($text)) . "')

				");

				$post_id = $db->insert_id();

				$db->query('
					UPDATE ' . TOPICS_TABLE . '
					SET	topic_last_post_user_id = ' . $user->row['user_id'] . ',
						topic_last_post_user_level = ' . $user->row['user_level'] . ",
						topic_last_post_username = '" . $user->row['username'] . "',
						topic_last_post_time = " . time() . ',
						topic_last_post_id = ' . $post_id . ',
						topic_posts = topic_posts + 1
					WHERE topic_id = ' . $row['topic_id']
				);

				$db->query('
					UPDATE ' . FORUMS_TABLE . '
					SET	forum_posts = forum_posts + 1,
						forum_last_post_id = ' . $post_id . ',
						forum_last_post_user_id = ' . $user->row['user_id'] . ",
						forum_last_post_username = '" . $user->row['username'] . "',
						forum_last_post_user_level = " . $user->row['user_level'] . ',
						forum_last_post_time = ' . time() . ',
						forum_last_post_topic_id = ' . $row['topic_id'] . '
					WHERE forum_id = ' . $row['forum_id']
				);

				$db->query('
					UPDATE ' . USERS_TABLE . '
					SET	user_posts = user_posts + 1,
						user_points = user_points + ' . $config['points_post'] . '
					WHERE user_id = ' . $user->row['user_id']
				);

				config_set_count('posts_num', +1);

				// include 'lib/functions/topic.php';

				functions::topic()->mark_topic($row['topic_id'], $row['forum_id'], time(), $row['mark_time'], time());

				header('Location: viewtopic.php?id=' . $row['topic_id'] . '&page=' . ceil(($row['topic_posts']+2)/$config['posts_perpage']) . '#' . $post_id);
			}
		}

		if (!empty($_GET['quote'])) {
			$res2 = $db->query('
				SELECT p.post_text, u.username
				FROM ' . POSTS_TABLE . ' p, ' . USERS_TABLE . ' u
				WHERE p.post_id = ' . (int)$_GET['quote'] . '
					AND u.user_id = p.user_id
			');

			$row2 = $db->fetch_array($res2);
			$db->free_result($res2);

			if ($row2) {
				$text = '[quote=' . $row2['username'] . ']' . $row2['post_text'] . '[/quote]';
			}
		}

		$res2 = $db->query('
			SELECT p.*, u.username, u.user_rank, u.user_level, u.user_posts, u.user_avatar
			FROM ' . POSTS_TABLE . ' p, ' . USERS_TABLE . ' u
			WHERE p.topic_id = ' . $row['topic_id'] . '
				AND u.user_id = p.user_id
			ORDER BY p.post_id DESC LIMIT 10
		');

		while ($row2 = $db->fetch_array($res2)) {
			template::assignBlock('posts', array(
				'ID'			=>	$row2['post_id'],
				'TIME'			=>	date('d.m.y H:i', $row2['post_time']),
				'TEXT'			=>	replace($row2['post_text'], $row2['enable_bbcodes'], $row2['enable_smilies'], $row2['enable_urls']),
				'USERNAME'		=>	$row2['username'],
				'USER_ID'		=>	$row2['user_id'],
				'USER_AVATAR'		=>	($row2['user_avatar']) ? $row2['user_avatar'] : $config['default_avatar'],
				'USER_LEGEND'		=>	$user->legend($row2['user_level']),
				'USER_RANK'		=>	$user->rank($row2['user_id'], $row2['user_rank'], $row2['user_posts']),
				'USER_RANK_ICON'	=>	$user->rank_icon($row2['user_id'], $row2['user_rank'], $row2['user_posts'])
			));
		}

		$db->free_result($res2);
	} else {
		$title_tag = 'Forum | Beitrag bearbeiten | ';

		$res = $db->query('

			SELECT t.*, p.*, f.*
			FROM ' . POSTS_TABLE . ' p, ' . FORUMS_TABLE . ' f, ' . TOPICS_TABLE . ' t
			WHERE p.post_id = ' . $id . '
				AND t.topic_id = p.topic_id
				AND f.forum_id = p.forum_id
		');

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (empty($row['post_id'])) {
			message_box('Der Beitrag existiert nicht', 'forum.php', 'zurück zum Forum');
		}

		if ($row['user_id'] != $user->row['user_id'] && $user->row['user_level'] != ADMIN && $user->row['user_level'] != MOD) {
			message_box('Du bist nicht berechtigt das Thema zu bearbeiten', 'forum.php', 'zurück zum Forum');
		}

		if (!$submit && !isset($_POST['preview'])) {
			$text =		$row['post_text'];
			$bbcodes =	!$row['enable_bbcodes'];
			$smilies =	!$row['enable_smilies'];
			$urls =		!$row['enable_urls'];
			$signatur =	$row['enable_signatur'];
		} else if ($submit) {
			if (!$text) {
				$error = 1;
			} else if ($config['max_post_chars'] && strlen($text) > $config['max_post_chars']) {
				$error = 5;
			} else {
				$db->query('

					UPDATE ' . POSTS_TABLE . '
					SET	enable_bbcodes = ' . (int)!$bbcodes . ',
						enable_smilies = ' . (int)!$smilies . ',
						enable_urls = ' . (int)!$urls . ',
						enable_signatur = ' . (int)$signatur . ",
						post_text = '" . $db->chars(trim($text)) . "',
						post_edit_username = '" . $user->row['username'] . "',
						post_edit_user_level = " . $user->row['user_level'] . ',
						post_edit_time = ' . time() . ',
						post_edit_user_id = ' . $user->row['user_id'] . '
					WHERE post_id = ' . $row['post_id']
				);

				header('Location: viewtopic.php?id=' . $row['topic_id'] . '&p=' . $row['post_id'] . '#' . $row['post_id']);
			}
		}
	}

	$smilies_gp = $cache->get('smilies_group');

	foreach ($smilies_gp as $row2) {
		template::assignBlock('smilies', array(
			'EMOTION'	=>	$row2['smilie_emotion'],
			'IMAGE'		=>	$row2['smilie_image']
		));
	}

	template::assign(array(
		'TITLE_TAG'	=>	$title_tag,
		'ERROR'		=>	$error,
		'EDIT'		=>	$edit,
		'ID'		=>	$id,
		'PREVIEW'	=>	$preview,
		'TEXT'		=>	htmlspecialchars(trim($text)),
		'TOPIC_ID'	=>	$row['topic_id'],
		'TOPIC_TITLE'	=>	htmlspecialchars($row['topic_title']),
		'FORUM_NAME'	=>	$row['forum_name'],
		'FORUM_ID'	=>	$row['forum_id'],
		'BBCODES'	=>	$bbcodes,
		'SMILIES'	=>	$smilies,
		'URLS'		=>	$urls,
		'SIGNATUR'	=>	$signatur,
		'DEFAULT_AVATAR'=>	$config['default_avatar'],
		'POSTS_PERDAY'	=>	$config['posts_perday'],
		'MAX_CHARS'	=>	$config['max_post_chars']
	));

	template::display('newpost');
?>