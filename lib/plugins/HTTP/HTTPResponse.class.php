<?php

	/**
		@author		< marco.a >

		< HTTPResponse Class >
	**/

	final class HTTPResponse implements HTTPResponseInterface {

		/*
			@name	locked
		*/
		private $locked = NULL;

		/*
			@name	items
		*/
		private $items = NULL;

		/*
			@name	__construct
		*/
		public function __construct() {
			$this->locked = false;
			$this->items = array();
		}

		/*
			@name	setItem
			sets an item
		*/
		public function setItem($name, $value) {
			if ($this->locked == true || isset($this->items[$name])) return false;

			$this->items[$name] = $value;

			return true;
		}

		/*
			@name	lock
			locks instance
		*/
		public function lock() {
			$this->locked = true;
		}

		/*
			@name	__call
		*/
		public function __call($method, $args) {
			$item = str_replace('get', '', $method);
			$item = lcfirst($item);

			if (!isset($this->items[$item])) return NULL;

			return $this->items[$item];
		}

	}

?>