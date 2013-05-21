<?php template::display('header'); ?>

<?php if (template::getVar('ERROR')): ?>
	<div class="info">
		<?php if (template::getVar('ERROR') == '1'): ?>		Du musst einen Usernamen und einen Grund eingeben
		<?php elseif (template::getVar('ERROR') == '2'): ?>	Das Zeitformat ist nicht in der Zukunft oder existiert nicht
		<?php elseif (template::getVar('ERROR') == '3'): ?>	Du kannst dich nicht selber sperren
		<?php elseif (template::getVar('ERROR') == '4'): ?>	Das Mitglied existiert nicht
		<?php elseif (template::getVar('ERROR') == '5'): ?>	Das Mitglied ist ein Administrator oder Moderator und kann nicht gesperrt werden
		<?php elseif (template::getVar('ERROR') == '6'): ?>	Für dieses Mitglied existiert bereits eine Sperre
		<?php endif; ?>
	</div>
<?php endif; ?>

<form method="post" action="banlist-new.php?id=<?=template::getVar('ID'); ?>&menu=<?=template::getVar("menu");?>">
	<div class="h2box noBottom">
		<h1>Mitglied sperren</h1>
	</div>

	<table cellpadding="5" cellspacing="0">
		<tr>
			<td width="20%">Username:</td>
			<td><input type="text" name="user" value="<?=template::getVar('BAN_USERNAME'); ?>" /></td>
		</tr>

		<tr>
			<td>sperren bis:</td>
			<td>
				<div style="width: 60%;" class="fLeft">
					<input name="day" type="number" value="<?=template::getVar('DAY'); ?>" style="width: 50px;" />.
					<input name="month" type="number" value="<?=template::getVar('MONTH'); ?>" style="width: 50px;" />.
					<input name="year" type="number" value="<?=template::getVar('YEAR'); ?>" style="width: 75px;" />
				</div>

				<div style="width: 40%; text-align: right;" class="fRight">
					<input name="hour" type="number" value="<?=template::getVar('HOUR'); ?>" style="width: 50px;" />:
					<input name="min" type="number" value="<?=template::getVar('MIN'); ?>" style="width: 50px;" />
					&nbsp; Uhr
				</div>

				<div class="clear"></div>
			</td>
		</tr>

		<tr>
			<td width="200">Grund:</td>
			<td><input type="text" name="reason" value="<?=template::getVar('REASON'); ?>" /></td>
		</tr>
	</table>

	<br />

	<div style="text-align: right;">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>

<?php template::display('footer'); ?>