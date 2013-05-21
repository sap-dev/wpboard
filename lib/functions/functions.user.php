<?php
	/**
	*
	* @package com.Itschi.base.functions.user
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib\functions;

	class user extends \functions {
		public function valid_email($email) {
			return preg_match('#^[a-z0-9_.-]+@([a-z0-9_.-]+\.)+[a-z]{2,4}$#si', $email);
		}

		public function valid_username($username) {
			return preg_match('#^[a-z]{1,2}[a-z0-9-_]+$#i', $username);
		}

		public function username_exists($username) {
			global $db;

			$res = $db->query('
				SELECT user_id
				FROM ' . USERS_TABLE . "
				WHERE username = '" . $username . "'
			");

			$row = $db->fetch_array($res);
			$db->free_result($res);

			return ($row['user_id']) ? true : false;
		}

		public function email_exists($email) {
			global $db;

			$res = $db->query('
				SELECT user_id
				FROM ' . USERS_TABLE . "
				WHERE user_email = '" . $db->chars($email) . "'
			");

			$row = $db->fetch_array($res);
			$db->free_result($res);

			return ($row['user_id']) ? true : false;
		}

		public function unlock_id() {
			$length = 6;
			$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
			$chars_num = strlen($chars) - 1;
			$code = '';

			while ($length--) {
				srand((double)microtime() * 1000000);

				$char = $chars{rand(0, $chars_num)};
				$code .= (rand(0, 2)) ? $char : strtoupper($char);
			}

			return $code;
		}

		public function delete_user($user_id) {
			global $db, $user, $config;

			if ($user->row['user_id'] == $user_id) {
				$row = $user->row;
			} else {
				$res = $db->query('

					SELECT user_id, user_avatar, user_email, user_ip, user_unlock
					FROM ' . USERS_TABLE . '
					WHERE user_id = ' . (int)$user_id
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);
			}

			if (!$row) {
				return false;
			}

			$db->unbuffered_query('DELETE FROM ' . USERS_TABLE . ' WHERE user_id = ' . $row['user_id']);
			$db->unbuffered_query('DELETE FROM ' . MAILS_TABLE . ' WHERE to_user_id = ' . $row['user_id']);
			$db->unbuffered_query('DELETE FROM ' . TOPICS_TRACK_TABLE . ' WHERE user_id = ' . $row['user_id']);
			$db->unbuffered_query('DELETE FROM ' . FORUMS_TRACK_TABLE . ' WHERE user_id = ' . $row['user_id']);
			$db->unbuffered_query('DELETE FROM ' . BANLIST_TABLE . ' WHERE user_id = ' . $row['user_id']);

			$db->unbuffered_query('UPDATE ' . FORUMS_TABLE . " SET forum_last_post_username = '', forum_last_post_user_id = 0, forum_last_post_user_level = 0 WHERE forum_last_post_user_id = " . $row['user_id']);
			$db->unbuffered_query('UPDATE ' . TOPICS_TABLE . " SET username = '', user_id = 0, user_level = 0 WHERE user_id = " . $row['user_id']);
			$db->unbuffered_query('UPDATE ' . TOPICS_TABLE . " SET topic_last_post_username = '', topic_last_post_user_id = 0, topic_last_post_user_level = 0 WHERE topic_last_post_user_id = " . $row['user_id']);
			$db->unbuffered_query('UPDATE ' . POSTS_TABLE . " SET post_edit_username = '', post_edit_user_id = 0, post_edit_user_level = 0 WHERE post_edit_user_id = " . $row['user_id']);

			if ($row['user_avatar']) {
				@unlink('/images/avatar/' . $row['user_avatar']);
			}

			if ($config['newest_user_id'] == $user_id) {
				$res = $db->query('
					SELECT user_id, username, user_level
					FROM ' . USERS_TABLE . "
					WHERE user_unlock = ''
					ORDER BY user_id DESC LIMIT 1
				");

				$row = $db->fetch_array($res);
				$db->free_result($res);

				config_set('newest_user_id', $row['user_id']);
				config_set('newest_username', $row['username']);
				config_set('newest_user_level', $row['user_level']);
			}

			if (!$row['user_unlock']) {
				config_set_count('users_num', -1);
			}

			if ($user->row['user_id'] == $row['user_id']) {
				$user->logout();

				message_box('Du hast Deine Mitgliedsschaft beendet', 'index.php', 'weiter zur Startseite');
			}
		}
	}
?>