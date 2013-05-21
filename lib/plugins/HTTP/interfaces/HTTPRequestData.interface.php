<?php

	/**
		@author		< marco.a >

		< HTTPRequestData Interface >
	**/

	interface HTTPRequestDataInterface {

		/**
			**************
			#  methods   #
			**************
		**/

		/*
			@name	alloc
			allocates HTTPRequestData instance
		*/
		public static function alloc();

		/*
			@name	__construct
		*/
		public function __construct();

		/*
			@name	add
			adds a field
		*/
		public function add($name, $value);

		/*
			@name	addFile
			adds a file
		*/
		public function addFile($name, $path);

		/*
			@name	remove
			removes a field
		*/
		public function remove($name);

		/*
			@name	fieldByValue
			gets a field by its value
		*/
		public function fieldByValue($value, $type = false);

		/*
			@name	getFields
			returns fields
		*/
		public function getFields();

	}

?>