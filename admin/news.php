<?php
	/**
	*
	* @package com.Itschi.ACP.news
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	if (isset($_POST['submit'])) {
		$activate = (int)$_POST['activate'];

		config_set('index_news', $activate);

		$path = '../lib/cache/';

		if ($handle = @opendir($path)) {
			while ($file = @readdir($handle)) {
				if ($file != '.' && $file != '..' && !is_dir($path . $file)) {
					@unlink($path . $file);
				}
			}

			closedir($handle);
		}

		header("Location: ./news.php");
		// header("Location: ./news.php?sync&token=" . $token->token);
	}

	if ((int)$_GET['activate'] > 0) {
		$db->query("
			UPDATE " . FORUMS_TABLE . "
			SET is_news = 1
			WHERE forum_id = '".(int)$_GET['activate']."'
		");

		header("Location: ./news.php");
	}

	if ((int)$_GET['deactivate'] > 0) {
		$db->query("
			UPDATE " . FORUMS_TABLE . "
			SET is_news = 0
			WHERE forum_id = '".(int)$_GET['deactivate']."'
		");

		header("Location: ./news.php");
	}

	$res = $db->query("
		SELECT forum_name, forum_id, is_news, is_category
		FROM " . FORUMS_TABLE . "
	");

	while ($row = $db->fetch_object($res)) {
		template::assignBlock('forums', array(
			'FORUM_TITLE'	=>	$row->forum_name,
			'IS_NEWS'	=>	$row->is_news,
			'FORUM_ID'	=>	$row->forum_id,
			'IS_CATEGORY'	=>	$row->is_category
		));
	}

	template::assign(array(
		'NEWS_ACTIVE'	=>	$config['index_news'],
		'SYNC_NOTICE'	=>	isset($_GET['sync'])
	));

	template::display('news');
?>