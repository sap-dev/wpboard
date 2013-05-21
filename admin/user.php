<?php
	/**
	*
	* @package com.Itschi.ACP.user
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zur&uml;ck');
		exit;
	}

	$error = '';
	$update_data = array();

	$res = $db->query('
		SELECT *
		FROM ' . USERS_TABLE . '
		WHERE user_id = ' . (int)$_GET['id']
	);

	$row = $db->fetch_array($res);
	$db->free_result($res);

	if (!$row) {
		message_box('Das Mitglied existiert nicht', 'index.php', 'zurück');
		exit;
	}

	if (isset($_GET['unlock'])) {
		$db->query('
			UPDATE ' . USERS_TABLE . "
			SET user_unlock = ''
			WHERE user_id = " . $row['user_id']
		);

		$row['user_unlock'] = '';

		$error = 8;
	}

	if (isset($_POST['submit'])) {
		// require_once '../lib/functions/user.php';
		$fUser = functions::user();

		if ($_POST['username'] != $row['username']) {
			if (!$fUser->valid_username($_POST['username'])) {
				$error = 1;
			} else if (strlen($_POST['username']) < 3 || strlen($_POST['username']) > 15) {
				$error = 2;
			} else if ($fUser->username_exists($_POST['username'])) {
				$error = 3;
			} else {
				if ($config['newest_user_id'] == $row['user_id']) {
					config_set('newest_username', $_POST['username']);
				}

				$db->query('UPDATE ' . FORUMS_TABLE . " SET forum_last_post_username = '" . $_POST['username'] . "' WHERE forum_last_post_user_id = " . $row['user_id']);
				$db->query('UPDATE ' . TOPICS_TABLE . " SET username = '" . $_POST['username'] . "' WHERE user_id = " . $row['user_id']);
				$db->query('UPDATE ' . TOPICS_TABLE . " SET topic_last_post_username = '" . $_POST['username'] . "' WHERE topic_last_post_user_id = " . $row['user_id']);
				$db->query('UPDATE ' . POSTS_TABLE . " SET post_edit_username = '" . $_POST['username'] . "' WHERE post_edit_user_id = " . $row['user_id']);

				$update_data['username'] = $_POST['username'];
			}
		}

		if ($_POST['email'] != $row['user_email']) {
			if (!$fUser->valid_email($_POST['email'])) {
				$error = 4;
			}
			else if ($fUser->email_exists($_POST['email'])) {
				$error = 5;
			} else {
				$update_data['user_email'] = $_POST['email'];
			}
		}

		if ($_POST['password'] && $_POST['password2'] && $_POST['password'] == $_POST['password2']) {
			if (strlen($_POST['password']) < 6) {
				$error = 6;
			} else {
				$update_data['user_password'] = md5($_POST['password']);
			}
		}

		if ($_POST['level'] != $row['user_level']) {
			$db->query('UPDATE ' . FORUMS_TABLE . ' SET forum_last_post_user_level = ' . (int)$_POST['level'] . ' WHERE forum_last_post_user_id = ' . $row['user_id']);
			$db->query('UPDATE ' . TOPICS_TABLE . ' SET user_level = ' . (int)$_POST['level'] . ' WHERE user_id = ' . $row['user_id']);
			$db->query('UPDATE ' . TOPICS_TABLE . ' SET topic_last_post_user_level = ' . (int)$_POST['level'] . ' WHERE topic_last_post_user_id = ' . $row['user_id']);
			$db->query('UPDATE ' . POSTS_TABLE . ' SET post_edit_user_level = ' . (int)$_POST['level'] . ' WHERE post_edit_user_id = ' . $row['user_id']);

		}

		$update_data = array_merge($update_data, array(
			'user_level'	=>	(($_GET['id'] == 1) ? ADMIN : (int)$_POST['level']),
			'user_website'	=>	trim($_POST['website']),
			'user_icq'	=>	trim($_POST['icq']),
			'user_skype'	=>	trim($_POST['skype']),
			'user_signatur'	=>	strip(trim($_POST['signatur'])),
			'user_rank'	=>	(int)$_POST['rank'],
			'user_points'	=>	(int)$_POST['points']
		));

		if (!empty($_POST['avatar']) && $row['user_avatar']) {
			@unlink('../images/avatar/' . $row['user_avatar']);
			@unlink('../images/avatar/mini/' . $row['user_avatar']);

			$update_data['user_avatar'] = '';
		}

		$data = '';

		foreach ($update_data as $colum => $var) {
			$data .= (($data) ? ', ' : '') . $colum . ' = \'' . $db->chars($var) . '\'';
		}

		$db->query('
			UPDATE ' . USERS_TABLE . '
			SET ' . $data . '
			WHERE user_id = ' . $row['user_id']
		);

		if (!$error) {
			$error = 7;
		}

		$row = array_merge($row, $update_data);
	}

	$res = $db->query('
		SELECT *
		FROM ' . RANKS_TABLE . '
		WHERE rank_special = 1
		ORDER BY rank_title ASC
	');

	while ($row2 = $db->fetch_array($res)) {
		template::assignBlock('ranks', array(
			'ID'	=>	$row2['rank_id'],
			'TITLE'	=>	$row2['rank_title']
		));
	}

	$db->free_result($res);

	template::assign(array(
		'MENU_BUTTON'	=>	3,
		'ERROR'		=>	$error,
		'ID'		=>	$row['user_id'],
		'USER_USERNAME'	=>	$row['username'],
		'AVATAR'	=>	($row['user_avatar']) ? $row['user_avatar'] : $config['default_avatar'],
		'EMAIL'		=>	htmlspecialchars($row['user_email']),
		'WEBSITE'	=>	htmlspecialchars($row['user_website']),
		'ICQ'		=>	htmlspecialchars($row['user_icq']),
		'SKYPE'		=>	htmlspecialchars($row['user_skype']),
		'SIGNATUR'	=>	htmlspecialchars($row['user_signatur']),
		'LEVEL'		=>	$row['user_level'],
		'LEVEL_USER'	=>	USER,
		'LEVEL_MOD'	=>	MOD,
		'LEVEL_ADMIN'	=>	ADMIN,
		'IP'		=>	$row['user_ip'],
		'POINTS'	=>	$row['user_points'],
		'RANK'		=>	$row['user_rank']
	));

	template::display('user');
?>