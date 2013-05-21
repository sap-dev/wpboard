<?php
	/**
	*
	* @package com.Itschi.mail
	* @since 2007/05/25
	*
	*/

	require 'base.php';

	template::assign('TITLE_TAG', 'Mail | ');

	if (!$user->row) {
		login_box();
		exit;
	}

	$dir =	(isset($_GET['dir']) && $_GET['dir'] == 2) ? 2 : 1;
	$mode =	(isset($_GET['mode'])) ? $_GET['mode'] : '';

	$res = $db->query('
		SELECT COUNT(*)
		FROM ' . MAILS_TABLE . '
		WHERE ' . (($dir == 2) ? '' : 'to_') . 'user_id = ' . $user->row['user_id']
	);

	$mail_num = $db->result($res, 0);
	$db->free_result($res);

	switch ($mode) {
		case 'view':

			$res = $db->query('
				SELECT m.*, u.username, u.user_id, u.user_rank, u.user_avatar, u.user_signatur, u.user_signatur_bbcodes, u.user_signatur_smilies, u.user_signatur_urls, u.user_posts, u.user_points, u.user_level
				FROM ' . MAILS_TABLE . ' m
					LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = m.user_id
				WHERE m.mail_id = ' . (int)$_GET['id']
			);

			$row = $db->fetch_array($res);
			$db->free_result($res);

			if (!$row || ($row['to_user_id'] != $user->row['user_id'] && $row['user_id'] != $user->row['user_id'])) {
				message_box('Die Nachricht existiert nicht', 'mail.php', 'zurück');
			}

			if ($row['mail_read'] == 0 && $row['to_user_id'] == $user->row['user_id']) {
				$db->query('
					UPDATE ' . USERS_TABLE . '
					SET user_mails = user_mails - 1
					WHERE user_id = ' . $user->row['user_id']
				);

				$db->query('
					UPDATE ' . MAILS_TABLE . '
					SET mail_read = 1
					WHERE mail_id = ' . $row['mail_id']
				);
			}

			template::assign(array(
				'DIR'			=>	$dir,
				'MAIL_USERNAME'		=>	$row['username'],
				'MAIL_USER_ID'		=>	$row['user_id'],
				'MAIL_USER_AVATAR'	=>	($row['user_avatar']) ? $row['user_avatar'] : $config['default_avatar'],
				'MAIL_USER_POSTS'	=>	number_format($row['user_posts'], 0, '', '.'),
				'MAIL_USER_RANK'	=>	$user->rank($row['user_id'], $row['user_rank'], $row['user_posts']),
				'MAIL_USER_RANK_ICON'	=>	$user->rank_icon($row['user_id'], $row['user_rank'], $row['user_posts']),
				'MAIL_USER_LEGEND'	=>	$user->legend($row['user_level']),
				'MAIL_ID'		=>	$row['mail_id'],
				'MAIL_TITLE'		=>	htmlspecialchars($row['mail_title']),
				'MAIL_TIME'		=>	date('d.m.y H:i', $row['mail_time']),
				'MAIL_TEXT'		=>	replace($row['mail_text'], $row['enable_bbcodes'], $row['enable_smilies'], $row['enable_urls']),
				'SIGNATUR'		=>	($row['enable_signatur'] && $row['user_signatur']) ? replace($row['user_signatur'], $row['user_signatur_bbcodes'], $row['user_signatur_smilies'], $row['user_signatur_urls']) : false,
				'DEFAULT_AVATAR'	=>	$config['default_avatar']
			));

			template::display('mail_view');

		break;

		case 'new':

			$error = '';
			$to_user = (isset($_POST['user'])) ? $_POST['user'] : '';
			$title = (isset($_POST['title'])) ? strip($_POST['title']) : '';
			$text = (isset($_POST['text'])) ? strip($_POST['text']) : '';
			$bbcodes = (isset($_POST['bbcodes'])) ? 1 : 0;
			$smilies = (isset($_POST['smilies'])) ? 1 : 0;
			$urls = (isset($_POST['urls'])) ? 1 : 0;
			$submit = (isset($_POST['submit'])) ? true : false;
			$preview = (isset($_POST['preview'])) ? replace(trim($text), !$bbcodes, !$smilies, !$urls) : '';
			$signatur = (!$submit && !$preview || !empty($_POST['signatur'])) ? 1 : 0;
			$answer = (isset($_GET['answer'])) ? $_GET['answer'] : 0;
			$user_id = (isset($_GET['id'])) ? $_GET['id'] : 0;
			$quote = (isset($_GET['quote'])) ? $_GET['quote'] : 0;

			if ($answer) {
				$res = $db->query('
					SELECT mail_title
					FROM ' . MAILS_TABLE . '
					WHERE mail_id = ' . (int)$answer . '
					AND to_user_id = ' . $user->row['user_id']
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row) {
					$title = ((preg_match('/Re:/', $row['mail_title'])) ? '' : 'Re: ') . $row['mail_title'];
				}
			}

			if ($user_id) {
				$res = $db->query('
					SELECT username
					FROM ' . USERS_TABLE . '
					WHERE user_id = ' . (int)$user_id
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row) {
					$to_user = $row['username'];
				}
			}

			if ($quote && !$text) {
				$res = $db->query('
					SELECT u.username, m.mail_text
					FROM ' . MAILS_TABLE . ' m, ' . USERS_TABLE . ' u
					WHERE m.mail_id = ' . (int)$quote . '
						AND m.to_user_id = ' . $user->row['user_id'] . '
						AND u.user_id = m.user_id
				');

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row) {
					$text = '[quote=' . $row['username'] . ']' . $row['mail_text'] . '[/quote]';
				}
			}

			if ($submit) {
				$res = $db->query('
					SELECT user_id
					FROM ' . USERS_TABLE . "
					WHERE username = '" . $db->chars($to_user) . "'
				");

				$row = $db->fetch_array($res);
				$db->free_result($res);


				function mails($colum, $user_id) {
					global $db;

					$res = $db->query(' 
						SELECT COUNT(*)
						FROM ' . MAILS_TABLE . '
						WHERE ' . $colum . 'user_id = ' . $user_id
					);

					$row = $db->result($res, 0);
					$db->free_result($res);

					return $row;
				}

				$res2 = $db->query('
					SELECT COUNT(*)
					FROM ' . MAILS_TABLE . '
					WHERE user_id = ' . $user->row['user_id'] . '
						AND mail_time > ' . strtotime('-1 day')
				);

				$row2 = $db->result($res2, 0);
				$db->free_result($res2);

				if ($row2 >= $config['posts_perday']) {
					$error = 1;
				} else if (!$row['user_id']) {
					$error = 2;
				} else if (!$title || !$text) {
					$error = 3;
				} else if (mails('to_', (int)$row['user_id']) >= $config['mail_limit']) {
					$error = 4;
				} else if (mails('', $user->row['user_id']) >= $config['mail_limit']) {
					$error = 5;
				} else if ($config['max_post_chars'] && strlen($text) > $config['max_post_chars']) {
					$error = 6;
				} else {
					$db->query('
						INSERT INTO ' . MAILS_TABLE . "
						(mail_title, mail_text, user_id, to_user_id, mail_time, enable_bbcodes, enable_smilies, enable_urls, enable_signatur) VALUES
						('" . $db->chars(trim($title)) . "', '" . $db->chars(trim($text)) . "', " . $user->row['user_id'] . ', ' . $row['user_id'] . ', ' . time() . ', ' . (int)!$bbcodes . ', ' . (int)!$smilies . ', ' . (int)!$urls . ', ' . (int)$signatur . ')
					');

					$db->query('
						UPDATE ' . USERS_TABLE . '
						SET user_mails = user_mails + 1
						WHERE user_id = ' . $row['user_id']
					);

					message_box('Die E-Mail wurde gesendet', 'mail.php', 'weiter zum Postfach', '', '', 3);
				}
			}

			$smilies_gp = $cache->get('smilies_group');

			foreach ($smilies_gp as $row) {
				template::assignBlock('smilies', array(
					'EMOTION'	=>	$row['smilie_emotion'],
					'IMAGE'		=>	$row['smilie_image']
				));
			}

			template::assign(array(
				'ERROR'		=>	$error,
				'DIR'		=>	$dir,
				'BBCODES'	=>	$bbcodes,
				'SMILIES'	=>	$smilies,
				'SIGNATUR'	=>	$signatur,
				'URLS'		=>	$urls,
				'PREVIEW'	=>	$preview,
				'TEXT'		=>	htmlspecialchars(trim($text)),
				'TITLE'		=>	htmlspecialchars(trim($title)),
				'TO_USER'	=>	htmlspecialchars(trim($to_user)),
				'PERDAY'	=>	$config['posts_perday'],
				'MAX_CHARS'	=>	$config['max_post_chars']
			));

			template::display('mail_new');

		break;


		default:

			function delete_mail($mail_id) {
				global $db, $user;

				$res = $db->query('
					SELECT mail_id, mail_read, to_user_id, user_id
					FROM ' . MAILS_TABLE . '
					WHERE mail_id = ' . (int)$mail_id
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row['to_user_id'] == $user->row['user_id'] || $row['user_id'] == $user->row['user_id']) {
					if ($row['to_user_id'] == $user->row['user_id']) {
						$user->row['user_mails']--;
					}

					if ($row['mail_read'] == 0) {
						$db->query('
							UPDATE ' . USERS_TABLE . '
							SET user_mails = user_mails - 1
							WHERE user_id = ' . $row['to_user_id']
						);
					}

					$db->query('
						DELETE FROM ' . MAILS_TABLE . '
						WHERE mail_id = ' . $row['mail_id']
					);

					return true;
				}

				return false;
			}

			$error = '';

			if ($mail_num >= $config['mail_limit']) {
				$error = 3;
			}

			if ($mode == 'delete') {
				if (delete_mail($_GET['id'])) {
					$error = 1;
				}
			}

			if ($_POST) {
				foreach ($_POST as $key => $value) {
					if ($_POST[$key] == 'true') {
						if (delete_mail($key)) {
							$error = 2;
						}
					}
				}
			}

			$perpage = 20;
			$page = (isset($_GET['page'])) ? max($_GET['page'], 1) : 1;
			$pages_num = ceil($mail_num/$perpage);

			$res = $db->query('
				SELECT m.*, u.username, u.user_level
				FROM ' . MAILS_TABLE . ' m
					LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = m.' . (($dir == 2) ? 'to_' : '') . 'user_id
				WHERE m.' . (($dir == 2) ? '' : 'to_') . 'user_id = ' . $user->row['user_id'] . '
				ORDER BY m.mail_id DESC
				LIMIT ' . ($page * $perpage - $perpage) . ',' . $perpage
			);

			while ($row = $db->fetch_array($res)) {
				template::assignBlock('mails', array(
					'ID'		=>	$row['mail_id'],
					'TITLE'		=>	htmlspecialchars($row['mail_title']),
					'TIME'		=>	date('d.m.y H:i', $row['mail_time']),
					'USERNAME'	=>	$row['username'],
					'READ'		=>	$row['mail_read'],
					'USER_ID'	=>	$row['user_id'],
					'USER_LEGEND'	=>	$user->legend($row['user_level'])
				));
			}

			$db->free_result($res);

			template::assign(array(
				'DIR'		=>	$dir,
				'ERROR'		=>	$error,
				'MAILS_NUM'	=>	$mail_num,
				'PAGE'		=>	$page,
				'PAGES_NUM'	=>	$pages_num,
				'PAGES'		=>	($pages_num > 1) ? pages($pages_num, $page, 'mail.php?dir=' . $dir . '&page=') : ''
			));

			template::display('mail');

		break;
	}
	?>
