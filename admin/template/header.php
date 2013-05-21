<!DOCTYPE html>
<html>
	<head>
		<title><?=template::getVar('PAGE_TITLE'); ?> - Administrationsbereich</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="./template/style.css" />
		<script type="text/javascript">
			token = '<?=template::getVar('USER_TOKEN'); ?>';
		</script>
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/main.js"></script>
		<script type="text/javascript" src="../js/dropdown/jquery.dropdown.js"></script>

	</head>
	<body>

		<div id="header_wrap" style="padding-top: 15px;">
			<header>
				<table width="100%" style="padding: 0px;">
				<tr><td style="padding: 0px;">
				<div class="logo">
					<br />
					<img src="http://creatr.cc/creatr/logo/Web%20Power%20Board.png?1368529574" alt="Logo" />
				</div>
				</td>
				<td style="padding: 0px; text-align: right;">
					<table style="width: 400px; padding: 0px;">
					<tr>
						<td style="padding: 0px;" width="25%"><a href="?menu=1" style="color: #000000;"><div class="acp_menu_item<? if(template::getVar('menu')=="1") { ?>_activ<? } ?>"><img src="http://cdn4.iconfinder.com/data/icons/pc_de_hamburg_icon_pack/32x32/config.png"><br>Einstellungen</div></a></td>
						<td style="padding: 0px;" width="25%"><a href="?menu=2" style="color: #000000;"><div class="acp_menu_item<? if(template::getVar('menu')=="2") { ?>_activ<? } ?>"><img src="http://cdn3.iconfinder.com/data/icons/fatcow/32x32_0460/group.png"><br>Benutzer</div></a></td>
						<td style="padding: 0px;" width="25%"><a href="?menu=3" style="color: #000000;"><div class="acp_menu_item<? if(template::getVar('menu')=="3") { ?>_activ<? } ?>"><img src="http://cdn4.iconfinder.com/data/icons/Hypic_Icon_Pack_by_shlyapnikova/32/forum_32.png"><br>Forum</div></a></td>
						<td style="padding: 0px;" width="25%"><a href="?menu=4" style="color: #000000;"><div class="acp_menu_item<? if(template::getVar('menu')=="4") { ?>_activ<? } ?>"><img src="http://cdn4.iconfinder.com/data/icons/Hypic_Icon_Pack_by_shlyapnikova/32/plugin_32.png"><br>Plugins</div></a></td>
					</tr>
					</table>
				</td>
				</tr>
				</table>
			</header>
		</div>

		<section id="content">
			<?php if (template::getVar('admin')) {?>
				<div id="nav">
					<nav>
						<ul>
						
							<li><a href="index.php"<?php if (template::getVar('PAGE') == 'index'): ?>class="active"<?php endif; ?>>Startseite</a></li>
						<? if(template::getVar('menu')=="1") { ?>
							<li><a href="settings.php?menu=1"<?php if (template::getVar('PAGE') == 'settings'): ?>class="active"<?php endif; ?>>Einstellungen</a></li>
							<li><a href="news.php?menu=1"<?php if (template::getVar('PAGE') == 'news'): ?>class="active"<?php endif; ?>>News</a></li>
							<li><a href="bots.php?menu=1"<?php if (template::getVar('PAGE') == 'bots' ||template::getVar('PAGE') == 'bot-new'): ?>class="active"<?php endif; ?>>Bots</a></li>
							<li><a href="smilies.php?menu=1"<?php if (template::getVar('PAGE') == 'smilies' ||template::getVar('PAGE') == 'smilie-new'): ?>class="active"<?php endif; ?>>Smilies</a></li>
							<li><a href="menu.php?menu=1"<?php if (template::getVar('PAGE') == 'menu' ||template::getVar('PAGE') == 'menu-new'): ?>class="active"<?php endif; ?>>Men&uuml;punkte</a></li>
						
						<? }if(template::getVar('menu')=="2") { ?>
							<li><a href="users.php?menu=2"<?php if (template::getVar('PAGE') == 'users' ||template::getVar('PAGE') == 'user'): ?>class="active"<?php endif; ?>>Mitglieder</a></li>
							<li><a href="banlist.php?menu=2"<?php if (template::getVar('PAGE') == 'banlist' ||template::getVar('PAGE') == 'banlist-new'): ?>class="active"<?php endif; ?>>Sperrungen</a></li>


						<? } if(template::getVar('menu')=="3") { ?>
							<li><a href="forums.php?menu=3"<?php if (template::getVar('PAGE') == 'forums' ||template::getVar('PAGE') == 'forum-new'): ?>class="active"<?php endif; ?>>Foren</a></li>
							<li><a href="ranks.php?menu=3"<?php if (template::getVar('PAGE') == 'ranks' ||template::getVar('PAGE') == 'rank-new'): ?>class="active"<?php endif; ?>>Ränge</a></li>
							<li><a href="groups.php?menu=3"<?php if (template::getVar('PAGE') == 'groups' ||template::getVar('PAGE') == 'group-new'): ?>class="active"<?php endif; ?>>Gruppen</a></li>
						<? } if(template::getVar('menu')=="4") { ?>
							<li><a href="styles.php?menu=4"<?php if (template::getVar('PAGE') == 'styles'): ?>class="active"<?php endif; ?>>Styles</a></li>
							<li><a href="plugins.php?menu=4"<?php if (template::getVar('PAGE') == 'plugins'||template::getVar('PAGE') == 'server-new'): ?>class="active"<?php endif; ?>>Plugins</a></li>
						<? } ?>
						</ul>

						<div class="clear"></div>
					</nav>

					<div class="clear"></div>
				</div>
			<?php } ?>

			<div class="content">