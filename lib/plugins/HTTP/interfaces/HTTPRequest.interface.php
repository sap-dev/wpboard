<?php

	/**
		@author		< marco.a >

		< HTTPRequest Interface >
	**/

	interface HTTPRequestInterface {

		/**
			**************
			#  methods   #
			**************
		**/

		/*
			@name	alloc
			allocates HTTPRequest instance
		*/
		public static function alloc($options = 0x00);

		/*
			@name	init
			inits request
		*/
		public function init();

		/*
			@name	setOpt
			sets option
		*/
		public function setOpt($key, $value);

		/*
			@name	getOpt
			gets option
		*/
		public function getOpt($name);

		/*
			@name	setOpts
			sets options
		*/
		public function setOpts($options);

		/*
			@name	addHeader
			adds a header
		*/
		public function addHeader($name, $value);

		/*
			@name	send
			sends request
		*/
		public function send($callback);

	}

?>