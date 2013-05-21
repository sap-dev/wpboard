<?php template::display('header'); ?>

<?php if (template::getVar('ERROR')): ?>
	<div class="info">
		<?php if (template::getVar('ERROR') == '1'): ?>	Die Zugangsdaten stimmen nicht - Versuche es bitte erneut
		<?php elseif (template::getVar('ERROR') == '2'): ?>	Du wurdest ausgeloggt - Du warst <?=template::getVar('TIME'); ?> Minute<?php if (template::getVar('TIME') != 1): ?>n<?php endif; ?> online - Es ist <?=template::getVar('TIME_HI'); ?> Uhr
		<?php elseif (template::getVar('ERROR') == '3'): ?>	Logge dich ein, um diese Seite zu sehen
		<?php endif; ?>
	</div>

	<br /><br /><br /><br />
<?php endif; ?>

<div id="login">
	<form action="login.php" method="post">
		<div class="title">
			<h2>Willkommen zurück!</h2>
		</div>

		<div class="inputs">
			<input type="text" name="username" placeholder="Benutzername" />
			<input type="password" name="password" placeholder="Passwort" />

			<br /><br />
			<input type="checkbox" id="merke" name="merke" checked="checked" /> <label for="merke">Eingeloggt bleiben</label>
		</div>

		<div class="links">
			<a href="./password.php">Passwort vergessen?</a> &minus; <a href="./register.php">Mitglied werden</a>
		</div>

		<div class="submit">
			<input type="submit" value="Anmelden" />
		</div>

		<input type="hidden" name="redirect" value="<?=template::getVar('REDIRECT'); ?>" />
	</form>
</div>

<?php template::display('footer'); ?>