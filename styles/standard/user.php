<?php template::display('header'); ?>

<?php if (template::getVar('IS_ONLINE')): ?>
	<style type="text/css">
		#profile .header {
			border-bottom: 1px solid black;
		}
	</style>
<?php endif; ?>

<style>
	#profile {
		margin-top: -40px;
	}
</style>
<br />
<div id="profile">
	<div class="header">
		<div class="avatar">
			<img class="img" src="images/avatar/<?=template::getVar('AVATAR'); ?>" border="0" height="100px" width="100" />
		</div>

		<div class="user">
			<h1>
				<?=template::getVar('USER_USERNAME'); ?>

				<?php if (template::getVar('IS_ONLINE')): ?>
					<small style="font-size: 12px;">&minus; <b>Online</b> seit <?=template::getVar('ONLINE_TIME'); ?> Minuten</small>
				<?php else: ?>
					<small style="font-size: 12px;">&minus; <b>Offline</b> seit <?=template::getVar('ONLINE_TIME'); ?> Uhr</small>
				<?php endif; ?>
			</h1>

			<span class="status"><?=template::getVar('USER_USERSTATUS'); ?></span>
		</div>

		<div class="clear"></div>
	</div>
</div>

<?php if (template::getVar('BAN')): ?>
	<div class="info">Das Mitglied ist gesperrt</div>
<?php endif; ?>

<table width="100%"><tr><td valign="top" width="25%">

	<div class="box_container">
		<div class="box_header">Allgemeine Informationen</div>
		<div class="box_content">
			<img src="http://www.woltlab.com/forum/wcf/icon/teamM.png" style="vertical-align: middle;"> <?=template::getVar('RANK'); ?>	<?php if (template::getVar('RANK_ICON')): ?><br /><img src="images/ranks/<?=template::getVar('RANK_ICON'); ?>" border="0" /><?php endif; ?><br />
			<img src="http://www.woltlab.com/forum/wcf/icon/registerM.png" style="vertical-align: middle;"> Registriert seid <?=template::getVar('REGISTER'); ?><br />
			<img src="http://www.woltlab.com/forum/icon/postM.png" style="vertical-align: middle;"> <?=template::getVar('POSTS'); ?> Beitr&auml;ge (<?=template::getVar('PRODAY'); ?> Beiträge pro Tag)<br />
			<img src="http://www.woltlab.com/forum/wcf/icon/offlineM.png" style="vertical-align: middle;"> Zuletzt online <?=template::getVar('LAST_VISIT'); ?><br />
			</div>
	</div>
	<br />
	<div class="box_container">
		<div class="box_header">Kontaktmöglichkeiten</div>
		<div class="box_content">
			<img src="http://www.woltlab.com/forum/wcf/icon/websiteM.png" style="vertical-align: middle;"> <?php if (template::getVar('WEBSITE')): ?><a target="_blank" href="<?=template::getVar('WEBSITE'); ?>"><?=template::getVar('WEBSITE'); ?></a><?php else: ?>Keine<?php endif; ?><br />
			<img src="http://www.woltlab.com/forum/wcf/icon/skypeM.png" style="vertical-align: middle;"> <?php if (template::getVar('SKYPE')): ?><?=template::getVar('SKYPE'); ?><?php else: ?>Keine<?php endif; ?><br />
			<img src="http://www.woltlab.com/forum/wcf/icon/icqM.png" style="vertical-align: middle;"> <?php if (template::getVar('ICQ')): ?><?=template::getVar('ICQ'); ?><?php else: ?>Keine<?php endif; ?><br />
		</div>
	</div>


</td><td valign="top" width="75%">
	<div class="box_container" style="width: 100%;">
		<div class="box_header">Über mich</div>
		<div class="box_content">
			<?=template::getVar('UEBER'); ?>	
		</div>
	</div>

	<br /><br />
<?php if (template::getVar('SIGNATUR')): ?>
<?=template::getVar('SIGNATUR'); ?>				
	<?php endif; ?>
			</td>
		</tr>
	</table>
	

<br /><br />


	</td></tr></table>


</div>

<?php template::display('footer'); ?>