<?php
	/**
	*
	* @package com.Itschi.login
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	$error = '';
	$redirect = (isset($_POST['redirect'])) ? strip($_POST['redirect']) : 'index.php';

	if (isset($_GET['logout'])) {
		template::assign(array(
			'TIME'		=>	round((time() - $user->row['user_login'])/60, 0),
			'TIME_HI'	=>	date('H:i')
		));

		if ($user->logout()) {
			$error = 2;
		}
	} else if (!empty($_POST['username']) && !empty($_POST['password'])) {
		if ($user->row) {
			header("Location: ./index.php");
			exit;
		}

		if (!$user->login($_POST['username'], md5($_POST['password']), isset($_POST['merke']), $redirect)) {
			$error = 1;
		}
	}

	template::assign(array(
		'TITLE_TAG'	=>	'Login | ',
		'REDIRECT'	=>	$redirect,
		'ERROR'		=>	$error
	));

	template::display('login');
?>
