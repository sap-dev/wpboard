<?php
	/**
	*
	* @package Itschi
	* @since 2007/05/25
	*
	*/

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}

	$error = '';
	$ban_id = (isset($_GET['id'])) ? (int)$_GET['id'] : 0;
	$username = (isset($_POST['user'])) ? $_POST['user'] : '';
	$reason = (isset($_POST['reason'])) ? strip(trim($_POST['reason'])) : '';
	$day = (isset($_POST['day'])) ? (int)$_POST['day'] : date('d');
	$month = (isset($_POST['month'])) ? (int)$_POST['month'] : date('m');
	$year = (isset($_POST['year'])) ? (int)$_POST['year'] : date('Y');
	$hour = (isset($_POST['hour'])) ? (int)$_POST['hour'] : date('H');
	$min = (isset($_POST['min'])) ? (int)$_POST['min'] : date('i');

	if (isset($_POST['submit'])) {
		if (!$username || !$reason) {
			$error = 1;
		} else if (($time = mktime($hour, $min, 0, $month, $day, $year)) <= time()) {
			$error = 2;
		} else if ($username == $user->row['username']) {
			$error = 3;
		} else {
			$res = $db->query('
				SELECT user_id, user_level
				FROM ' . USERS_TABLE . "
				WHERE username = '" . $db->chars($username) . "'
			");

			$row = $db->fetch_array($res);
			$db->free_result($res);

			$user_id = (int)$row['user_id'];

			if (!$user_id) {
				$error = 4;
			} else if (($row['user_level'] == ADMIN || $row['user_level'] == MOD) && $user->row['user_level'] != ADMIN) {
				$error = 5;
			} else if (!$ban_id) {
				$res = $db->query('
					SELECT ban_id
					FROM ' . BANLIST_TABLE . '
					WHERE user_id = ' . $user_id
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row['ban_id']) {
					$error = 6;
				}
			}
		}

		if (!$error) {
			function set_ban($ban, $user_id) {
				global $db;

				$db->query('
					UPDATE ' . USERS_TABLE . '
					SET user_ban = ' . $ban . '
					WHERE user_id = ' . $user_id
				);
			}

			if ($ban_id) {
				$res = $db->query('
					SELECT user_id
					FROM ' . BANLIST_TABLE . '
					WHERE ban_id = ' . $ban_id
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row['user_id'] != $user_id) {
					set_ban(0, $row['user_id']);
					set_ban(1, $user_id);
				}

				$db->query('

					UPDATE ' . BANLIST_TABLE . '
					SET	ban_time = ' . $time . ',
						user_id = ' . $user_id . ",
						ban_reason = '" . $db->chars($reason) . "'
					WHERE ban_id = " . $ban_id
				);
			} else {
				$db->query('
					INSERT INTO ' . BANLIST_TABLE . '
					(ban_time, user_id, ban_reason, by_id) VALUES
					(' . $time . ', ' . $user_id . ", '" . $db->chars($reason) . "', '" . $user->row['user_id'] . "')
				");

				set_ban(1, $user_id);
			}

			header('Location: banlist.php');
		}
	} else if ($ban_id) {
		$res = $db->query('
			SELECT b.*, u.username
			FROM ' . BANLIST_TABLE . ' b
				LEFT JOIN ' . USERS_TABLE . ' u ON u.user_id = b.user_id
			WHERE b.ban_id = ' . $ban_id
		);

		$row = $db->fetch_array($res);
		$db->free_result($res);

		if (!$row['ban_id']) {
			message_box('Der Eintrag existiert nicht', 'banlist.php', 'zurück');
			exit;
		}

		$username = $row['username'];
		$reason = $row['ban_reason'];
		$day = date('d', $row['ban_time']);
		$month = date('m', $row['ban_time']);
		$year = date('Y', $row['ban_time']);
		$hour = date('H', $row['ban_time']);
		$min = date('i', $row['ban_time']);
	}

	template::assign(array(
		'MENU_BUTTON'	=>	4,
		'ERROR'		=>	$error,
		'ID'		=>	$ban_id,
		'YEAR'		=>	$year,
		'BAN_USERNAME'	=>	htmlspecialchars($username),
		'REASON'	=>	htmlspecialchars($reason),
		'DAY'		=>	(strlen($day) == 2) ? $day : '0' . $day,
		'MONTH'		=>	(strlen($month) == 2) ? $month : '0' . $month,
		'HOUR'		=>	(strlen($hour) == 2) ? $hour : '0' . $hour,
		'MIN'		=>	(strlen($min) == 2) ? $min : '0' . $min
	));

	template::display('banlist-new', true);
?>