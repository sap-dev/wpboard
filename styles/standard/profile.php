<?php template::display('header'); ?>
<table width="100%"><tr><td valign="top" width="25%">
<h2>Einstellungen</h2>
<div class="sections" style="margin-bottom: 0;">
	<ul>
		<li><a href="profile.php" <?php if (!template::getVar('MODE')): ?>class="active"<?php endif; ?>><img src="http://www.woltlab.com/forum/wcf/icon/profileM.png" style="vertical-align: middle;"> Profil</a></li>
		<li><a href="profile.php?mode=avatar" <?php if (template::getVar('MODE') == 'avatar'): ?>class="active"<?php endif; ?>><img src="http://www.woltlab.com/forum/wcf/icon/avatarM.png" style="vertical-align: middle;"> Avatar</a></li>
		<li><a href="profile.php?mode=signatur" <?php if (template::getVar('MODE') == 'signatur'): ?>class="active"<?php endif; ?>><img src="http://www.woltlab.com/forum/wcf/icon/wysiwyg/insertCodeM.png" style="vertical-align: middle;"> Signatur</a></li>
		<li><a href="profile.php?mode=account" <?php if (template::getVar('MODE') == 'account'): ?>class="active"<?php endif; ?>><img src="http://www.woltlab.com/forum/icon/managementM.png" style="vertical-align: middle;"> Account</a></li>

		<?php if (template::getVar('ENABLE_DELETE')): ?>
			<li><a href="profile.php?mode=delete" <?php if (template::getVar('MODE') == 'delete'): ?>class="active"<?php endif; ?>>Mitgliedschaft beenden</a></li>
		<?php endif; ?>

	</ul>
