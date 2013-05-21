<?php
	/**
	*
	* @package com.Itschi.forum.newtopic
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
	$title = (isset($_POST['title'])) ? strip($_POST['title']) : '';
	$text = (isset($_POST['text'])) ? strip($_POST['text']) : '';
	$bbcodes = (isset($_POST['bbcodes'])) ? 1 : 0;
	$smilies = (isset($_POST['smilies'])) ? 1 : 0;
	$urls = (isset($_POST['urls'])) ? 1 : 0;
	$signatur = ($submit || isset($_POST['preview'])) ? isset($_POST['signatur']) : 1;
	$poll_title = (isset($_POST['poll_title'])) ? strip($_POST['poll_title']) : '';
	$poll_options = (isset($_POST['poll_options'])) ? strip($_POST['poll_options']) : '';
	$poll_days = (isset($_POST['poll_days'])) ? (int)$_POST['poll_days'] : 0;
	$preview = (!empty($_POST['preview'])) ? replace(trim($text), !$bbcodes, !$smilies, !$urls) : '';


	if (!$edit) {
		$title_tag = 'Forum | Neues Thema | ';

		$res = $db->query('
			SELECT f.*, fr.mark_time
			FROM ' . FORUMS_TABLE . ' f
				LEFT JOIN ' . FORUMS_TRACK_TABLE . ' fr ON fr.forum_id = f.forum_id AND fr.user_id = ' . $user->row['user_id'] . '
			WHERE f.forum_id = ' . $id
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row || $row['is_category']) {
			message_box('Die Kategorie existiert nicht', 'forum.php', 'zurück zum Forum');
		}

		if ($submit) {
			$res2 = $db->query('
				SELECT post_id
				FROM ' . POSTS_TABLE . '
				WHERE user_id = ' . $user->row['user_id'] . '
					AND post_time > ' . strtotime('-1 day')
			);

			$posts = $db->num_rows($res2);
			$db->free_result($res2);

			$poll = explode("\n", preg_replace("/(\r\n)+|(\n|\r)+/", "\n", trim($poll_options)));
			$poll_num = count($poll);

			if (!$title) {
				$error = 4;
			} else if (!$text) {
				$error = 1;
			} else if ($posts >= $config['posts_perday']) {
				$error = 2;
			} else if ($row['forum_closed']) {
				$error = 5;
			} else if ($poll_title && $poll_num < 2) {
				$error = 6;
			} else if ($poll_title && $poll_num > 30) {
				$error = 7;
			} else if ($config['max_post_chars'] && strlen($text) > $config['max_post_chars']) {
				$error = 8;
			} else {
				$db->query('
					INSERT INTO ' . TOPICS_TABLE . '
					(forum_id, topic_title, topic_time, user_id, topic_last_post_time, topic_last_post_user_id, topic_last_post_user_level, user_level, username, topic_last_post_username, poll_title, poll_time) VALUES 
					(' . $row['forum_id'] . ", '" . $db->chars(trim($title)) . "', " . time() . ', ' . $user->row['user_id'] . ', ' . time() . ', ' . $user->row['user_id'] . ', ' . $user->row['user_level'] . ', ' . $user->row['user_level'] . ", '" . $user->row['username'] . "', '" . $user->row['username'] . "', '" . $db->chars(trim($poll_title)) . "', " . ($poll_days == 0 ? 0 : (time() + 24*3600*$poll_days)) . ')
				');

				$topic_id = $db->insert_id();

				if ($poll_title) {
					foreach ($poll as $value) {
						$db->query('
							INSERT INTO ' . POLL_OPTIONS_TABLE . '
							(topic_id, option_text) VALUES
							(' . $topic_id . ", '" . $db->chars($value) . "')
						");
					}
				}

				$db->query('
					INSERT INTO ' . POSTS_TABLE . '
					(enable_bbcodes, enable_smilies, enable_urls, enable_signatur, post_text, user_id, forum_id, topic_id, post_time, is_topic) VALUES 
					(' . (int)!$bbcodes . ', ' . (int)!$smilies . ', ' . (int)!$urls . ', ' . (int)$signatur . ", '" . $db->chars(trim($text)) . "', " . $user->row['user_id'] . ', ' . $row['forum_id'] . ', ' . $topic_id . ', ' . time() . ', 1)
				');

				$post_id = $db->insert_id();

				$db->query('
					UPDATE ' . TOPICS_TABLE . '
					SET topic_last_post_id = ' . $post_id . '
					WHERE topic_id = ' . $topic_id
				);

				$db->query('
					UPDATE ' . FORUMS_TABLE . '
					SET	forum_topics = forum_topics + 1,
						forum_last_post_id = ' . $post_id . ',
						forum_last_post_user_id = ' . $user->row['user_id'] . ",
						forum_last_post_username = '" . $user->row['username'] . "',
						forum_last_post_user_level = " . $user->row['user_level'] . ',
						forum_last_post_time = ' . time() . ',
						forum_last_post_topic_id = ' . $topic_id . '
					WHERE forum_id = ' . $row['forum_id']
				);

				$db->query('
					UPDATE ' . USERS_TABLE . '
					SET	user_posts = user_posts + 1,
						user_points = user_points + ' . $config['points_topic'] . '
					WHERE user_id = ' . $user->row['user_id']
				);

				config_set_count('topics_num', +1);

				// include 'lib/functions/topic.php';

				functions::topic()->mark_topic($topic_id, $row['forum_id'], time(), $row['mark_time'], time());

				header('Location: viewtopic.php?id=' . $topic_id);
			}
		}
	} else {
		$title_tag = 'Forum | Thema bearbeiten | ';

		$res = $db->query('
			SELECT t.*, f.*, p.*
			FROM ' . TOPICS_TABLE . ' t, ' . POSTS_TABLE . ' p, ' . FORUMS_TABLE . ' f
			WHERE t.topic_id = ' . $id . '
				AND p.topic_id = t.topic_id
				AND p.is_topic = 1
				AND f.forum_id = t.forum_id
		');

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row) {
			message_box('Das Thema existiert nicht', 'forum.php', 'zurück zum Forum');
		}

		if ($row['user_id'] != $user->row['user_id'] && $user->row['user_level'] != ADMIN && $user->row['user_level'] != MOD) {
			message_box('Du bist nicht berechtigt das Thema zu bearbeiten', 'forum.php', 'zurück zum Forum');
		}

		if (!$submit && !isset($_POST['preview'])) {
			$poll_options = array();

			$res2 = $db->query('
				SELECT option_text
				FROM ' . POLL_OPTIONS_TABLE . '
				WHERE topic_id = ' . $row['topic_id'] . '
				ORDER BY option_id

			');

			while ($row2 = $db->fetch_array($res2)) {
				$poll_options[] = $row2['option_text'];
			}

			$db->free_result($res2);

			$poll_options =	implode("\n", $poll_options);
			$poll_title = $row['poll_title'];
			$poll_days = ($row['poll_time'] == 0) ? 0 : round(($row['poll_time'] - time()) / (24*3600));
			$title = $row['topic_title'];
			$text = $row['post_text'];
			$bbcodes = !$row['enable_bbcodes'];
			$smilies = !$row['enable_smilies'];
			$urls =	!$row['enable_urls'];
			$signatur =	$row['enable_signatur'];
			$topic_title = htmlspecialchars($row['topic_title']);
		} else if ($submit) {
			$poll = explode("\n", preg_replace("/(\r\n)+|(\n|\r)+/", "\n", trim($poll_options)));
			$poll_num = count($poll);

			if (!$title) {
				$error = 4;
			} else if (!$text) {
				$error = 1;
			} else if ($poll_title && $poll_num < 2) {
				$error = 6;
			} else if ($poll_title && $poll_num > 30) {
				$error = 7;
			} else if ($config['max_post_chars'] && strlen($text) > $config['max_post_chars']) {
				$error = 8;
			} else {
				if ($poll_title) {
					$res2 = $db->query('
						SELECT option_id, option_text
						FROM ' . POLL_OPTIONS_TABLE . '
						WHERE topic_id = ' . $row['topic_id'] . '
						ORDER BY option_id
					');

					$poll_old_num = $db->num_rows($res2);

					if ($poll_num == $poll_old_num) {
						$i = 0;

						while ($row2 = $db->fetch_array($res2)) {
							$db->query('
								UPDATE ' . POLL_OPTIONS_TABLE . "
								SET option_text = '" . $db->chars($poll[$i]) . "'
								WHERE option_id = " . $row2['option_id']
							);

							$i++;
						}
					} else {
						$db->query('
							DELETE FROM ' . POLL_VOTES_TABLE . '
							WHERE topic_id = ' . $row['topic_id']
						);

						$db->query('
							DELETE FROM ' . POLL_OPTIONS_TABLE . '
							WHERE topic_id = ' . $row['topic_id']
						);

						foreach ($poll as $value) {
							$db->query('
								INSERT INTO ' . POLL_OPTIONS_TABLE . '
								(topic_id, option_text) VALUES
								(' . $row['topic_id'] . ", '" . $db->chars($value) . "')
							");
						}
					}

					$db->free_result($res2);
				} else {
					$db->query('
						DELETE FROM ' . POLL_OPTIONS_TABLE . '
						WHERE topic_id = ' . $row['topic_id']
					);

					$db->query('
						DELETE FROM ' . POLL_VOTES_TABLE . '
						WHERE topic_id = ' . $row['topic_id']
					);
				}

				$db->query('
					UPDATE ' . POSTS_TABLE . '
					SET	enable_bbcodes = ' . (int)!$bbcodes . ',
						enable_smilies = ' . (int)!$smilies . ',
						enable_urls = ' . (int)!$urls . ',
						enable_signatur = ' . (int)$signatur . ",
						post_text = '" . $db->chars(trim($text)) . "',
						post_edit_username = '" . $user->row['username'] . "',
						post_edit_user_level = " . $user->row['user_level'] . ',
						post_edit_user_id = ' . $user->row['user_id'] . ',
						post_edit_time = ' . time() . '
					WHERE post_id = ' . $row['post_id']
				);

				$db->query('
					UPDATE ' . TOPICS_TABLE . "
					SET	topic_title = '" . $db->chars(trim($title)) . "',
						poll_title = '" . $db->chars(trim($poll_title)) . "',
						poll_time = " . ($poll_days == 0 ? 0 : (time() + 24*3600*$poll_days)) . ',
						poll_votes = ' . ($poll_title && $poll_num == $poll_old_num ? $row['poll_votes'] : 0) . ',
						topic_label = '.$_POST['label'].'
					WHERE topic_id = ' . $row['topic_id']
				);

				header('Location: viewtopic.php?id=' . $row['topic_id']);
			}
		}
		
			$res5 = $db->query('SELECT * FROM ' . LABEL_TABLE . '');
			while ($row5 = $db->fetch_array($res5)) {
				if($row5['label_id']==$row['topic_label']) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				$labels .= '<option value="'.$row5['label_id'].'" style="background: '.$row5['label_color'].';" '.$selected.'>'.$row5['label_text'].'</option>';
			}

		template::assign(array(
			'TOPIC_ID'		=>	$row['topic_id'],
			'LABELS'		=>	$labels,
			'IS_MOD'		=>	($user->row['user_level'] == ADMIN || $user->row['user_level'] == MOD),
			'TOPIC_TITLE'	=>	$row['topic_title']
		));
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
		'EDIT'		=>	$edit,
		'ERROR'		=>	$error,
		'ID'		=>	$id,
		'PREVIEW'	=>	$preview,
		'TEXT'		=>	htmlspecialchars(trim($text)),
		'TITLE'		=>	htmlspecialchars(trim($title)),
		'POLL_TITLE'	=>	htmlspecialchars(trim($poll_title)),
		'POLL_OPTIONS'	=>	htmlspecialchars(trim($poll_options)),
		'POLL_DAYS'	=>	$poll_days,
		'FORUM_NAME'	=>	$row['forum_name'],
		'FORUM_ID'	=>	$row['forum_id'],
		'BBCODES'	=>	$bbcodes,
		'SMILIES'	=>	$smilies,
		'URLS'		=>	$urls,
		'SIGNATUR'	=>	$signatur,
		'POSTS_PERDAY'	=>	$config['posts_perday'],
		'MAX_CHARS'	=>	$config['max_post_chars']
	));

	template::display('newtopic');
?>