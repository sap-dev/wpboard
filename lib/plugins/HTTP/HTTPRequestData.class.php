<?php

	/**
		@author		< marco.a >

		< HTTPRequestData Class >
	**/

	final class HTTPRequestData implements HTTPRequestDataInterface {

		/*
			@name	HTTPRequest
		*/
		private $HTTPRequest = NULL;

		/*
			@name	fields
		*/
		private $fields = NULL;

		/*
			@name	alloc
			allocates HTTPRequestData instance
		*/
		public static function alloc() {
			return new self();
		}

		/*
			@name	__construct
		*/
		public function __construct() {
			$this->fields = array();
		}

		/*
			@name	add
			adds a field
		*/
		public function add($name, $value) {
			if (isset($this->fields[$name])) return false;

			$this->fields[$name] = $value;

			return true;
		}

		/*
			@name	addFile
			adds a file
		*/
		public function addFile($name, $path) {
			if (isset($this->fields[$name]) || !is_file($path)) return false;

			$this->fields[$name] = array(
				'name' => $name,
				'path' => realpath($path)
			);

			return true;
		}

		/*
			@name	remove
			removes a field
		*/
		public function remove($name) {
			if (!isset($this->fields[$name])) return false;

			$this->fields[$name] = NULL;
			unset($this->fields[$name]);

			return true;
		}

		/*
			@name	fieldByValue
			gets a field by its value
		*/
		public function fieldByValue($value, $type = false) {
			if (!is_array($this->fields)) return false;

			$field = false;

			foreach ($this->fields as $fieldName => $fieldValue) {
				if ($type == true) {
					if ($fieldValue === $value) {
						$field = $fieldName;

						break;
					}
				} else {
					if ($fieldValue == $value) {
						$field = $fieldName;

						break;
					}
				}
			}

			return $field;
		}

		/*
			@name	getFields
			returns fields
		*/
		public function getFields() {
			return $this->fields;
		}

	}

?>