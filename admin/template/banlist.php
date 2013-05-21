<?php template::display('header'); ?>

<div class="h2box">
	<div class="fLeft">
		<h1>Sperrungen</h1><br />
		<?php if (template::getVar('NUM') == '1'): ?>
			<span>Es ist momentan 1 Mitglied gesperrt.</span>
		<?php else: ?>
			<span>Es sind momentan <b><?=template::getVar('NUM'); ?></b> Mitglieder gesperrt.</span>
		<?php endif; ?>
	</div>
	
	<div class="fRight" style="padding-top: 30px;">
		<a href="banlist-new.php?menu=<?=template::getVar("menu");?>" class="button">Mitglied sperren</a>
	</div>
	
	<div class="clear"></div>
</div>

<table class="form" cellpadding="5" cellspacing="0">
	<tr>
		<td width="25%" class="title">Mitglied</td>
		<td width="30%" class="title">Grund</td>
		<td width="15%" class="title">gesperrt von</td>
		<td width="30%" class="title">Aktion</td>
	</tr>

	<?php
		if (isset(template::$blocks['banlist'])):
			foreach(template::$blocks['banlist'] as $banlist):
	?>
				<tr>
					<td>
						<a href="../user.php?id=<?=$banlist['USER_ID']; ?>&menu=<?=template::getVar("menu");?>"><b><?=$banlist['USERNAME']; ?></b></a><br />
						<small class="grey">bis <?=$banlist['TIME']; ?> Uhr</small>
					</td>

					<td>
						<?=$banlist['REASON']; ?>
					</td>

					<td>
						<a href="../user.php?id=<?=$banlist['BY_ID']; ?>"><?=$banlist['BY']; ?></a>
					</td>

					<td>
						<a href="banlist-new.php?id=<?=$banlist['ID']; ?>" class="button greyB">bearbeiten</a>
						<a href="banlist.php?delete=<?=$banlist['ID']; ?>&u=<?=$banlist['USER_ID']; ?>" class="button redB">löschen</a>
					</td>
				</tr>
	<?php
			endforeach;
		endif;
	?>
</table>

<div style="padding:5px">
	<?php if (template::getVar('PAGES_NUM') > '1'): ?> | Seite <?=template::getVar('PAGE'); ?> von <?=template::getVar('PAGES_NUM'); ?> | <?=template::getVar('PAGES'); ?><?php endif; ?>
</div>

<?php template::display('footer'); ?>