<?php
	/**
	 *
	 */

	abstract class standard {
		public static function menu() {
			global $db, $user;
			$res2 = $db->query('SELECT * FROM board_menu');
			$i = 0;
			while ($row = $db->fetch_array($res2)) {
				$i++;
				if ($row['menu_link'] == 'forum') {
					$forumPages = array(
						'forum', 'viewtopic', 'viewforum', 'newtopic', 'newpost', 'search'
					);
					
					echo '
						<li><a href="./'.$row['menu_link'].'.php" '.((in_array(template::getPage(), $forumPages)) ? 'class="active"' : '').'><img border="0" src="'.$row['menu_icon'].'" style="vertical-align:middle;"> '.$row['menu_text'].'</a></li>
					';
				} else {
					if($i == 1) {
						$class = 'roundStart';
					} else {
						$class = 'active';
					}
					echo '
						<li><a href="./'.$row['menu_link'].'.php" '.((template::getPage() == $row['menu_link']) ? 'class="'.$class.'"' : '').'><img border="0" src="'.$row['menu_icon'].'" style="vertical-align:middle;"> '.$row['menu_text'].'</a></li>
					';
				}
			}
			if ($user->row['user_level'] == ADMIN) {
				echo '<li><a href="./admin"><img border="0" src="http://cdn2.iconfinder.com/data/icons/gnomeicontheme/24x24/actions/gtk-edit.png" style="vertical-align:middle;"> Administration</a></li>';
			}


		}
	}

	/**
	 *	This function must be available in EVERY style. It gets called while initializing the style.
	 */

	function initializeStyle() {
		template::registerArea(array(
			'footer',
			'header',
			'aboveContent',
			'menuPlugin',
			'underneathContent'
		));
	}
?>