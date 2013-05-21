<?php
	/**
	*
	* @package com.Itschi.base.cache
	* @since 2007/05/25
	*
	*/

	namespace Itschi\lib;

	class cache {
		function delete($name) {
			global $root;

			@unlink("{$root}lib/cache/{$name}.php");
		}

		function put($file) {
			global $root, $db;

			$data = array();

			switch ($file) {
				case 'config':
					$res = $db->query('
						SELECT config_name, config_value
						FROM ' . CONFIG_TABLE . '
						WHERE is_dynamic = 0
					');

					while ($row = $db->fetch_array($res)) {
						$data[$row['config_name']] = $row['config_value'];
					}

					$db->free_result($res);

				break;

				case 'ranks':
					$res = $db->query('
						SELECT rank_posts, rank_image, rank_title
						FROM ' . RANKS_TABLE . '
						WHERE rank_special = 0
						ORDER BY rank_posts DESC
					');

					while ($row = $db->fetch_array($res)) {
						$data[0][$row['rank_posts']] = $row;
					}

					$db->free_result($res);

					$res = $db->query('
						SELECT rank_id, rank_image, rank_title
						FROM ' . RANKS_TABLE . '
						WHERE rank_special = 1
					');

					while ($row = $db->fetch_array($res)) {
						$data[$row['rank_id']] = $row;
					}

					$db->free_result($res);

				break;

				case 'smilies':
				case 'smilies_group':

					$res = $db->query('
						SELECT smilie_emotion, smilie_image
						FROM ' . SMILIES_TABLE . '
						' . (($file == 'smilies_group') ? 'GROUP BY smilie_image' : '') . '
						ORDER BY smilie_id ASC
					');

					while ($row = $db->fetch_array($res)) {
						$data[] = $row;
					}

					$db->free_result($res);

				break;

				case 'bots':
					$res = $db->query('
						SELECT *
						FROM ' . BOTS_TABLE
					);

					while ($row = $db->fetch_array($res)) {
						$data[] = $row;
					}

					$db->free_result($res);

				break;
			}

			$path = sprintf('%slib/cache/%s.php', $root, $file);

			if (is_writable($path)) {
				$file = fopen($path, 'w');
				$bytes = fwrite($file, '<?php $row = unserialize(\'' . str_replace('\'','\\\'', serialize($data)) . '\'); ?>');
				fclose($file);
			} else {
				@unlink($path);
			}

			return $data;
		}

		function get($file) {
			if (!@include("{$root}lib/cache/{$file}.php")) {
				$row = $this->put($file);
			}

			return $row;
		}
	}
?>