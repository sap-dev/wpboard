<?php
/**
*
* @package Itschi
* @since 2007/05/25
*
*/

require '../base.php';

if ($user->row['user_level'] != ADMIN)
{
	message_box('Keine Berechtigung!', '../', 'zurück');
}

$addons = array();

if ($handle = @opendir('../addons/'))
{
	while ($dir = @readdir($handle))
	{
		if ($dir != '.' && $dir != '..' && is_dir('../addons/' . $dir))
		{
			if (@include('../addons/' . $dir . '/index.php'))
			{
				$tpl->block_assign('addons', array(
					'NAME'		=> 	(isset($Addon['Name']) ? htmlspecialchars($Addon['Name']) : ''),
					'TEXT'		=> 	(isset($Addon['Text']) ? htmlspecialchars($Addon['Text']) : ''),
					'AUTHOR'	=> 	(isset($Addon['Author']) ? htmlspecialchars($Addon['Author']) : ''),
					'VERSION'	=> 	(isset($Addon['Version']) ? htmlspecialchars($Addon['Version']) : ''),
					'REQUIRE'	=> 	(isset($Addon['Require']) ? htmlspecialchars($Addon['Require']) : ''),
					'DIR'		=>	$dir,
					'INSTALLED'	=>	@file_exists('../addons/' . $dir . '/___installed.php')
				));

				$addons[] = $dir;
			}
		}
	}

	closedir($handle);
}

if (isset($_GET['install']) && in_array($_GET['install'], $addons))
{
	$dir = $_GET['install'];

	include '../addons/' . $dir . '/install.php';

        $f = @fopen('../addons/' . $dir . '/___installed.php', 'w');

	if ($f)
	{
        	$bytes = fwrite($f, time());
        	fclose($f);
	}

	message_box('Das Addon wurde installiert', 'addons.php', 'Weiter');
}

if (isset($_GET['uninstall']) && in_array($_GET['uninstall'], $addons))
{
	$dir = $_GET['uninstall'];

	include '../addons/' . $dir . '/uninstall.php';

	@unlink('../addons/' . $dir . '/___installed.php');

	message_box('Das Addon wurde deinstalliert', 'addons.php', 'Weiter');
}



$tpl->assign(array(
	'MENU_BUTTON'	=>	9
));

$tpl->display('addons.tpl');

?>