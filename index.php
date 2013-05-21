<?php
	/**
	*
	* @package com.Itschi.Index
	* @since 2013/02/13
	*
	*/
	
	if (!file_exists('config.php')) {
		header('Location: install.php?step=1');
		exit;
	}
	
	require 'base.php';
	include 'lib/feed.php';

	\Itschi\lib\feed(5);

	if ($config['index_news']) {
		$cRes = $db->query("
			SELECT p.*, t.topic_title, u.username, t.topic_time, f.forum_name AS forum_title
			FROM " . POSTS_TABLE . " AS p
			INNER JOIN " . TOPICS_TABLE . " AS t
				ON t.topic_id = p.topic_id
			INNER JOIN " . FORUMS_TABLE . " AS f
				ON t.forum_id = f.forum_id
			INNER JOIN " . USERS_TABLE . " AS u
				ON u.user_id = p.user_id
			WHERE p.is_topic = 1 AND f.is_news = 1
			ORDER BY p.post_id DESC
		");

		$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;
		$pages_num = ceil($db->num_rows($cRes) / $config['posts_perpage']);

		$res = $db->query("
			SELECT p.*, t.topic_title, u.username, t.topic_time, f.forum_name AS forum_title
			FROM " . POSTS_TABLE . " AS p
			INNER JOIN " . TOPICS_TABLE . " AS t
				ON t.topic_id = p.topic_id
			INNER JOIN " . FORUMS_TABLE . " AS f
				ON t.forum_id = f.forum_id
			INNER JOIN " . USERS_TABLE . " AS u
				ON u.user_id = p.user_id
			WHERE p.is_topic = 1 AND f.is_news = 1
			ORDER BY p.post_id DESC
			LIMIT " . ($page * $config['posts_perpage'] - $config['posts_perpage']) . ", " . $config['posts_perpage'] . "
		");

		while ($row = $db->fetch_object($res)) {
			$datext = functions::date()->strTimeDifference(date("d.m.Y H:i", $row->topic_time), date("d.m.Y H:i"), false);

			template::assignBlock('news', array(
				'TITLE'	=>	$row->topic_title,
				'TEXT'	=>	replace($row->post_text, $row->enable_bbcodes, $row->enable_smilies, $row->enable_urls),
				'DATE'	=>	$datext,
				'TOPIC_ID'	=>	$row->topic_id,
				'FORUM_ID'	=>	$row->forum_id,
				'FORUM_TITLE'	=>	$row->forum_title,
				'COMMENTS_NUM'	=>	$db->result($db->query("SELECT COUNT(post_id) FROM " . POSTS_TABLE . " WHERE topic_id = '".$row->topic_id."'"), 0) - 1
			));
		}
	}

	template::assign(array(
		'TITLE_TAG'	=>	'Startseite | ',
		'USER_LEGEND'	=>	$user->legend($user->row['user_level']),
		'NEWS_ACTIVE'	=>	$config['index_news'],
		'PAGENR'			=>	$page,
		'PAGES_NUM'		=>	$pages_num,
		'PAGES'			=>	($pages_num > 1) ? pages($pages_num, $page, 'index.php?page=') : ''
	));

	template::display('index');
?>
