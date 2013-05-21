<?php
	/**
	*
	* @package com.Itschi.base.functions.date
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib\functions;

	class date extends \functions {
		public function timeDifference($date1, $date2, $whole = false) {
			$date1 = is_int($date1) ? $date1 : strtotime($date1);
			$date2 = is_int($date2) ? $date2 : strtotime($date2);

			if (($date1 !== false) && ($date2 !== false)) {
				if ($date2 >= $date1) {
					$diff = ($date2 - $date1);
				   
					if ($days = intval((floor($diff / 86400))))
						$diff %= 86400;
					if ($hours = intval((floor($diff / 3600))))
						$diff %= 3600;
					if ($minutes = intval((floor($diff / 60))))
						$diff %= 60;
				   
					if ($whole) {
						return array($days, $hours, $minutes, intval($diff));
					} else {
						return $days;
					}
				}
			}
		   
			return false;
		}
	   
		public function strTimeDifference($date1, $date2) {
			$diff = $this->timeDifference($date1, $date2);
			$date1 = strtotime($date1);

			if ($diff == 0) {
				return 'Heute, ' . date('H:i', $date1); 
			} else if ($diff == 1) {
				return 'Gestern, ' . date('H:i', $date1);
			} else if ($diff == 2) {
				return 'Vorgestern, ' . date('H:i', $date1);
			} else {
				return 'Vor ' . $diff . ' Tagen, ' . date('H:i', $date1);
			}
		}
	}
?>