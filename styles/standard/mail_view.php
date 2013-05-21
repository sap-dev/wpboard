<?php template::display('header'); ?>

<div class="fLeft actionsL" style="width: 60%;">
	<h1>
		<a href="./mail.php">Mails</a> &rsaquo;
		<a href="./mail.php?dir=<?=template::getVar('DIR'); ?>"><?php if (template::getVar('DIR') == '1'): ?>Posteingang<?php else: ?>Gesendet<?php endif; ?></a> &rsaquo;
		<?=template::getVar('MAIL_TITLE'); ?>
	</h1>
</div>

<div class="fRight actionsR noP" style="width: 40%;">
	<a href="mail.php?dir=<?=template::getVar('DIR'); ?>&mode=new&id=<?=template::getVar('MAIL_USER_ID'); ?>&answer=<?=template::getVar('MAIL_ID'); ?>" class="button blueB">Antworten</a>
	<a href="mail.php?dir=<?=template::getVar('DIR'); ?>&mode=delete&id=<?=template::getVar('MAIL_ID'); ?>" class="button redB">Löschen</a>
	<a href="mail.php?dir=<?=template::getVar('DIR'); ?>&mode=new&id=<?=$user->row; ?>&answer=<?=template::getVar('MAIL_ID'); ?>&quote=<?=template::getVar('MAIL_ID'); ?>" class="button greyB">Zitieren</a>
</div>

<div class="clear"></div>

<div id="posts">
	<table border="0" cellspacing="0" cellpadding="0" width="100%" class="firstPost" style="border-bottom: 0;">
		<tr>
			<td class="user" width="40px">
				<img src="images/avatar/<?=template::getVar('MAIL_USER_AVATAR'); ?>" border="0" width="40" height="40" />
			</td>

			<td class="user">
				<?php if (template::getVar('MAIL_USER_ID')): ?>
					<b><a href="user.php?id=<?=template::getVar('MAIL_USER_ID'); ?>"><?=template::getVar('MAIL_USERNAME'); ?></a></b><br />
				<?php else: ?>
					<b>Unbekannt</b>
				<?php endif; ?>

				<small class="grey">
					<?=template::getVar('MAIL_TIME'); ?> Uhr
				</small>
			</td>

			<td class="actions" align="right">
				&nbsp;
			</td>
		</tr>

		<tr>
			<td colspan="3" class="text">
				<?=template::getVar('MAIL_TEXT'); ?>

				<?php if (template::getVar('SIGNATUR')): ?>
					<br /><br />
					<div style="border-top:1px solid #dddddd;padding:5px;">
						<span><?=template::getVar('SIGNATUR'); ?></span>
					</div>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</div>

<?php template::display('footer'); ?>