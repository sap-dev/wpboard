<?php template::display('header'); ?>

<h1 class="title">
	E-Mail bestätigen
</h1>

<form action="register.php?u=<?=template::getVar('LOCK_USER_ID'); ?>" method="post">
	<?php if (template::getVar('ERROR') == '1'): ?>
		<div class="info">Der Bestätigungscode stimmt nicht</div>
	<?php endif; ?>

		<center>
			Hey <b><?=template::getVar('LOCK_USERNAME'); ?></b>,<br />um die Registrierung abzuschlie&szlig;en, musst Du Deine E-Mail bestätigen!
			<br />An deine E-Mail wurde ein Bestätigungsschlüssel gesendet.<br />

			<br />
			<table align="center" cellspacing="0" cellpadding="7">
				<tr>
					<td width="100" align="right">Bestätigungsschlüssel:</td>
					<td width="100"><input size="10" maxlength="6" name="token" value="<?=template::getVar('TOKEN'); ?>" type="text" /></td>
				</tr>
			</table>
			<br /><input type="submit" name="submit_unlock" value="Registrierung abschlie&szlig;en" />

		</center>
	</div>
</form>

<?php template::display('footer'); ?>