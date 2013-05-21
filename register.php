<?php
	/**
	*
	* @package com.Itschi.register
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	/**
	* Session is required for the Captcha
	*/
	session_start();

	if ($user->row) header("Location: ./index.php");

	$error = '';
	$deny =	array();
	$username =	(isset($_POST['username'])) ? $_POST['username'] : '';
	$email = (isset($_POST['email'])) ? $_POST['email'] : '';
	$password =	(isset($_POST['password'])) ? $_POST['password'] : '';
	$password2 = (isset($_POST['password2'])) ? $_POST['password2'] : '';

	if (isset($_POST['submit'])) {
		// include 'lib/functions/user.php';
		$fUser = functions::user();

		if (!$username) {
			$error = 1;
		} else if (!$fUser->valid_username($username)) {
			$error = 2;
		} else if (strlen($username) < 3 || strlen($username) > 15) {
			$error = 3;
		} else if (in_array(strtolower($username), $deny)) {
			$error = 4;
		} else if ($fUser->username_exists($username)) {
			$error = 5;
		} else if (!$fUser->valid_email($email)) {
			$error = 6;
		} else if ($fUser->email_exists($email)) {
			$error = 7;
		} else if (strlen($password) < 6) {
			$error = 8;
		} else if ($password != $password2) {
			$error = 9;
		} else if ($config['enable_captcha'] && md5(strtolower($_POST['captcha'])) != $_SESSION['sicherheitscode']) {
			$error = 10;
		} else {
			if ($config['unlock_delete'] != 0) {
				$db->query('
					DELETE FROM ' . USERS_TABLE . '
					WHERE user_register < ' . (time() - $config['unlock_delete']*24*3600) . "
					AND user_unlock <> ''
				");
			}

			$unlock_id = $fUser->unlock_id();
			
			if ($config['enable_unlock']) {
				@mail($email, 'Best�tige Deine E-Mail', "Hallo " . $username . ", \n \n Du musst Deine E-Mail best�tigen, um die Registrierung abzuschlie�en. \n \n Klicke dazu auf folgenden Link: \n \n http://" . $_SERVER['HTTP_HOST'] . (dirname($_SERVER['PHP_SELF']) == '/' ? '' : dirname($_SERVER['PHP_SELF'])) . "/register.php?u=" . $user_id . "&token=" . $unlock_id . " \n \n \n Viel Spa� weiterhin! \n \n ", 'from:' . $config['email']);
			} else {
				config_vars();
				config_set('users_num', $config['users_num']+1);
				
				$_REQUEST['token'] = $unlock_id;
				$unlock_id = '';
			}

			$db->query('
				INSERT INTO ' . USERS_TABLE . "
				(user_unlock, username, user_password, user_email, user_register, user_ip) VALUES
				('" . $unlock_id . "', '" . $db->chars($username) . "', '" . md5($password) . "', '" . $db->chars($email) . "', " . time() . ", '" . $_SERVER['REMOTE_ADDR'] . "')
			");

			$user_id = $db->insert_id();

			header('Location: register.php?u=' . $user_id);
			exit;
		}
	}

	if (empty($_GET['u'])) {
		$username = htmlspecialchars($username);
		$email = htmlspecialchars($email);
		$password = htmlspecialchars($password);

		template::assign(array(
			'TITLE_TAG'		=>	'Registrieren | ',
			'ERROR'			=>	$error,
			'USER_USERNAME'		=>	$username,
			'EMAIL'			=>	$email,
			'PASSWORD'		=>	$password,
			'PASSWORD2'		=>	$password2,
			'ENABLE_CAPTCHA'	=>	$config['enable_captcha'],
			'ENABLE_UNLOCK'		=>	$config['enable_unlock']
		));

		template::display('register');
	} else {
		$error = '';
		$token = (isset($_REQUEST['token'])) ? $_REQUEST['token'] : '';

		$res = $db->query('
			SELECT user_id, username, user_unlock, user_email, user_password
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int)$_GET['u']
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row['user_id']) {
			message_box('Das Mitglied wurde nicht gefunden', 'forum.php', 'zurück zum Forum');
		}

		if (!$row['user_unlock']) {
			message_box('Du kannst dich jetzt einloggen', 'login.php', 'weiter zum Login');
		}

		if ($token) {
			if ($token == $row['user_unlock']) {
				$db->query('
					UPDATE ' . USERS_TABLE . "
					SET user_unlock = ''
					WHERE user_id = " . $row['user_id']
				);

				config_set('newest_user_id', $row['user_id']);
				config_set('newest_username', $row['username']);
				config_set('newest_user_level', 0);
				config_set_count('users_num', +1);

				@mail($row['user_email'], $row['username'] . ' -  Du bist jetzt ein Mitglied!', "Hallo " . $row['username'] . "! \n \n Du bist jetzt ein Mitglied der Community. \n \n \n \n Viel Spa� weiterhin! \n \n ", 'from:' . $config['email']);

				message_box('Du hast dich erfolgreich registriert', 'login.php', 'weiter zum Login');
			} else {
				$error = 1;
			}
		}

		template::assign(array(
			'TITLE_TAG'	=>	'Registrieren | ',
			'TOKEN'		=>	htmlspecialchars($token),
			'ERROR'		=>	$error,
			'LOCK_USER_ID'	=>	$row['user_id'],
			'LOCK_USERNAME'	=>	$row['username']
		));

		template::display('register_unlock');
	}
?>
