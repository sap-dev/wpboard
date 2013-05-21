<?php
	/**
	*
	* @package com.Itschi.base.functions.topic
	* @since 2007/05/25
	*
	*/

	function mark_forum($forum_id = false) {
		global $db, $user;

		$res = $db->query('
			SELECT forum_id, forum_last_post_time
			FROM ' . FORUMS_TABLE . '
			WHERE is_category = 0
			' . (($forum_id) ? ' AND forum_id = ' . (int)$forum_id : '') . '
		');

		while ($row = $db->fetch_array($res)) {
			$db->query('
				DELETE FROM ' . TOPICS_TRACK_TABLE . '
				WHERE forum_id = ' . $row['forum_id'] . '
					AND user_id = ' . $user->row['user_id']
			);

			$db->query('
				DELETE FROM ' . FORUMS_TRACK_TABLE . '
				WHERE forum_id = ' . $row['forum_id'] . '
					AND user_id = ' . $user->row['user_id']
			);

			$db->query('
				INSERT INTO ' . FORUMS_TRACK_TABLE . '
				(forum_id, user_id, mark_time) VALUES
				(' . $row['forum_id'] . ', ' . $user->row['user_id'] . ', ' . $row['forum_last_post_time'] . ')
			');
		}

		$db->free_result($res);

		if ($forum_id) {
			message_box('Alle Themen in diesem Forum wurden als gelesen markiert', 'viewforum.php?id=' . $forum_id, 'zurück zum Forum', '', '', 3);
		} else {
			message_box('Alle Foren wurden als gelesen markiert', 'forum.php', 'zurück zum Forum', '', '', 3);
		}
	}

	function mark_topic($topic_id, $forum_id, $forum_last_post_time, $forum_mark_time, $last_time) {
		global $db, $user;

		$db->query('
			DELETE FROM ' . TOPICS_TRACK_TABLE . '
			WHERE topic_id = ' . $topic_id . '
				AND user_id = ' . $user->row['user_id']
		);

		$db->query('
			INSERT INTO ' . TOPICS_TRACK_TABLE . '
			(topic_id, forum_id, mark_time, user_id) VALUES
			(' . $topic_id . ', ' . $forum_id . ', ' . $last_time . ', ' . $user->row['user_id'] . ')
		');

		$new_topics = false;
		$res2 = $db->query('
			SELECT t.topic_last_post_time, r.mark_time
			FROM ' . TOPICS_TABLE . ' t
				LEFT JOIN ' . TOPICS_TRACK_TABLE . ' r ON r.topic_id = t.topic_id AND r.user_id = ' . $user->row['user_id'] . '
			WHERE t.forum_id = ' . $forum_id . '
				AND ' . max($forum_mark_time, $user->row['user_register']) . ' < t.topic_last_post_time
		');

		while ($row2 = $db->fetch_array($res2)) {
			if ($row2['mark_time'] < $row2['topic_last_post_time']) {
				$new_topics = true;
				break;
			}
		}

		if ($new_topics == false) {
			$db->query('
				DELETE FROM ' . TOPICS_TRACK_TABLE . '
				WHERE forum_id = ' . $forum_id . '
				AND user_id = ' . $user->row['user_id']
			);

			$db->query('
				DELETE FROM ' . FORUMS_TRACK_TABLE . '
				WHERE forum_id = ' . $forum_id . '
				AND user_id = ' . $user->row['user_id']
			);

			$db->query('
				INSERT INTO ' . FORUMS_TRACK_TABLE . '
				(forum_id, user_id, mark_time) VALUES
				(' . $forum_id . ', ' . $user->row['user_id'] . ', ' . $forum_last_post_time . ')
			');
		}
	}

	function poll_vote($row, $option_id) {
		global $db, $user;

		$res = $db->query('
			SELECT o.option_id, p.user_id as vote
			FROM ' . POLL_OPTIONS_TABLE . ' o
				LEFT JOIN ' . POLL_VOTES_TABLE . ' p ON p.topic_id = ' . $row['topic_id'] . ' AND p.user_id = ' . $user->row['user_id'] . '
			WHERE o.option_id = ' . (int)$option_id
		);

		$row2 = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row2['option_id'] || $row2['vote']) {
			$message = 'Du hast schon gevotet oder keine Auswahlmöglichkeit ausgewählt';
		} else {
			$db->query('
				INSERT INTO ' . POLL_VOTES_TABLE . '
				(topic_id, user_id) VALUES 
				(' . $row['topic_id'] . ', ' . $user->row['user_id'] . ')
			');

			$db->query('
				UPDATE ' . POLL_OPTIONS_TABLE . '
				SET option_votes = option_votes + 1
				WHERE option_id = ' . $row2['option_id']
			);

			$db->query('
				UPDATE ' . TOPICS_TABLE . '
				SET poll_votes = poll_votes + 1
				WHERE topic_id = ' . $row['topic_id']
			);

			$message = 'Deine Stimme wurde abgegeben';
		}

		message_box($message, 'viewtopic.php?id=' . $row['topic_id'], 'weiter zum Thema', 'viewforum.php?id=' . $row['forum_id'], 'zurück zum Forum', 3);
	}

	function close_topic($row) {
		global $db, $user;

		if ($user->row['user_level'] != ADMIN && $user->row['user_level'] != MOD) {
			return false;
		}

		$db->query('
			UPDATE ' . TOPICS_TABLE . '
			SET topic_closed = ' . (($row['topic_closed']) ? 0 : 1) . '
			WHERE topic_id = ' . $row['topic_id']
		);

		message_box('Das Thema wurde ' . (($row['topic_closed']) ? 'geöffnet' : 'geschlossen'), 'viewtopic.php?id=' . $row['topic_id'], 'weiter zum Thema', 'viewforum.php?id=' . $row['forum_id'], 'zurück zum Forum', 3);
	}

	function important_topic($row) {
		global $db, $user;

		if ($user->row['user_level'] != ADMIN && $user->row['user_level'] != MOD) {
			return false;
		}

		$db->query('
			UPDATE ' . TOPICS_TABLE . '
			SET topic_important = ' . (($row['topic_important']) ? 0 : 1) . '
			WHERE topic_id = ' . $row['topic_id']
		);

		message_box('Das Thema wurde als ' . (($row['topic_important']) ? 'nicht ' : '') . 'wichtig markiert', 'viewtopic.php?id=' . $row['topic_id'], 'weiter zum Thema', 'viewforum.php?id=' . $row['forum_id'], 'zurück zum Forum', 3);
	}

	function delete_topic_post($post_id) {
		global $db, $user, $config;

		$res = $db->query('
			SELECT p.*, t.topic_posts
			FROM ' . POSTS_TABLE . ' p, ' . TOPICS_TABLE . ' t
			WHERE p.post_id = ' . (int)$post_id . '
				AND t.topic_id = p.topic_id
		');

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row['post_id'] || ($row['user_id'] != $user->row['user_id'] && $user->row['user_level'] != ADMIN && $user->row['user_level'] != MOD)) {
			return false;
		}

		if ($row['is_topic']) {
			if (empty($_GET['ok'])) {
				message_box('Willst du das Thema wirklich löschen?', 'viewforum.php?id=' . $row['forum_id'] . '&delete=' . $row['post_id'] . '&ok=1', 'Thema löschen', '/forum/' . $row['forum_id'] . '/' . $row['topic_id'], 'Abbrechen');
			}

			$res2 = $db->query('
				SELECT is_topic, user_id
				FROM ' . POSTS_TABLE . '
				WHERE topic_id = ' . $row['topic_id']
			);

			while ($row2 = $db->fetch_array($res2)) {
				$db->query('
					UPDATE ' . USERS_TABLE . '
					SET	user_posts = user_posts - 1,
						user_points = user_points - ' . (($row2['is_topic']) ? $config['points_topic'] : $config['points_post']) . '
					WHERE user_id = ' . $row2['user_id']
				);
			}

			config_set_count('posts_num', -$row['topic_posts']);
			config_set_count('topics_num', -1);

			$db->free_result($res2);

			$db->query('DELETE FROM ' . TOPICS_TRACK_TABLE . ' WHERE topic_id = ' . $row['topic_id']);
			$db->query('DELETE FROM ' . TOPICS_TABLE . ' WHERE topic_id = ' . $row['topic_id']);
			$db->query('DELETE FROM ' . POSTS_TABLE . ' WHERE topic_id = ' . $row['topic_id']);
			$db->query('DELETE FROM ' . POLL_OPTIONS_TABLE . ' WHERE topic_id = ' . $row['topic_id']);
			$db->query('DELETE FROM ' . POLL_VOTES_TABLE . ' WHERE topic_id = ' . $row['topic_id']);

			$res2 = $db->query('
				SELECT topic_id, topic_last_post_user_id, topic_last_post_username, topic_last_post_user_level, topic_last_post_time, topic_last_post_id
				FROM ' . TOPICS_TABLE . '
				WHERE forum_id = ' . $row['forum_id'] . '
				ORDER BY topic_last_post_time DESC LIMIT 1
			');

			$row2 = $db->fetch_array($res2);
			$db->free_result($res2);

			$db->query('
				UPDATE ' . FORUMS_TABLE . '
				SET	forum_topics = forum_topics - 1,
					forum_posts = forum_posts - ' . (int)$row['topic_posts'] . ',
					forum_last_post_id = ' . (int)$row2['topic_last_post_id'] . ',
					forum_last_post_user_id = ' . (int)$row2['topic_last_post_user_id'] . ",
					forum_last_post_username = '" . $row2['topic_last_post_username'] . "',
					forum_last_post_user_level = " . (int)$row2['topic_last_post_user_level'] . ',
					forum_last_post_time = ' . (int)$row2['topic_last_post_time'] . ',
					forum_last_post_topic_id = ' . (int)$row2['topic_id'] . '
				WHERE forum_id = ' . $row['forum_id']
			);

			message_box('Das Thema wurde gelöscht', 'viewforum.php?id=' . $row['forum_id'], 'zurück zum Forum', '', '', 3);
		} else {
			if (!isset($_GET['ok'])) {
				message_box('Willst du den Beitrag wirklich löschen?', 'viewtopic.php?id=' . $row['topic_id'] . '&delete=' . $row['post_id'] . '&ok=1', 'Beitrag löschen', '/forum/' . $row['forum_id'] . '/' . $row['topic_id'], 'Abbrechen');
			}

			config_set_count('posts_num', -1);

			$db->query('DELETE FROM ' . POSTS_TABLE . ' WHERE post_id = ' . $row['post_id']);

			$res2 = $db->query('
				SELECT p.post_id, p.user_id, p.post_time, u.username, u.user_level
				FROM ' . POSTS_TABLE . ' p, ' . USERS_TABLE . ' u
				WHERE p.topic_id = ' . $row['topic_id'] . '
					AND u.user_id = p.user_id
				ORDER BY p.post_id DESC LIMIT 1
			');

			$row2 = $db->fetch_array($res2);
			$db->free_result($res2);

			$db->query('
				UPDATE ' . TOPICS_TABLE . '
				SET	topic_posts = topic_posts - 1,
					topic_last_post_user_id = ' . $row2['user_id'] . ",
					topic_last_post_username = '" . $row2['username'] . "',
					topic_last_post_user_level = " . $row2['user_level'] . ',
					topic_last_post_time = ' . $row2['post_time'] . ',
					topic_last_post_id = ' . $row2['post_id'] . '
				WHERE topic_id = ' . $row['topic_id']
			);

			$db->query('
				UPDATE ' . USERS_TABLE . '
				SET	user_posts = user_posts - 1,
					user_points = user_points - ' . $config['points_post'] . '
				WHERE user_id = ' . $row['user_id']
			);

			$res2 = $db->query('
				SELECT topic_id, topic_last_post_user_id, topic_last_post_username, topic_last_post_user_level, topic_last_post_time, topic_last_post_id, topic_posts
				FROM ' . TOPICS_TABLE . '
				WHERE forum_id = ' . $row['forum_id'] . '
				ORDER BY topic_last_post_time DESC LIMIT 1
			');

			$row2 = $db->fetch_array($res2);
			$db->free_result($res2);

			$db->query('
				UPDATE ' . FORUMS_TABLE . '
				SET	forum_posts = forum_posts - 1,
					forum_last_post_id = ' . $row2['topic_last_post_id'] . ',
					forum_last_post_user_id = ' . $row2['topic_last_post_user_id'] . ",
					forum_last_post_username = '" . $row2['topic_last_post_username'] . "',
					forum_last_post_user_level = " . $row2['topic_last_post_user_level'] . ',
					forum_last_post_time = ' . $row2['topic_last_post_time'] . ',
					forum_last_post_topic_id = ' . $row2['topic_id'] . '
				WHERE forum_id = ' . $row['forum_id']
			);

			message_box('Der Beitrag wurde gelöscht', 'viewtopic.php?id=' . $row['topic_id'], 'zurück zum Thema', '', '', 3);
		}
	}
?>