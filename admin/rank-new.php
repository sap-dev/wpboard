<?php
	/**
	*
	* @package com.Itschi.ACP.ranks.new
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
	$title = (isset($_POST['title'])) ? strip(trim($_POST['title'])) : '';
	$special = (isset($_POST['special'])) ? (int)$_POST['special'] : 0;
	$posts = (isset($_POST['posts'])) ? (int)$_POST['posts'] : 0;
	$icon = (isset($_POST['icon'])) ? $_POST['icon'] : '';

	if ($title) {
		if ($id) {
			$db->query('
				UPDATE ' . RANKS_TABLE . "
				SET	rank_title	=	'" . $db->chars($title) . "',
					rank_image	=	'" . $db->chars($icon) . "',
					rank_posts	=	" . $posts . ',
					rank_special	=	' . $special . '
				WHERE rank_id = ' . $id
			);
		} else {
			$db->query('
				INSERT INTO ' . RANKS_TABLE . "
				(rank_title, rank_image, rank_posts, rank_special) VALUES
				('" . $db->chars($title) . "', '" . $db->chars($icon) . "', " . $posts . ', ' . $special . ')
			');
		}

		$cache->delete('ranks');

		header('Location: ranks.php');
	} else if (!isset($_POST['submit']) && $id) {
		$res = $db->query('
			SELECT *
			FROM ' . RANKS_TABLE . '
			WHERE rank_id = ' . $id
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row['rank_id']) {
			message_box('Der Rang existiert nicht!', 'ranks.php', 'zurück');
			exit;
		}

		$title = htmlspecialchars($row['rank_title']);
		$posts = $row['rank_posts'];
		$special = $row['rank_special'];
		$icon = $row['rank_image'];
	}

	$extensions = array('png', 'jpg', 'jpeg', 'gif', 'bmp');

	if ($handle = @opendir('../images/ranks/')) {
		while (($file = @readdir($handle)) !== false) {
			$ext = explode('.', $file);

			if (count($ext) == 1) continue;

			$ext = end($ext);

			if ($file != '.' && $file != '..' && !is_dir('../images/ranks/' . $file) && in_array(strtolower($ext), $extensions)) {
				template::assignBlock('icons', array(
					'ICON'	=>	$file
				));
			}
		}

		closedir($handle);
	}


	template::assign(array(
		'MENU_BUTTON'	=>	8,
		'ID'		=>	$id,
		'TITLE'		=>	htmlspecialchars($title),
		'POSTS'		=>	$posts,
		'SPECIAL'	=>	$special,
		'ICON'		=>	$icon
	));

	template::display('rank-new', true);
?>