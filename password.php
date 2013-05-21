<?php
	/**
	*
	* @package com.Itschi.passwordRecovery
	* @since 2007/05/25
	* 
	* TODO: Sicherer machen
	*
	*/

	require 'base.php';

	if ($user->row) header("Location: ./index.php");

	$error = '';
	$username = (isset($_POST['user'])) ? $_POST['user'] : '';
	$email = (isset($_POST['email'])) ? $_POST['email'] : '';

	if (isset($_GET['mode'])) {
		$res = $db->query('
			SELECT user_id, username, user_email, user_password
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int)$_GET['id']
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if ($_GET['key'] == md5($row['user_password'])) {
			$db->query('
				UPDATE ' . USERS_TABLE . "
				SET user_password = '" . $db->chars(md5(base64_decode(urldecode($_GET['p'])))) . "'
				WHERE user_id = " . $row['user_id']
			);

			mail($row['user_email'], 'Neues Passwort ' . $row['username'], "Hallo " . $row['username'] . ", \n \n dein neues Passwort wurde gespeichert! \n Deine neuen Zugangsdaten lauten: \n \n Benutzername: " . $row['username'] . " \n Passwort: " . base64_decode(urldecode($_GET['p'])) . " \n \n ", 'from:' . $config['email']);

			message_box('Das neue Passwort wurde gespeichert', 'login.php', 'weiter zum Login');
		} else {
			$error = 1;
		}
	}

	if (isset($_POST['send'])) {
		$res = $db->query('
			SELECT user_id, username, user_email, user_password
			FROM ' . USERS_TABLE . "
			WHERE username = '" . $db->chars($username) . "'
				AND user_email = '" . $db->chars($email) . "'
		");

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row) {
			$error = 2;
		} else if (strlen($_POST['pw']) < 3) {
			$error = 3;
		} else if ($_POST['pw'] != $_POST['pw2']) {
			$error = 4;
		} else {
			mail($row['user_email'], 'Passwort neu setzen', "Hallo " . $row['username'] . ", \n \n du hast dein Passwort vergessen? Wenn nicht ignoriere diese Mail. \n \n Klicke auf den folgenden Link um dein Passwort neu zu setzten: \n \n http://" . $_SERVER['HTTP_HOST'] . (dirname($_SERVER['PHP_SELF']) == '/' ? '' : dirname($_SERVER['PHP_SELF'])) . "/password.php?mode=set&id=" . $row['user_id'] . "&p=" . urlencode(base64_encode($_POST['pw'])) . "&key=" . md5($row['user_password']) . " \n \n ", 'from:' . $config['email']);

			message_box('Ein Bestätigungslink wurde an deine E-Mail geschickt', 'index.php', 'weiter zur Startseite');
		}
	}

	template::assign(array(
		'TITLE_TAG'	=>	'Passwort vegessen? | ',
		'ERROR'		=>	$error
	));

	template::display('password');
?>
