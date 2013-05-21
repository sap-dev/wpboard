<?php
	/**
	*
	* @package com.Itschi.forum.viewforum
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	if ($user->row) {
		if (isset($_GET['delete'])) {
			// include 'lib/functions/topic.php';

			functions::topic()->delete_topic_post($_GET['delete']);
		} else if (isset($_GET['mark'])) {
			// include 'lib/functions/topic.php';

			functions::topic()->mark_forum($_GET['id']);
		}
	}

	$res = $db->query(
		($user->row) ? '
		SELECT f.*, t.mark_time
		FROM ' . FORUMS_TABLE . ' f
			LEFT JOIN ' . FORUMS_TRACK_TABLE . ' t ON t.forum_id = f.forum_id AND t.user_id = ' . $user->row['user_id'] . '
		WHERE f.forum_id = ' . (int)$_GET['id']
		 : '
		SELECT f.*
		FROM ' . FORUMS_TABLE . ' f
		WHERE f.forum_id = ' . (int)$_GET['id']
	);

	$row = $db->fetch_array($res);
	$db->free_result($res);

	$user_level = ($user->row) ? $user->row['user_level'] + 1 : 0;

	if (!$row || $row['is_category']) {
		message_box('Die Kategorie existiert nicht', 'forum.php', 'zurück zum Forum');
	} else if ($user_level < $row['forum_level']) {
		message_box('Du bist nicht berechtigt die Kategorie zu sehen', 'forum.php', 'zurück zum Forum');
	}

	$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;
	$pages_num = ceil($row['forum_topics']/$config['topics_perpage']);
	$mark_time = ($user->row) ? max($row['mark_time'], $user->row['user_register']) : 0;
	
	switch($_GET['order']) {
		case "label":
			$orderby = 't.topic_label,';
		break;
		case "view":
			$orderby = 't.topic_views DESC,';
		break;
		default:
			$orderby = '';
		break;
	}
	
	
	$order = $_GET['order'];
	
	
	$res2 = $db->query(
		(($user->row) ? '
		SELECT t.*, r.mark_time
		FROM ' . TOPICS_TABLE . ' t
			LEFT JOIN ' . TOPICS_TRACK_TABLE . ' r ON r.topic_id = t.topic_id AND r.user_id = ' . $user->row['user_id']
		 : '
		SELECT t.*
		FROM ' . TOPICS_TABLE . ' t
		') . '
		WHERE t.forum_id = ' . $row['forum_id'] . '
		ORDER BY '.$orderby.' t.topic_important DESC, t.topic_last_post_time DESC
		LIMIT ' . ($page * $config['topics_perpage'] - $config['topics_perpage']) . ', ' . $config['topics_perpage']
	);

	while ($row2 = $db->fetch_array($res2)) {
		$post_pages = ceil(($row2['topic_posts']+1)/$config['posts_perpage']);
		$jump = '';

		if ($post_pages > 1) {
			for ($t2 = $post_pages-3, $i = 1, $anhang = ''; $post_pages >= $i; $i++) {
				if ($i > $t2 || $i == 1) {
					$jump .= $anhang . ' <a class="seite" href="viewtopic.php?id=' . $row2['topic_id'] . '&page=' . $i . '">' . $i . '</a>';
					$anhang = '';
				} else {
					$anhang = ' ... ';
				}
			}
		}

		$row2['new'] = ($user->row['user_id'] && max($row2['mark_time'], $mark_time) < $row2['topic_last_post_time']);

		$uRes = $db->query("SELECT * FROM " . USERS_TABLE . " WHERE user_id = '" . $row2['topic_last_post_user_id'] . "'");
		$row33 = $db->fetch_array($uRes);
		
		$tRes = $db->query("SELECT * FROM " . POSTS_TABLE . " WHERE topic_id = '" . $row2['topic_id'] . "' ORDER by post_time ASC");
		$row44 = $db->fetch_array($tRes);
		
		$lRes = $db->query("SELECT * FROM " . LABEL_TABLE . " WHERE label_id = '" . $row2['topic_label'] . "'");
		$label = $db->fetch_array($lRes);
		
		$labela = '<div class="badge" style="background: '.$label['label_color'].'">'.$label['label_text'].'</div>';
		template::assignBlock('topics', array(
			'ID'					=>	$row2['topic_id'],
			'NEW'					=>	$row2['new'],
			'PAGES'					=>	$jump,
			'TITLE'					=>	htmlspecialchars($row2['topic_title']),
			'ICON'					=>	($row2['topic_important'] ? 'info' : ($row2['topic_closed'] ? 'closed' : '')) . (($row2['new']) ? 'new' : ''),
			'POSTS'					=>	number_format($row2['topic_posts'], 0, '', '.'),
			'VIEWS'					=>	number_format($row2['topic_views'], 0, '', '.'),
			'TIME'					=>	date('d.m.y H:i', $row2['topic_time']),
			'USERNAME'				=>	$row2['username'],
			'USER_ID'				=>	$row2['user_id'],
			'LABEL'					=>	$labela,
			'LABEL_EXIST'			=>	$row2['topic_label'],
			'USER_LEGEND'			=>	$user->legend($row2['user_level']),
			'LAST_POST_TIME'		=>	date('d.m.y H:i', $row2['topic_last_post_time']),
			'LAST_POST_USER_LEGEND'	=>	$user->legend($row2['topic_last_post_user_level']),
			'LAST_POST_USER_ID'		=>	$row2['topic_last_post_user_id'],
			'LAST_POST_USERNAME'	=>	$row2['topic_last_post_username'],
			'LAST_POST_ID'			=>	$row2['topic_last_post_id'],
			'LAST_POST_USER_AVATAR'	=>	($row33['user_avatar']) ? $row33['user_avatar'] : $config['default_avatar'],
			'PREVIEW_TEXT'			=>	template::strShort(replace($row44['post_text'], $row44['enable_bbcodes'], $row44['enable_smilies'], $row44['enable_urls']),300)
		));
	}

	$db->free_result($res2);

	$sRes = $db->query("
		SELECT *
		FROM " . FORUMS_TABLE . " AS f
		INNER JOIN " . FORUMS_TRACK_TABLE . " AS t
			ON f.forum_id = t.forum_id
		WHERE f.forum_toplevel = ".$row['forum_id']."
	");

	while ($sRow = $db->fetch_array($sRes)) {
		$sRow['forum_icon'] = (($sRow['forum_closed']) ? 'closed' : '') . (($user->row['user_id'] && max($sRow['mark_time'], $user->row['user_register']) < $sRow['forum_last_post_time']) ? 'new' : '') . 'topic';

		$sRow = array_merge($sRow, array(
			'LAST_POST_TIME'	=>	date('d.m.y H:i', $sRow['forum_last_post_time']),
			'LAST_POST_USER_LEGEND'	=>	$user->legend($sRow['forum_last_post_user_level']),
			'LAST_POST_ID'		=>	$sRow['forum_last_post_id'],
			'LAST_POST_USER_ID'	=>	$sRow['forum_last_post_user_id'],
			'LAST_POST_USERNAME'	=>	$sRow['forum_last_post_username'],
			'LAST_POST_TOPIC_ID'	=>	$sRow['forum_last_post_topic_id'],
			'TOPICS'				=>	number_format($sRow['forum_topics'], 0, '', '.'),
			'POSTS'					=>	number_format($sRow['forum_posts'], 0, '', '.'),
		));

		$subforums[] = $sRow;
	}

	template::assign(array(
		'TITLE_TAG'	=>	'Forum | ' . htmlspecialchars($row['forum_name']) . ' | ',
		'FORUM_ID'	=>	$row['forum_id'],
		'FORUM_NAME'	=>	$row['forum_name'],
		'TOPICS'	=>	number_format($row['forum_topics'], 0, '', '.'),
		'FORUM_CLOSED'	=>	$row['forum_closed'],
		'PAGES_NUM'	=>	$pages_num,
		'PAGE'		=>	$page,
		'PAGES'		=>	($pages_num > 1) ? pages($pages_num, $page, 'viewforum.php?id=' . $row['forum_id'] . '&page=') : '',
		'SUBFORUMS'	=>	$subforums,
		'IS_MOD'	=>	$user->row['user_level'] == ADMIN || $user->row['user_level'] == MOD,
		'IS_NEWS'	=>	$row['is_news']
	));

	template::display('viewforum');


?>