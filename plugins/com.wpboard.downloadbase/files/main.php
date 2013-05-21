<?php
    /** insert menu link **/
		if($_GET['plugin']=="com.wpboard.downloadbase") {
			$active = 'class="active"';
		} else {
			$active = '';
		}
		plugin::TPL()->addToArea('menuPlugin', '<li><a '.$active.' href="plugin.php?plugin=com.wpboard.downloadbase"><img src="http://cdn3.iconfinder.com/data/icons/musthave/24/Download.png" style="vertical-align:middle;"> Downloads</a></li>');
?>