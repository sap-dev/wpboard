<?php
	/**
	*
	* @package com.Itschi.base.constants
	* @since 2007/05/25
	*
	*/

	define('VERSION', '1.0.0 Pre-Alpha 1');

	define('USER', 0);
	define('MOD', 1);
	define('ADMIN', 2);
	define('STRIP', (get_magic_quotes_gpc()) ? true : false);

	define('SESSIONS_TABLE', $prefix . 'sessions');
	define('BOTS_TABLE', $prefix . 'bots');
	define('CONFIG_TABLE', $prefix . 'config');
	define('FORUMS_TABLE', $prefix . 'forums');
	define('FORUMS_TRACK_TABLE', $prefix . 'forums_track');
	define('MAILS_TABLE', $prefix . 'mails');
	define('ONLINE_TABLE', $prefix . 'online');
	define('POLL_OPTIONS_TABLE', $prefix . 'poll_options');
	define('POLL_VOTES_TABLE', $prefix . 'poll_votes');
	define('POSTS_TABLE', $prefix . 'posts');
	define('RANKS_TABLE', $prefix . 'ranks');
	define('SMILIES_TABLE', $prefix . 'smilies');
	define('TOPICS_TABLE', $prefix . 'topics');
	define('TOPICS_TRACK_TABLE', $prefix . 'topics_track');
	define('USERS_TABLE', $prefix . 'users');
	define('BANLIST_TABLE', $prefix . 'banlist');
	define('PLUGINS_TABLE', $prefix . 'plugins');
	define('LANGUAGE_TABLE', $prefix . 'language');
	define('SERVER_TABLE', $prefix . 'plugin_server');
	define('STYLES_TABLE', $prefix . 'styles');
	define('LABEL_TABLE', $prefix . 'label');
	define('MENU_TABLE', $prefix . 'menu');
	define('PREFIX', $prefix);
?>
