<?php
	/**
	*
	* @package com.Itschi.base.plugins.interfaces.plugin
	* @since 2007/05/25
	*
	*/

	interface pluginInterface {
		/**
		 *	@name SQL
		**/

		public static function SQL();

		/**
		 *	@name TPL
		**/

		public static function TPL();

		/**
		 *	@name HTTP
		**/

		//public static function HTTP();

		/**
		 *	@name files
		**/

		//public static function files();

		/**
		 *	@name cache
		**/

		//public static function cache();

		/**
		 *	@name utils
		**/

		public static function utils();
	}
?>