</div>
</td><td valign="top" width="75%"><br />
<div class="tabs noJS" style="width: 100%;">
	<div class="content">
		<div id="profile" class="tabContent">
		
			<?php if (template::getVar('MODE') == 'signatur'): ?>
				<form action="profile.php" method="post">
				<textarea name="signatur" style="width: 590px; height: 100px;" placeholder="Signatur..."><?=template::getVar('SIGNATUR'); ?></textarea>
				<div style="text-align: right;">
						<br /><input type="submit" name="form_profil" value="Speichern" />
					</div>
			
			<?php elseif (template::getVar('MODE') == 'avatar'): ?>

				<?php if (template::getVar('ERROR')): ?>

				<div class="info">

					<?php if (template::getVar('ERROR') == '1'): ?>		Das Format ist nicht erlaubt
					<?php elseif (template::getVar('ERROR') == '2'): ?>	Der Avatar muss mindestens <?=template::getVar('MIN_WIDTH'); ?> Pixel breit und <?=template::getVar('MIN_HEIGHT'); ?> hoch sein
					<?php endif; ?>

				</div>
				<div class="info_a"></div>

				<?php endif; ?>

				<form enctype="multipart/form-data" action="profile.php?mode=avatar" method="post">
					<div style="width: 500px; margin: auto;">
						<div class="fLeft" style="width: 180px">
							<img class="img" src="images/avatar/<?=template::getVar('AVATAR'); ?>" width="150px" height="150px" />

							<div style="position: absolute; margin-top: -18.5px; margin-left: -5px;">
								<?php if (template::getVar('AVATAR') != template::getVar('DEFAULT_AVATAR')): ?><a href="profile.php?mode=avatar&delete=1" class="button redB">Avatar löschen</a><?php endif; ?>
							</div>
						</div>

						<div class="fRight" style="width: 320px">
							<h2 class="title" style="padding: 0; border: 0;">Neuen Avatar hochladen</h2>
							<input size="20" type="file" name="file" />

							<br />

							<small class="grey">
								.jpg; .jpeg; .png; .gif
							</small>

							<br /><br />

							<input type="submit" name="submit" value="Hochladen" />
						</div>

						<div class="clear"></div>
					</div>
				</form>

			<?php elseif (template::getVar('MODE') == 'account'): ?>

				<?php if (template::getVar('ERROR') == '1'): ?>

				<div class="info">
					<?php if (template::getVar('ERROR') == '1'): ?>		Das aktuelle Passwort stimmt nicht
					<?php elseif (template::getVar('ERROR') == '2'): ?>	Die Email ist ungültig
					<?php elseif (template::getVar('ERROR') == '3'): ?>	Die Email ist schon vergeben
					<?php elseif (template::getVar('ERROR') == '4'): ?>		Das aktuelle Passwort stimmt nicht
					<?php elseif (template::getVar('ERROR') == '5'): ?>	Die Passwörter sind nicht gleich
					<?php elseif (template::getVar('ERROR') == '6'): ?>	Das Passwort muss mindestens 6 Zeichen lang sein
					<?php endif; ?>
				</div>

				<?php endif; ?>

				<div class="fLeft" style="width:100%;">
					<h1>E-Mail-Adresse ändern</h1>

					<form action="profile.php?mode=account" method="post">
						<table cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<td width="40%">Aktuelle E-Mail-Adresse:</td>
								<td width="60%" style="padding: 10px;" height="32px" class="grey"><?=template::getVar('USER_EMAIL'); ?></td>
							</tr>

							<tr>
								<td>Neue E-Mail-Adresse:</td>
								<td><input name="email" type="email" style="width:95%;" /></td>
							</tr>

							<tr>
								<td>Aktuelles Passwort:</td>
								<td><input name="password" type="password" style="width:95%;" /></td>
							</tr>

							<tr>
								<td colspan="2" align="right">
									<input type="submit" name="form_email" value="E-Mail-Adresse ändern" />
								</td>
							</tr>
						</table><br />
						<h1>Passwort ändern</h1>

					<form name="eintrag" enctype="multipart/form-data" action="profile.php?mode=account" method="post">
						<table cellpadding="5" cellspacing="0" width="100%">
							<tr>
								<td width="40%">Neues Passwort:</td>
								<td width="60%"><input name="password" type="password" style="width:95%;"></td>
							</tr>

							<tr>
								<td>Passwort wiederholen:</td>
								<td><input name="password2" type="password" style="width:95%;"></td>
							</tr>

							<tr>
								<td>Aktuelles Passwort:</td>
								<td><input name="oldpassword" type="password" style="width:95%;"></td>
							</tr>

							<tr>
								<td colspan="2" align="right">
									<input type="submit" name="form_pw" value="Passwort ändern" />
								</td>
							</tr>
						</table>
					</form>
					</form>
				</div>

				<div class="clear"></div>

			<?php elseif (template::getVar('MODE') == 'delete'): ?>

				<?php if (template::getVar('ERROR')): ?>

				<div class="info">
					<?php if (template::getVar('ERROR') == '1'): ?>		Das aktuelle Passwort stimmt nicht.
					<?php elseif (template::getVar('ERROR') == '2'): ?>	Die Passwörter sind nicht gleich.
					<?php elseif (template::getVar('ERROR') == '3'): ?>	Gründer können das Konto nicht im Benutzerpanel löschen.
					<?php endif; ?>
				</div>

				<?php endif; ?>

				<?php if (template::getVar('USERID') != 1) : ?>
				<form name="eintrag" enctype="multipart/form-data" action="profile.php?mode=delete" method="post">
					<div class="info">
						Mitgliedschaft besteht seit <span><?=template::getVar('REGISTER'); ?> Uhr</span>
						<br />
						<br />

						Dein kompletter Account wird gelöscht.
						Beiträge im Forum werden nicht gelöscht.<br />
						Achtung: Diese Aktion kann nicht rückgängig gemacht werden!
						Gelöscht ist gelöscht!
					</div>

					<br />

					<table border="0" cellspacing="0" cellpadding="5" width="40%" style="margin: auto;">
						<tr>
							<td align="right">Aktuelles Passwort:</td>
							<td><input name="password" type="password" size="30" /></td>
						</tr>

						<tr>
							<td align="right">wiederholen:</td>
							<td><input name="password2" type="password" size="30" /></td>
						</tr>

						<tr>
							<td></td>
							<td>
								<input type="submit" name="form_delete" value="Meinen Account endgültig löschen" />
							</td>
						</tr>
					</table>
				</form>
				<?php else: ?>
				<div class="info">
					Mitgliedschaft besteht seit <span><?=template::getVar('REGISTER'); ?> Uhr</span>
					<br />
					<br />

					Gründer können ihr Konto aus Sicherheitsgründen nicht löschen.
				</div>
				<?php endif; ?>

			<?php else: ?>

				<form action="profile.php" method="post">
					<table align="center" cellpadding="10" cellspacing="0" width="100%">
						<tr>
								<td width="10%" valign="top">
								<table class="userProfile">
									<tr>
										<td width="160px"><span>Homepage:</span></td>
										<td width="70%">
											<input name="website" value="<?=template::getVar('WEBSITE'); ?>" style="width: 100%;" type="text" />
										</td>
									</tr>
									<tr>
										<td><span>ICQ:</span></td>
										<td>
											<input name="icq" value="<?=template::getVar('ICQ'); ?>" style="width:100%;" type="text" />
										</td>
									</tr>
									<tr>
										<td><span>Skype:</span></td>
										<td>
											<input name="skype" value="<?=template::getVar('SKYPE'); ?>" style="width:100%;" type="text" />
										</td>
									</tr>
									<tr>
										<td><span>Über mich:</span></td>
										<td>
											<textarea cols="70" rows="5" name="ueber"><?=template::getVar('UEBER'); ?></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								
								<div class="options">
									<div class="title">
										Optionen:
									</div>

									<div class="content">
										<input id="option2" type="checkbox" value="1" name="signatur_smilies" <?php if (!template::getVar('SIGNATUR_SMILIES')): ?>checked <?php endif; ?>/>
										<label for="option2">Smilies ausschalten</label>

										&nbsp;&nbsp;

										<input id="option1" type="checkbox" value="1" name="signatur_bbcodes" <?php if (!template::getVar('SIGNATUR_BBCODES')): ?>checked <?php endif; ?>/>
										<label for="option1">BBCodes ausschalten</label>

										&nbsp;&nbsp;

										<input id="option3" type="checkbox" value="1" name="signatur_urls" <?php if (!template::getVar('SIGNATUR_URLS')): ?>checked <?php endif; ?>/>
										<label for="option3">URLs nicht automatisch verlinken</label>
									</div>

									<div class="clear"></div>
								</div>
								</td>
								</tr>
								<tr>
								<td align="right">
									<input type="submit" name="form_profil" value="Speichern" />
								</td>
							</tr>
							</td>
						</tr>
					</table>

					
				</form>

			<?php endif; ?>
		</div>
	</div>
</div>
</td></tr></table>
<?php template::display('footer'); ?>
