<?php
	/**
	*
	* @package com.Itschi.ACP.forums.new
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
	$is_category = ($_POST['is_category'] == 1) ? 1 : 0;
	$is_subforum = ($_POST['is_category'] == 2) ? 1 : 0;
	$closed = (isset($_POST['closed'])) ? (int)isset($_POST['closed']) : 0;
	$name =	(isset($_POST['name'])) ? strip(trim($_POST['name'])) : '';
	$description = (isset($_POST['description'])) ? strip(trim($_POST['description'])) : '';
	$level =	(isset($_POST['level'])) ? (int)$_POST['level'] : 0;
	$topforum = (isset($_POST['topforum'])) ? (int)$_POST['topforum'] : 0;

	if ($name) {
		if ($id) {
			$db->query('
				UPDATE ' . FORUMS_TABLE . '
				SET	forum_closed 		= 	' . $closed . ',
					forum_level			=	' . $level . ',
					is_category			=	' . $is_category . ",
					forum_name			=	'" . $db->chars($name) . "',
					forum_description	=	'" . (($is_category) ? '' : $db->chars($description)) . "',
					forum_toplevel		=	'".(($is_subforum) ? $topforum : 0)."'
				WHERE forum_id = " . $id . "
			");
		} else {
			$res = $db->query('
				SELECT forum_order
				FROM ' . FORUMS_TABLE . '
				ORDER BY forum_order DESC
			');

			$row = $db->fetch_array($res);
			$db->free_result($res);

			$db->query('
				INSERT INTO ' . FORUMS_TABLE . '
				(forum_closed, forum_level, is_category, forum_order, forum_name, forum_description, forum_toplevel)
				VALUES (' . $closed . ',
					' . $level . ',
					' . $is_category . ',
					' . ((!$row['forum_order']) ? 1 : $row['forum_order'] + 1) . ",
					'" . $db->chars($name) . "',
					'" . (($is_category) ? '' : $db->chars($description)) . "',
					'" . (($is_subforum) ? $topforum : 0) . "'
					)
			");
		}

		header('Location: forums.php');
	} else if (!isset($_POST['submit']) && $id) {
		$res = $db->query('
			SELECT *
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . (int)$_GET['id']
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);


		if (!$row['forum_id']) {
			message_box('Forum existiert nicht!', '../', 'zurück');
			exit;
		}

		$is_category = $row['is_category'];
		$closed = $row['forum_closed'];
		$name = $row['forum_name'];
		$description = $row['forum_description'];
		$level = $row['forum_level'];
		$is_subforum = ($row['forum_toplevel'] > 0) ? 1 : 0;
	}

	$res = $db->query("SELECT forum_id, forum_name FROM " . FORUMS_TABLE . " WHERE forum_toplevel = 0 && is_category = 0");
	while ($fRow = $db->fetch_object($res)) {
		template::assignBlock('forums', array(
			'NAME'	=>	$fRow->forum_name,
			'ID'	=>	$fRow->forum_id
		));
	}

	template::assign(array(
		'MENU_BUTTON'	=>	5,
		'ID'		=>	$id,
		'NAME'		=>	htmlspecialchars($name),
		'DESCRIPTION'	=>	htmlspecialchars($description),
		'LEVEL'		=>	$level,
		'CLOSED'	=>	$closed,
		'IS_CATEGORY'	=>	$is_category,
		'IS_SUBFORUM'	=>	$is_subforum
	));

	template::display('forum-new', true);
?>