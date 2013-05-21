<?php
	/**
	*
	* @package com.Itschi.base.user
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib;

	class user {
		public  $row = false;
		private $session = 'forum_sid';
		private $session_max_lifetime = 7200;
		private $cookie_auto_username = 'auto_username';
		private $cookie_auto_password = 'auto_password';
		private $cookie_auto_lifetime = 2678400;
		private $ranks = false;
		private $ranks_cache = array();

		public function __construct() {
			global $db, $prefix;

			$this->session 			= $prefix . $this->session;
			$this->cookie_auto_username	= $prefix . $this->cookie_auto_username;
			$this->cookie_auto_password	= $prefix . $this->cookie_auto_password;

			$session_user_id = $this->getSession();

			if ($session_user_id) {
				$this->row = array('user_id' => $session_user_id);
				$this->update_vars();

				$db->query('
					UPDATE ' . USERS_TABLE . '
					SET user_lastvisit = ' . time() . '
					WHERE user_id = ' . (int)$this->row['user_id']
				);

				if ($this->row['user_ban']) {
					$this->check_ban();
				}
			} else {
				if (isset($_COOKIE[$this->cookie_auto_username]) && isset($_COOKIE[$this->cookie_auto_password])) {
					$this->login(
						$_COOKIE[$this->cookie_auto_username],
						$_COOKIE[$this->cookie_auto_password]
					);
				}
			}

			$this->online_global();

			$db->query('
				DELETE
				FROM ' . ONLINE_TABLE . '
				WHERE online_lastvisit < ' . (time() - 300)
			);
		}

		private function setSession($user_id) {
			global $db;

			$res = $db->query('
				DELETE FROM ' . SESSIONS_TABLE . '
				WHERE session_time < ' . (time() - $this->session_max_lifetime)
			);

			do {
				$length = 20;
				$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
				$chars_num = strlen($chars) - 1;
				$session_id = '';
				while ($length--) {
					srand((double)microtime() * 1000000);

					$session_id .= $chars{rand(0, $chars_num)};
				}
			} while ($this->getSession($session_id, false) !== false);

			$res = $db->query('
				INSERT INTO ' . SESSIONS_TABLE . "
				SET	session_id = '" . $db->chars($session_id) . "',
					session_time = " . time() . ',
					user_id = ' . $user_id
			);

			setCookie($this->session, $session_id, 0, '/');
		}

		private function getSession($session_id = false, $update_time = true) {
			if (!$session_id) {
				$session_id = (isset($_COOKIE[$this->session])) ? $_COOKIE[$this->session] : '';

				if (!$session_id) {
					return false;
				}
			}

			global $db;

			$res = $db->query('
				SELECT user_id
				FROM ' . SESSIONS_TABLE . "
				WHERE session_id = '" . $db->chars($session_id) . "'
			");
			$row = $db->fetch_array($res);
			$db->free_result($res);

			if (!$row) {
				return false;
			}
			
			if ($update_time) {
				$db->query('
					UPDATE ' . SESSIONS_TABLE . '
					SET session_time = ' . time() . "
					WHERE session_id = '" . $db->chars($session_id) . "'
				");	
			}

			return $row['user_id'];
		}

		public function login($username, $password, $autologin = false, $redirect = '') {
			global $db, $token;

			$res = $db->query('
				SELECT *
				FROM ' . USERS_TABLE . "
				WHERE username = '" . $db->chars($username) . "'
					AND user_password = '" . $db->chars($password) . "'
			");

			$row = $db->fetch_array($res);
			$db->free_result($res);

			if (!$row) {
				return false;
			}

			if ($row['user_unlock']) {
				message_box('Du hast Deine E-Mail noch nicht bestätigt', '/', 'zurück zur Startseite');
			}

			$this->row = $row;

			if ($row['user_ban']) {
				$this->check_ban();
			}

			$this->setSession($row['user_id']);

			$ip = $_SERVER['REMOTE_ADDR'];

			$db->query('
				UPDATE ' . USERS_TABLE . '
				SET	user_login = ' . time() . ",
					user_ip = '" . $ip . "',
					user_lastvisit = " . time() . '
				WHERE user_id = ' . $row['user_id']
			);

			if ($autologin) {
				setCookie($this->cookie_auto_username, $row['username'], time() + $this->cookie_auto_lifetime, '/');
				setCookie($this->cookie_auto_password, $row['user_password'], time() + $this->cookie_auto_lifetime, '/');
			}

			$db->query('
				DELETE FROM ' . ONLINE_TABLE . "
				WHERE online_ip = '" . $ip . "'
					AND user_id = " . $row['user_id']
			);

			$db->query('
				UPDATE ' . ONLINE_TABLE . '
				SET user_id = ' . $row['user_id'] . "
				WHERE online_ip = '" . $ip . "'
					AND user_id = 0
			");

			$this->online_global();

			# $token->regenerate();

			if ($redirect) {
				header('Location: ' . $redirect);
				exit;
			}

			return true;
		}

		public function logout() {
			global $db, $token;

			$session_id = (isset($_COOKIE[$this->session])) ? $_COOKIE[$this->session] : '';

			if (!$this->row || !$session_id) {
				return false;
			}

			$db->query('
				UPDATE ' . ONLINE_TABLE . '
				SET user_id = 0
				WHERE user_id = ' . $this->row['user_id']
			);

			$db->query('
				UPDATE ' . USERS_TABLE . '
				SET user_lastvisit = 0
				WHERE user_id = ' . $this->row['user_id']
			);

			$db->query('
				DELETE FROM
				' . SESSIONS_TABLE . "
				WHERE session_id = '" . $db->chars($session_id). "'
			");

			$this->row = false;

			setCookie($this->cookie_auto_username, '', -3600, '/');
			setCookie($this->cookie_auto_password, '', -3600, '/');
			setCookie($this->session, '', -3600, '/');

			$this->online_global();
			$token->regenerate();

			return true;
		}

		public function online_global() {
			global $db;

			$user_id = (int)$this->row['user_id'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$agent = $db->chars(trim(substr($_SERVER['HTTP_USER_AGENT'], 0, 149)));

			if ($user_id) {
				$res = $db->query('
					SELECT user_id
					FROM ' . ONLINE_TABLE . '
					WHERE user_id = ' . $user_id
				);

				$row = $db->fetch_array($res);
				$db->free_result($res);

				if ($row) {
					$db->query('
						UPDATE ' . ONLINE_TABLE . '
						SET	online_lastvisit = ' . time() . ",
							online_agent = '" . $agent . "'
						WHERE user_id = " . $user_id
					);

					return;
				}
			}

			$res = $db->query('
				SELECT user_id
				FROM ' . ONLINE_TABLE . "
				WHERE online_ip = '" . $ip . "'
					AND user_id = 0
			");
			$row = $db->fetch_array($res);
			$db->free_result($res);

			if ($row) {
				$db->query('
					UPDATE ' . ONLINE_TABLE . '
					SET	user_id = ' . $user_id . ',
						online_lastvisit = ' . time() . ",
						online_agent = '" . $agent . "'
					WHERE online_ip = '" . $ip . "'
						AND user_id = 0
				");
			} else {
				$db->query('
					INSERT INTO ' . ONLINE_TABLE . '
					(user_id, online_lastvisit, online_ip, online_agent) VALUES
					(' . $user_id . ', ' . time() . ", '" . $ip . "', '" . $agent . "')
				");
			}
		}

		public function update_vars() {
			global $db;

			if (!$this->row) {
				return false;
			}

			$res = $db->query('
				SELECT *
				FROM ' . USERS_TABLE . '
				WHERE user_id = ' . (int)$this->row['user_id']
			);

			$this->row = $db->fetch_array($res);
			$db->free_result($res);
		}

		public function check_ban() {
			global $db;

			$res = $db->query('
				SELECT ban_id, ban_time, ban_reason
				FROM ' . BANLIST_TABLE . '
				WHERE user_id = ' . $this->row['user_id']
			);

			$row = $db->fetch_array($res);
			$db->free_result($res);

			if ($row && $row['ban_time'] > time()) {
				$this->logout();

				message_box('Du wurdest gesperrt bis: ' . date('d.m.Y H:i', $row['ban_time']) . ' Uhr<br />Grund: <i>' . htmlspecialchars($row['ban_reason']) . '</i>', '/', 'zurück zur Startseite');
			}

			$db->query('

				UPDATE ' . USERS_TABLE . '
				SET user_ban = 0
				WHERE user_id = ' . $this->row['user_id']
			);

			$this->row['user_ban'] = 0;
		}

		public function legend($level) {
			switch ($level) {
				case USER:	return '';
				case MOD:	return 'mod';
				case ADMIN:	return 'admin';
			}
		}

		public function set_rank($user_id, $rank_id, $posts) {
			if (!$this->ranks) {
				global $cache;

				$this->ranks = $cache->get('ranks');
			}

			if ($rank_id) {
				$this->ranks_cache[$user_id] = array($this->ranks[$rank_id]['rank_title'], $this->ranks[$rank_id]['rank_image']);
			} else {
				foreach ($this->ranks[0] as $p => $rank) {
					if ($posts >= $p) {
						$this->ranks_cache[$user_id] = array($rank['rank_title'], $rank['rank_image']);
						return;
					}
				}
			}

			$this->ranks[$user_id] = array('', '');
		}

		public function rank($user_id, $rank_id, $posts) {
			if (!isset($this->ranks_cache[$user_id])) {
				$this->set_rank($user_id, $rank_id, $posts);
			}

			return $this->ranks_cache[$user_id][0];
		}

		public function rank_icon($user_id, $rank_id, $posts) {
			if (!isset($this->ranks_cache[$user_id])) {
				$this->set_rank($user_id, $rank_id, $posts);
			}

			return $this->ranks_cache[$user_id][1];
		}

		public function online() {
			global $db;

			$res = $db->query('
				SELECT COUNT(*)
				FROM ' . ONLINE_TABLE
			);

			$row = $db->result($res, 0);
			$db->free_result($res);

			return $row;
		}
	}

?>
