<?php template::display('header'); ?>

<form action="password.php" method="post">
	<?php if (template::getVar('ERROR')): ?>
		<div class="info">
			<?php
			switch(template::getVar('ERROR')) {
				case '1': echo "Der Link stimmt nicht"; break;
				case '2': echo "Benutzername und Email stimmen nicht überein"; break;
				case '3': echo "Das neue Passwort muss aus mindestens 3 Zeichen bestehen"; break;
				case '4': echo "Die neuen Passwörter sind nicht gleich"; break;
				default:  echo "Unbekannter Fehler"; break;
			}
			?>
		</div>

		<br /><br /><br /><br /><br />
	<?php endif; ?>

	<div id="login">
		<div class="title">
			<h2>Passwort vergessen?</h2>
		</div>

		<div class="inputs">
			<input type="text" name="user" placeholder="Benutzername" />
			<input type="email" name="email" placeholder="E-Mail-Adresse" />
			<br /><br />

			<input type="password" name="pw" placeholder="Neues Passwort" />
			<input type="password" name="pw2" placeholder="Neues Passwort wiederholen" />
		</div>

		<div class="submit">
			<input type="submit" name="send" value="Neues Passwort" />
		</div>
	</div>
</form>

<?php template::display('footer'); ?>