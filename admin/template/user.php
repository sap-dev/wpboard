<?php template::display('header'); ?>

<?php if (template::getVar('ERROR')): ?>
	<div class="info">
		<?php if (template::getVar('ERROR') == '1'): ?>		Der Username darf keine Sonderzeichen, Umlaute oder Leerzeichen enthalten
		<?php elseif (template::getVar('ERROR') == '2'): ?>	Der Username muss 3 - 15 Zeichen lang sein
		<?php elseif (template::getVar('ERROR') == '3'): ?>	Der Username ist schon vergeben
		<?php elseif (template::getVar('ERROR') == '4'): ?>	Die Email ist ungültig
		<?php elseif (template::getVar('ERROR') == '5'): ?>	Die Email ist schon vergeben
		<?php elseif (template::getVar('ERROR') == '6'): ?>	Das Passwort muss mindestens 6 Zeichen lang sein
		<?php elseif (template::getVar('ERROR') == '7'): ?>	Die Daten wurden erfolgreich gespeichert
		<?php elseif (template::getVar('ERROR') == '8'): ?>	Das Mitglied wurde freigeschaltet
		<?php endif; ?>
	</div>
<?php endif; ?>

<div class="h2box">
	<h1><?=template::getVar('USER_USERNAME'); ?> <span class="light">bearbeiten</span></h1>
</div>

<h2 class="title">Allgemein</h2>

<form action="user.php?id=<?=template::getVar('ID'); ?>&menu=<?=template::getVar("menu");?>" method="post">
	<table class="two-column" cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%">Status:</td>
			<td style="color: green"><?php if (template::getVar('UNLOCK')): ?><a href="user.php?id=<?=template::getVar('ID'); ?>&unlock=1">Mitglied freischalten</a><?php else: ?>Freigeschaltet<?php endif; ?></td>
		</tr>

		<tr>
			<td>Rolle:</td>
			<td>
				<input type="radio" name="level" id="level0" value="<?=template::getVar('LEVEL_USER'); ?>"<?php if (template::getVar('LEVEL') == template::getVar('LEVEL_USER')): ?> checked<?php endif; ?> /> <label for="level0">Mitglied</label> 
				<input type="radio" name="level" id="level1" value="<?=template::getVar('LEVEL_MOD'); ?>"<?php if (template::getVar('LEVEL') == template::getVar('LEVEL_MOD')): ?> checked<?php endif; ?>  /> <label for="level1">Moderator</label> 
				<input type="radio" name="level" id="level2" value="<?=template::getVar('LEVEL_ADMIN'); ?>"<?php if (template::getVar('LEVEL') == template::getVar('LEVEL_ADMIN')): ?> checked<?php endif; ?>  /> <label for="level2">Administrator</label> 

				<?php if (template::getVar('ID') == '1'): ?>
					<br /><br /><span class="grey">Gründer - Rechte können nicht geändert werden!</span>
				<?php endif; ?>
			</td>
		</tr>

		<tr>
			<td>Rang:</td>
			<td>
				<select name="rank">
					<option value="0"<?php if (!template::getVar('RANK')): ?> checked<?php endif; ?>>---</option>
					<?php
						if (isset(template::$blocks['ranks'])):
							foreach(template::$blocks['ranks'] as $ranks):
					?>
								<option value="<?=$ranks['ID']; ?>"<?php if ($ranks['ID'] == template::getVar('RANK')): ?>  selected<?php endif; ?>><?=$ranks['TITLE']; ?></option>
					<?php
							endforeach;
						endif;
					?>
				</select>
			</td>
		</tr>

		<tr>
			<td width="220">IP:</td>
			<td><b><?=template::getVar('IP'); ?></b></td>
		</tr>

		<tr>
			<td>Benutzername:</td>
			<td><input value="<?=template::getVar('USER_USERNAME'); ?>" size="30" type="text" name="username" /></td>
		</tr>

		<tr class="noBorder">
			<td>E-Mail:</td>
			<td><input value="<?=template::getVar('EMAIL'); ?>" size="30" type="text" name="email" /></td>
		</tr>
	</table>

	<br /><br />
	<h2 class="title">Profil</h2>

	<table class="two-column">
		<tr>
			<td width="40%">Punkte:</td>
			<td><input value="<?=template::getVar('POINTS'); ?>" size="30" type="number" name="points" /></td>
		</tr>

		<tr>
			<td>Homepage:</td>
			<td><input value="<?=template::getVar('WEBSITE'); ?>" size="30" type="text" name="website" /></td>
		</tr>
		<tr>
			<td>ICQ:</td>
			<td><input value="<?=template::getVar('ICQ'); ?>" size="30" type="text" name="icq" /></td>
		</tr>

		<tr>
			<td>Skype:</td>
			<td><input value="<?=template::getVar('SKYPE'); ?>" size="30" type="text" name="skype" /></td>
		</tr>

		<tr>
			<td valign="top">Signatur:</td>
			<td><textarea name="signatur" cols="50" rows="4"><?=template::getVar('SIGNATUR'); ?></textarea></td>
		</tr>

		<tr class="noBorder">
			<td valign="top">Avatar:</td>
			<td>
				<img src="../images/avatar/<?=template::getVar('AVATAR'); ?>" alt="Avatar" /><br />
				
				<input id="avatar" value="1" type="checkbox" name="avatar" />
				<label for="avatar">Avatar löschen</label>
			</td>
		</tr>
	</table>

	<br /><br />
	<h2 class="title">Passwort</h2>

	<table class="two-column">
		<tr>
			<td width="40%">Neues Passwort:</td>
			<td><input size="30" type="password" name="password" /></td>
		</tr>

		<tr>
			<td>wiederholen:</td>
			<td valign="top"><input size="30" type="password" name="password2" /></td>
		</tr>
	</table>

	<br />
	<div style="text-align:right;">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>

<?php template::display('footer'); ?>