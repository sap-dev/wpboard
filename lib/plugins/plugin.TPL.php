<?php
	/**
	*
	* @package com.Itschi.base.plugins.TPL
	* @since 2007/05/25
	*
	*/

	final class TPL extends plugin {
		public function addToArea($area, $content) {
			if (!parent::hasPermission('TPL')) {
				parent::logError('Access to TPL-functions denied.', 'TPL');
				return false;
			}

			if (!$this->areaAvailable($area)) {
				parent::logError('Area <b>' . htmlspecialchars($area) . '</b> not registered.', 'TPL');
			} else {
				template::addToArea($area, $content);
			}
		}

		public function areaAvailable($area) {
			return template::areaAvailable($area);
		}
	}
?>