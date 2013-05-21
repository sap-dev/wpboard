<?php
	/**
	*
	*	@package com.Itschi.ACP.settings
	*	@since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$handle = opendir($root . 'styles/');
	while ($file = readdir($handle)) {
		if ($file != '.' && $file != '..' && is_dir($root . 'styles/' . $file)) {
			template::assignBlock('themes', array(
				'NAME'	=>	$file
			));
		}
	}

	closedir($handle);

	$handle = opendir($root . 'images/avatar/');
	while ($file = readdir($handle)) {
		if ($file != '.' && $file != '..') {
			if (@file_exists($root . 'images/avatar/mini/' . $file) && (int)mb_substr($file, 0, 1) == 0) {
				template::assignBlock('avatars', array(
					'IMAGE'	=>	$file
				));
			}
		}
	}

	closedir($handle);


	$error = 0;
	if (isset($_POST['submit'])) {
		config_set('title', strip($_POST['title']));
		config_set('description', strip($_POST['description']));
		config_set('enable', (int)$_POST['enable']);
		config_set('enable_text', strip($_POST['enable_text']));
		config_set('topics_perpage', max($_POST['topics_perpage'], 1));
		config_set('posts_perpage', max($_POST['posts_perpage'], 1));
		config_set('points_topic', max($_POST['points_topic'], 0));
		config_set('points_post', max($_POST['points_post'], 0));
		config_set('enable_captcha', (int)$_POST['enable_captcha']);
		config_set('enable_unlock', (int)$_POST['enable_unlock']);
		config_set('unlock_delete', (int)$_POST['unlock_delete']);
		config_set('enable_avatars', (int)$_POST['enable_avatars']);
		config_set('enable_bots', (int)$_POST['enable_bots']);
		config_set('avatar_min_width', max($_POST['avatar_min_width'], 0));
		config_set('avatar_min_height', max($_POST['avatar_min_height'], 0));
		config_set('avatar_max_width', max($_POST['avatar_max_width'], 0));
		config_set('avatar_max_height', max($_POST['avatar_max_height'], 0));
		config_set('mail_limit', (int)$_POST['mail_limit']);
		config_set('enable_delete', (int)$_POST['enable_delete']);
		config_set('posts_perday', max($_POST['posts_perday'], 0));
		config_set('max_post_chars', max($_POST['max_post_chars'], 0));

		$error = 1;

		// require_once '../lib/functions/user.php';

		if (functions::user()->valid_email($_POST['email'])) {
			config_set('email', $_POST['email']);
		} else {
			$error = 2;
		}

		if (!empty($_POST['default_avatar'])) {
			config_set('default_avatar', strip($_POST['default_avatar']));
		} else {
			$error = 3;
		}

		$cache->delete('config');
		$config = config_vars();
	}

	template::assign(array(
		'MENU_BUTTON'		=>	2,
		'ERROR'			=>	$error,
		'TITLE'			=>	htmlspecialchars($config['title']),
		'DESCRIPTION'		=>	htmlspecialchars($config['description']),
		'EMAIL'			=>	htmlspecialchars($config['email']),
		'ENABLE'		=>	$config['enable'],
		'ENABLE_TEXT'		=>	htmlspecialchars($config['enable_text']),
		'DEFAULT_AVATAR'	=>	htmlspecialchars($config['default_avatar']),
		'TOPICS_PERPAGE'	=>	$config['topics_perpage'],
		'POSTS_PERPAGE'		=>	$config['posts_perpage'],
		'POINTS_TOPIC'		=>	$config['points_topic'],
		'POINTS_POST'		=>	$config['points_post'],
		'POSTS_PERDAY'		=>	$config['posts_perday'],
		'MAX_POST_CHARS'	=>	$config['max_post_chars'],
		'ENABLE_BOTS'		=>	$config['enable_bots'],
		'ENABLE_CAPTCHA'	=>	$config['enable_captcha'],
		'ENABLE_UNLOCK'		=>	$config['enable_unlock'],
		'UNLOCK_DELETE'		=>	$config['unlock_delete'],
		'ENABLE_AVATARS'	=>	$config['enable_avatars'],
		'AVATAR_MAX_HEIGHT'	=>	$config['avatar_max_height'],
		'AVATAR_MIN_HEIGHT'	=>	$config['avatar_min_height'],
		'AVATAR_MAX_WIDTH'	=>	$config['avatar_max_width'],
		'AVATAR_MIN_WIDTH'	=>	$config['avatar_min_width'],
		'MAIL_LIMIT'		=>	$config['mail_limit'],
		'ENABLE_DELETE'		=>	$config['enable_delete']
	));

	template::display('settings', true);
?>