<?
	/** load required classes **/
		require_once '../lib/plugins/plugin.php';
		require_once '../lib/plugins/plugin.HTTP.php';
		require_once '../lib/plugins/plugin.SQL.php';
		require_once '../lib/plugins/plugin.TPL.php';
		require_once '../lib/plugins/plugin.utils.php';

	/** open init function **/
		plugin::init_classes();

	/** update sql **/
	if($_POST['submit']) {
		plugin::SQL()->query("UPDATE ".PREFIX."plugin_announcement SET text = '".$_POST['text']."' WHERE id = '1'");
		template::assign("MELDUNG","1");
	}

	/** sql query **/
	$sql =  plugin::SQL()->query("SELECT * FROM ".PREFIX."plugin_announcement WHERE id = '1'");
	$row = plugin::SQL()->fetch_array($sql);

	/** assign template variables **/
	template::assign(array(
		'TEXT'		=>	$row['text'],
	));

	/** display template **/
	template::display_plugin_tpl('com.wpboard.announcement', 'admin', 'adminTPL');
?>