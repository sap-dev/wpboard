<?php
	/**
	*
	* @package com.Itschi.ACP.forums
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	if (isset($_GET['up'])) {
		$res = $db->query('
			SELECT forum_id, forum_order
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . (int)$_GET['up']
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);

		$res2 = $db->query('
			SELECT MIN(forum_order) AS forum_order
			FROM ' . FORUMS_TABLE . '
		');

		$row2 = $db->fetch_array($res2);
		$db->free_result($res2);

		if ($row['forum_order'] > 1 && $row['forum_order'] != $row2['forum_order']) {
			$db->query('
				UPDATE ' . FORUMS_TABLE . '
				SET forum_order = ' . (int)$row['forum_order'] . '
				WHERE forum_order = ' . (int)($row['forum_order'] - 1)
			);

			$db->query('
				UPDATE ' . FORUMS_TABLE . '
				SET forum_order = ' . (int)($row['forum_order'] - 1) . '
				WHERE forum_id = ' . (int)$row['forum_id']
			);
		}
	}

	if (isset($_GET['down'])) {
		$res = $db->query('
			SELECT forum_id, forum_order
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . (int)$_GET['down']
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);

		$res2 = $db->query('
			SELECT MAX(forum_order) AS forum_order
			FROM ' . FORUMS_TABLE . '

		');

		$row2 = $db->fetch_array($res2);
		$db->free_result($res2);

		if ($row2['forum_order'] != $row['forum_order']) {
			$db->query('
				UPDATE ' . FORUMS_TABLE . '
				SET forum_order = ' . $row['forum_order'] . '
				WHERE forum_order = ' . (int)($row['forum_order'] + 1)
			);

			$db->query('
				UPDATE ' . FORUMS_TABLE . '
				SET forum_order = ' . (int)($row['forum_order'] + 1) . '
				WHERE forum_id = ' . $row['forum_id']
			);
		}
	}

	if (isset($_GET['delete'])) {
		$forum_id = (int)$_GET['delete'];

		if (empty($_GET['ok'])) {
			message_box('Willst du das Forum mit Inhalt wirklich löschen?', 'forums.php?delete=' . $forum_id . '&ok=1', 'Forum löschen', 'forums.php', 'Abbrechen');
			exit;
		} else {
			$res = $db->query('
				SELECT *
				FROM ' . FORUMS_TABLE . '
				WHERE forum_id = ' . $forum_id
			);

			$row = $db->fetch_array($res);
			$db->free_result($res);

			config_set_count('posts', -$row['forum_posts']);
			config_set_count('topics', -$row['forum_topics']);

			$res2 = $db->query('
				SELECT topic_id
				FROM ' . TOPICS_TABLE . '
				WHERE forum_id = ' . $forum_id
			);

			while ($row2 = $db->fetch_array($res2)) {
				$db->query('DELETE FROM ' . POLL_OPTIONS_TABLE . ' WHERE topic_id = ' . $row2['topic_id']);
				$db->query('DELETE FROM ' . POLL_VOTES_TABLE . ' WHERE topic_id = ' . $row2['topic_id']);
			}

			$db->free_result($res2);

			$db->query('DELETE FROM ' . FORUMS_TABLE . ' WHERE forum_id = ' . $forum_id);
			$db->query('DELETE FROM ' . FORUMS_TRACK_TABLE . ' WHERE forum_id = ' . $forum_id);
			$db->query('DELETE FROM ' . TOPICS_TABLE . ' WHERE forum_id = ' . $forum_id);
			$db->query('DELETE FROM ' . TOPICS_TRACK_TABLE . ' WHERE forum_id = ' . $forum_id);
			$db->query('DELETE FROM ' . POSTS_TABLE . ' WHERE forum_id = ' . $forum_id);
		}
	}

	$res = $db->query('
		SELECT *
		FROM ' . FORUMS_TABLE . '
		WHERE forum_toplevel = 0
		ORDER BY forum_order
	');

	while ($row = $db->fetch_array($res)) {
		$subforums = array();
		$fRes = $db->query("SELECT * FROM " . FORUMS_TABLE . " WHERE forum_toplevel = '" . $row['forum_id'] . "'");
		while ($fRow = $db->fetch_array($fRes)) {
			$subforums[] = $fRow;
		}

		template::assignBlock('forums', array(
			'ID'		=>	$row['forum_id'],
			'IS_CATEGORY'	=>	$row['is_category'],
			'NAME'		=>	$row['forum_name'],
			'SUBFORUMS'		=>	$subforums
		));
	}

	$db->free_result($res);

	$res = $db->query('
		SELECT COUNT(*)
		FROM ' . FORUMS_TABLE . '
		WHERE is_category = 1
	');

	$cats_num = $db->result($res, 0);
	$db->free_result($res);

	$res2 = $db->query('
		SELECT COUNT(*)
		FROM ' . FORUMS_TABLE . '
		WHERE is_category = 0
	');

	$forums_num = $db->result($res2, 0);
	$db->free_result($res2);

	template::assign(array(
		'MENU_BUTTON'	=>	5,
		'CATS_NUM'	=>	$cats_num,
		'FORUMS_NUM'	=>	$forums_num
	));

	template::display('forums', true);
?>