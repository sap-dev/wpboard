<?
	require_once '../lib/plugins/plugin.php';
	require_once '../lib/plugins/plugin.HTTP.php';
	require_once '../lib/plugins/plugin.SQL.php';
	require_once '../lib/plugins/plugin.TPL.php';
	require_once '../lib/plugins/plugin.utils.php';

	plugin::init_classes();
	$sql =  plugin::SQL()->query('
		CREATE TABLE IF NOT EXISTS '.PREFIX.'plugin_downloadbase_cat (
 		 `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  		 `cat_name` varchar(255) NOT NULL,
  		PRIMARY KEY (`cat_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
	') OR die(mysql_error());
	$sql =  plugin::SQL()->query('
		CREATE TABLE IF NOT EXISTS '.PREFIX.'plugin_downloadbase_entry (
 		 `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  		 `entry_name` varchar(255) NOT NULL,
               `entry_time` varchar(255) NOT NULL,
    		 `entry_downloads` varchar(255) NOT NULL,
  		 `entry_url` varchar(255) NOT NULL,
  		 `entry_cat` varchar(255) NOT NULL,
  		 `entry_developer` varchar(255) NOT NULL,
  		 `entry_version` varchar(255) NOT NULL,
  		 `entry_package` varchar(255) NOT NULL,
  		 `entry_text` TEXT NOT NULL,
  		PRIMARY KEY (`entry_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
	') OR die(mysql_error());

	$sql =  plugin::SQL()->query("INSERT INTO ".PREFIX."plugin_downloadbase_cat SET cat_name = 'Kategorie 1'") OR die(mysql_error());
	$sql =  plugin::SQL()->query("INSERT INTO ".PREFIX."plugin_downloadbase_cat SET cat_name = 'Kategorie 2'") OR die(mysql_error());
?>