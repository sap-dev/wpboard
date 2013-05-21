<?php

	/**
		@author		< marco.a >

		< HTTPResponse Interface >
	**/

	interface HTTPResponseInterface {

		/**
			**************
			#  methods   #
			**************
		**/

		/*
			@name	__construct
		*/
		public function __construct();

		/*
			@name	setItem
			sets an item
		*/
		public function setItem($name, $value);

		/*
			@name	lock
			locks instance
		*/
		public function lock();

		/*
			@name	__call
		*/
		public function __call($method, $args);

	}

?>