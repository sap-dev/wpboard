<?php
	/**
	*
	* @package com.Itschi.ACP.smilies.new
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
	$emotion = (isset($_POST['emotion'])) ? strip($_POST['emotion']) : '';
	$smilie = (isset($_POST['smilie'])) ? strip($_POST['smilie']) : '';

	if ($emotion && $smilie) {
		if ($id) {
			$db->query('
				UPDATE ' . SMILIES_TABLE . "
				SET	smilie_emotion	=	'" . $db->chars($emotion) . "',
					smilie_image	=	'" . $db->chars($smilie) . "'
				WHERE smilie_id = " . $id
			);
		} else {
			$db->query('
				INSERT INTO ' . SMILIES_TABLE . "
				(smilie_emotion, smilie_image) VALUES
				('" . $db->chars($emotion) . "', '" . $db->chars($smilie) . "')
			");
		}

		$cache->delete('smilies');
		$cache->delete('smilies_group');

		header('Location: smilies.php');
	} else if (!isset($_POST['submit']) && $id) {
		$res = $db->query('
			SELECT *
			FROM ' . SMILIES_TABLE . '
			WHERE smilie_id = ' . $id
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row['smilie_id']) {
			message_box('Der Smilie existiert nicht!', 'smilies.php', 'zurück');
			exit;
		}

		$emotion = $row['smilie_emotion'];
		$smilie = $row['smilie_image'];
	}

	$extensions = array('png', 'jpg', 'jpeg', 'gif', 'bmp');

	if ($handle = @opendir('../images/smilies/')) {
		while (($file = readdir($handle)) !== false) {
			$ext = explode('.', $file);

			if (count($ext) == 1) continue;

			$ext = end($ext);
			$ext = strtolower($ext);

			if ($file != '.' && $file != '..' && !is_dir('../images/smilies/' . $file) && in_array($ext, $extensions)) {
				template::assignBlock('smilies', array(
					'SMILIE'	=>	$file
				));
			}
		}

		closedir($handle);
	}

	template::assign(array(
		'MENU_BUTTON'	=>	7,
		'ID'		=>	$id,
		'EMOTION'	=>	$emotion,
		'SMILIE'	=>	$smilie
	));

	template::display('smilie-new', true);
?>