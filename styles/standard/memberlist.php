<?php template::display('header'); ?>
<table width="100%"><tr><td valign="top" width="25%">
<h2>Mitglieder</h2>
<div class="sections">
	<ul>
		<li><a href="memberlist.php" <?php if (!template::getVar('MODE')): ?>class="active"<?php endif; ?>"><img src="http://cdn3.iconfinder.com/data/icons/humano2/24x24/emblems/emblem-people.png" style="vertical-align: middle;">Mitglieder</a></li>
		<li><a href="memberlist.php?mode=team" <?php if (template::getVar('MODE') == 'team'): ?>class="active"<?php endif; ?>><img src="http://cdn4.iconfinder.com/data/icons/general24/png/24/administrator.png" style="vertical-align: middle;">Team</a></li>
	</ul>

	<div class="clear"></div>
</div>
</td><td valign="top" width="75%"><br />
<div class="tabs noJS">
	<ul>
			<li><a href="memberlist.php?mode=team" <?php if (!template::getVar('CHAR')): ?>class="active"<?php endif; ?>>Alle</a></li>
			<li><a href="memberlist.php?mode=team&amp;q=14" <?php if (template::getVar('CHAR') == '14'): ?>class="active"<?php endif; ?>>Administrator</a></li>
			<li><a href="memberlist.php?mode=team&amp;q=15" <?php if (template::getVar('CHAR') == '15'): ?>class="active"<?php endif; ?>>Moderator</a></li>
	</ul>

	<div class="content">
		<div id="members" class="tabContent">
			<?php
				if (isset(template::$blocks['members'])):
					foreach(template::$blocks['members'] as $members):
			?>

						<div style="float:left;padding:15px 0;width:33%">
							<a style="float:left;margin-right:10px;width: 50px;height: 50px;" href="user.php?id=<?=$members['ID']; ?>"><img class="img" src="images/avatar/mini/<?=$members['AVATAR']; ?>" alt="<?=$members['USERNAME']; ?>" /></a>
							<b><a class="<?=$members['LEGEND']; ?>" href="user.php?id=<?=$members['ID']; ?>"><?=$members['USERNAME']; ?></a></b><br /><span><?=$members['RANK']; ?></span>
						</div>

			<?php
					endforeach;
				endif;
			?>

			<div class="clear"></div>
		</div>
	</div>
</div>

<?php if (template::getVar('PAGES_NUM') > '1'): ?>Seite <?=template::getVar('PAGE'); ?> von <?=template::getVar('PAGES_NUM'); ?> | <?=template::getVar('PAGES'); ?><?php endif; ?>
</td></tr></table>
<?php template::display('footer'); ?>
