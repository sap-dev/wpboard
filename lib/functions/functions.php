<?php
	/**
	*
	* @package com.Itschi.base.functions
	* @since 2007/05/25
	*
	*/

	use \Itschi\lib\functions as f;

	require_once 'functions.interface.php';

	abstract class functions implements functionsInterface {
		private static $objs = array();

		public static function init() {
			/* self::$objs['date'] = new f\date();
			self::$objs['user'] = new f\user();
			self::$objs['topic'] = new f\topic(); */
		}

		public static function date() {
			// return self::$objs['date'];

			return new f\date();
		}

		public static function topic() {
			// return self::$objs['topic'];

			return new f\topic();
		}
		
		public static function language() {
			return new lib\language();
		}

		public static function upload() {
			// return self::$objs['upload'];

			return new f\upload();
		}

		public static function user() {
			// return self::$objs['user'];

			return new f\user();
		}
	}
?>