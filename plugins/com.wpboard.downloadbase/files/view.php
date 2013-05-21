<?

	require_once 'base.php';

	if($_GET['action']=="cat") {
		$sql = plugin::SQL()->query('SELECT * FROM ' . PREFIX . 'plugin_downloadbase_entry WHERE entry_cat = '.$_GET['id'].'');
		while ($row = plugin::SQL()->fetch_array($sql)) {
			$forum = array(
				'NAME'		=>	$row['entry_name'],
				'TIME'		=>	$row['entry_time'],
				'DOWNLOADS'	=>	$row['entry_downloads'],
				'TEXT'		=>	$row['entry_text'],
				'PACKAGE'	=>	$row['entry_package'],
				'VERSION'	=>	$row['entry_version']
			);
			template::assignBlock('forums', $forum);
		}
		$sql2 = plugin::SQL()->fetch_array(plugin::SQL()->query('SELECT * FROM ' . PREFIX . 'plugin_downloadbase_cat WHERE cat_id = '.(int)$_GET['id'].''));
		template::assign("CAT_NAME",$sql2['cat_name']);
		template::display_plugin_tpl('com.wpboard.downloadbase', 'downloadbase_cat', '');
	} elseif($_GET['action']=="json") {
		$sql = plugin::SQL()->query('SELECT * FROM ' . PREFIX . 'plugin_downloadbase_entry');
		echo '[';
		while ($row = plugin::SQL()->fetch_array($sql)) {
			echo '
				{
   			 "name": "'.$row['entry_name'].'",
    			 "version": "'.$row['entry_version'].'",
   			 "description": "'.$row['entry_text'].'",
   			 "lastUpdate": '.$row['entry_time'].',
   			 "developer": "'.$row['entry_developer'].'",
   			 "package": "'.$row['entry_package'].'"
    }

			';
		}
		echo ']';
	} else {
		$sql = plugin::SQL()->query('SELECT * FROM ' . PREFIX . 'plugin_downloadbase_cat');
		while ($row = plugin::SQL()->fetch_array($sql)) {
			$num = plugin::SQL()->num_rows(plugin::SQL()->query('SELECT * FROM ' . PREFIX . 'plugin_downloadbase_entry WHERE entry_cat = '.$row['cat_id'].''));
			$sql2 = plugin::SQL()->fetch_array(plugin::SQL()->query('SELECT * FROM ' . PREFIX . 'plugin_downloadbase_entry WHERE entry_cat = '.$row['cat_id'].' ORDER by entry_time DESC'));
			$forum = array(
				'NAME'			=>	$row['cat_name'],
				'ID'			=>	$row['cat_id'],
				'NUM'			=>	$num,
				'LAST_DL_NAME'	=>	$sql2['entry_name'],
				'LAST_DL_DOWNLOADS'	=>	$sql2['entry_downloads'],
				'LAST_DL_TIME'	=>	date("d.m.Y H:i",$sql2['entry_time']),
			);
			template::assignBlock('forums', $forum);
		}
		$num2 = plugin::SQL()->num_rows(plugin::SQL()->query('SELECT * FROM ' . PREFIX . 'plugin_downloadbase_entry'));
		template::assign("download_num",$num2);
		template::display_plugin_tpl('com.wpboard.downloadbase', 'downloadbase_index', '');
	}
?>