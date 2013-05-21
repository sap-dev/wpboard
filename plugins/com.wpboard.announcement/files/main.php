<?php
    /** search template area in templates **/
	if (plugin::TPL()->areaAvailable('underneathContent')) {
		/** get board_title **/
		$sql =  plugin::SQL()->query("SELECT * FROM ".PREFIX."plugin_announcement WHERE id = '1'");
		$row = plugin::SQL()->fetch_array($sql);
		/** insert new contents in template **/
    		plugin::TPL()->addToArea('aboveContent', '<div class="info">'.$row['text'].'</div>');
   	}
?>