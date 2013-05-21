<?
	require_once '../lib/plugins/plugin.php';
	require_once '../lib/plugins/plugin.HTTP.php';
	require_once '../lib/plugins/plugin.SQL.php';
	require_once '../lib/plugins/plugin.TPL.php';
	require_once '../lib/plugins/plugin.utils.php';

	plugin::init_classes();
	$sql =  plugin::SQL()->query('
		CREATE TABLE IF NOT EXISTS '.PREFIX.'plugin_announcement (
 		 `id` int(11) NOT NULL AUTO_INCREMENT,
  		 `text` text NOT NULL,
  		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
	') OR die(mysql_error());
	$sql =  plugin::SQL()->query("INSERT INTO ".PREFIX."plugin_announcement SET text = 'Dies ist ein Mustertext der &uuml;ber das Plugin ge&auml;ndert werden kann.'") OR die(mysql_error());

?>