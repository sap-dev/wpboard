<?php
	/**
	*
	* @package com.Itschi.base.plugins.SQL
	* @since 2007/05/25
	*
	*/

	final class SQL extends plugin {
		/**
		 * 	@name checkPermission
		 *
		 *	@return bool
		 */

		private function checkPermission() {
			

			return true;
		}

		/**
		 *	@name fetch_array
		 *	@param resource
		 *
		 *	@return array
		 */

		public function fetch_array($res) {
			global $db;

			return $db->fetch_array($res);
		}

		/**
		 *	@name fetch_object
		 *	@param resource
		 *
		 *	@return object
		 */

		public function fetch_object($res) {
			global $db;

			return $db->fetch_object($res);
		}

		/**
		 *	@name num_rows
		 *	@param query
		 *
		 *	@return int
		 */

		public function num_rows($qry) {
			global $db;

			return $db->num_rows($qry);
		}

		/**
		 *	@name insertID
		 *
		 *	@return int
		 */

		public function insertID() {
			global $db;

			return $db->insert_id();
		}

		/**
		 *	@name free_result
		 *
		 *	@return void
		 */

		public function free_result($res) {
			global $db;

			return $db->free_result($res);
		}

		/**
		 *	@name chars
		 *	@param string
		 *
		 *	@return string
		 */

		public function chars($str) {
			global $db;

			return $db->chars($str);
		}

		/**
		 *	@name affected_rows
		 *
		 *	@return int
		 */

		public function affected_rows() {
			global $db;

			return $db->affected_rows();
		}

		/**
		 *	@name query
		 *	@param string $qry
		 *
		 *	@return resource
		 */

		public function query($qry, $unbuffered = false) {
			global $db, $prefix, $config;

			// Am I allowed to execute SQL queries?
			self::checkPermission();

			$qry_arr = explode(' ', $qry);

			// delete empty items (such as double spaces) and reorder array
			$arr = array_values(array_filter($qry_arr, function($v) {
				return !empty($v);
			}));

			$mode = strtolower($arr[0]);

			if ($mode == 'select') {

				// SELECT something FROM table
				unset($arr[0]);

				$c = 0;

				foreach ($arr as $q) {
					if ($q == 'FROM') {
						$i = $c + 1;
					}

					$c++;
				}

				$table = str_replace($prefix, '', $arr[$i + 1]);
				$table = preg_replace('^[(`|´|\')]^', '', $table);

				if (!empty($prefix)) {
					if (!preg_match('^'.$prefix.'^', $arr[$i + 1])) {
						parent::logError('Access to tables outside prefix not allowed for query<br /><b>'.$qry.'</b>', 'SQL');
					}
				}

				if (parent::hasPermission('SQL', array('accessTables', array($table), $qry))) {
					if ($unbuffered) {
						return $db->unbuffered_query($qry);
					}

					return $db->query($qry);
				}

			} else if ($mode == 'update' || $mode == 'alter' || $mode == 'drop' || $mode == 'insert' || $mode == 'delete') {

				// UPDATE table, ALTER TABLE table, DROP TABLE table, INSERT INTO table, DELETE FROM table
				if ($mode == 'drop' && strtolower($arr[1]) == 'database') {
					parent::logError('Dropping databases is not allowed.');
				}

				$table = str_replace($prefix, '', $arr[ ($mode == 'update') ? 1 : 2 ]);
				$table = preg_replace('^[(`|´|\')]^', '', $table);

				if (!empty($prefix)) {
					if (!preg_match('^'.$prefix.'^', $arr[ ($mode == 'update') ? 1 : 2 ])) {
						parent::logError('Access to tables outside prefix not allowed for query<br /><b>'.$qry.'</b>', 'SQL');
					}
				}

				if (parent::hasPermission('SQL', array('accessTables', array($table), $qry))) {
					if ($unbuffered) {
						return $db->unbuffered_query($qry);
					}

					return $db->query($qry);
				}

			} else if ($mode == 'create' && strtolower($arr[1]) == 'table') {

				// CREATE TABLE table
				if (parent::hasPermission('SQL', array('createTables'), $qry)) {
					if ($unbuffered) {
						return $db->unbuffered_query($qry);
					}

					return $db->query($qry);
				}

			} else {

				return $db->query($qry);
			}
		}

		/**
		 *	@name insert
		 *
		 *	@param string table
		 *	@param array values
		 *	@info
		 *
		 *	@return res
		 */

		public function insert($table, $values) {
			global $db, $prefix;

			self::checkPermission();

			$columns = '(';
			$vals = '(';

			foreach ($values as $col => $val) {
				$columns .= $db->chars($col) . ', ';
				$vals .= "'" . $db->chars($val) . "', ";
			}

			$columns = mb_substr($columns, 0, mb_strlen($columns) - 2) . ')';
			$vals = mb_substr($vals, 0, mb_strlen($vals) - 2) . ')';

			$qry = "INSERT INTO ".$db->chars($table)." ".$columns." VALUES ".$vals."";
			return self::query($qry);
		}
	}

	?>
