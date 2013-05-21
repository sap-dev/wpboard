<?php template::display('header'); ?>

<?php if (template::getVar('ERROR')): ?>
	<div class="info">
		<?php if (template::getVar('ERROR') == '1'): ?>		Bitte alle Felder ausfüllen.
		<?php elseif (template::getVar('ERROR') == '2'): ?>	Der Benutzername darf keine Sonderzeichen, Umlaute oder Leerzeichen enthalten
		<?php elseif (template::getVar('ERROR') == '3'): ?>	Der Benutzername muss 3 - 15 Zeichen lang sein
		<?php elseif (template::getVar('ERROR') == '4'): ?>	Der Benutzername ist nicht erlaubt
		<?php elseif (template::getVar('ERROR') == '5'): ?>	Der Benutzername ist schon vergeben
		<?php elseif (template::getVar('ERROR') == '6'): ?>	Die E-Mail ist ungültig
		<?php elseif (template::getVar('ERROR') == '7'): ?>	Die Email ist schon vergeben
		<?php elseif (template::getVar('ERROR') == '8'): ?>	Das Passwort muss mindestens 6 Zeichen lang sein
		<?php elseif (template::getVar('ERROR') == '9'): ?>	Die Passöwrter sind nicht gleich
		<?php elseif (template::getVar('ERROR') == '10'): ?>	Der Sicherheitscode ist falsch
		<?php endif; ?>
	</div>

	<br /><br /><br /><br />
<?php endif; ?>

<form action="register.php" method="post">
	<div id="register">
		<div class="title">
			<h2>Willkommen bei <?=template::getVar('PAGE_TITLE'); ?>!</h2>
		</div>

		<div class="inputs">
			<input type="text" name="username" value="<?=template::getVar('USER_USERNAME'); ?>" placeholder="Benutzername" />
			<input type="email" name="email" value="<?=template::getVar('EMAIL'); ?>" placeholder="E-Mail-Adresse" />
			<input type="password" name="password" value="<?=template::getVar('PASSWORD'); ?>" placeholder="Passwort" />
			<input type="password" name="password2" value="<?=template::getVar('PASSWORD'); ?>" placeholder="Passwort wiederholen" />
		
			<?php if (template::getVar('ENABLE_CAPTCHA')): ?>
				<br />
				<br />

				<a href="#" onclick="this.firstChild.src = 'lib/captcha.php?' + new Date().getTime();return false;"><img style="padding:4px 0;" border="0" id="captcha" src="lib/captcha.php" /></a><br />
				<input name="captcha" type="text" />
			<?php endif; ?>
		</div>

		<div class="submit">
			<input type="submit" name="submit" value="Registrieren" />
		</div>
	</div>
</form>

<?php template::display('footer'); ?>