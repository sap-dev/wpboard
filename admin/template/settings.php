<?php template::display('header'); ?>

<script type="text/javascript">
	$(function() {
		$('#defaultAvatar').change(function() {
			$('.defaultAvatar img').attr('src', '../images/avatar/' + $(this).val())
		});
	});
</script>

<?php if (template::getVar('ERROR')): ?>
	<div class="info">
		<?php if (template::getVar('ERROR') == '2'): ?>		Die E-Mail ist ungütig.
		<?php elseif (template::getVar('ERROR') == '3'): ?>	Du musst einen "Avatar der angezeigt wird wenn keiner hochgeladen wurde" angeben.
		<?php elseif (template::getVar('ERROR') == '1'): ?>	Die Einstellungen wurden gespeichert.
		<?php endif; ?>
	</div>
<?php endif; ?>

<form action="settings.php?menu=<?=template::getVar("menu");?>" method="post">
	<div class="h2box">
		<h1>Einstellungen</h1>

		<div class="clear"></div>
	</div>

	<h2 class="title">Allgemein</h2>

	<table class="two-column" cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%">Titel:</td>
			<td width="60%"><input type="text" value="<?=template::getVar('TITLE'); ?>" name="title" /></td>
		</tr>

		<tr>
			<td>Beschreibung:</td>
			<td><input type="text" value="<?=template::getVar('DESCRIPTION'); ?>" name="description" /></td>
		</tr>

		<tr>
			<td>E-Mail:</td>
			<td><input type="text" value="<?=template::getVar('EMAIL'); ?>" name="email" /></td>
		</tr>

		<tr>
			<td>Bots anzeigen in <i>Wer ist online<i>:</td>
			<td><input type="radio" value="1" id="enable_bots1" name="enable_bots"<?php if (template::getVar('ENABLE_BOTS')): ?> checked<?php endif; ?> /> <label for="enable_bots1">Ja</label> &nbsp; <input type="radio" value="0" id="enable_bots2" name="enable_bots"<?php if (!template::getVar('ENABLE_BOTS')): ?> checked<?php endif; ?> /> <label for="enable_bots2">Nein</label></td>
		</tr>

		<tr>
			<td>Mitgliedsschaft beenden erlauben:</td>
			<td><input type="radio" value="1" id="enable_delete1" name="enable_delete"<?php if (template::getVar('ENABLE_DELETE')): ?> checked<?php endif; ?> /> <label for="enable_delete1">Ja</label> &nbsp; <input type="radio" value="0" id="enable_delete2" name="enable_delete"<?php if (!template::getVar('ENABLE_DELETE')): ?> checked<?php endif; ?> /> <label for="enable_delete2">Nein</label></td>
		</tr>
	</table>

	<br /><br />

	<h2 class="title">Forum deaktivieren</h2>

	<table class="two-column">
		<tr>
			<td width="40%">Forum deaktivieren:</td>
			<td><input type="radio" value="1" id="enable1" name="enable"<?php if (template::getVar('ENABLE')): ?> checked<?php endif; ?> /> <label for="enable1">Ja</label> &nbsp; <input type="radio" value="0" id="enable2" name="enable"<?php if (!template::getVar('ENABLE')): ?> checked<?php endif; ?> /> <label for="enable2">Nein</label></td>
		</tr>

		<tr>
			<td>Text der angezeigt wird bei Deaktivierung:</td>
			<td><input type="text" value="<?=template::getVar('ENABLE_TEXT'); ?>" name="enable_text" /></td>
		</tr>
	</table>

	<br /><br />
	<h2 class="title">Punkte &amp; Feinschliff</h2>

	<table class="two-column">
		<tr>
			<td width="175px">Themen pro Seite:</td>
			<td><input type="number" value="<?=template::getVar('TOPICS_PERPAGE'); ?>" name="topics_perpage" /></td>
		</tr>

		<tr>
			<td>Beiträge pro Seite:</td>
			<td><input type="number" value="<?=template::getVar('POSTS_PERPAGE'); ?>" name="posts_perpage" /></td>
		</tr>

		<tr>
			<td>Punkte pro Thema:</td>
			<td><input type="number" value="<?=template::getVar('POINTS_TOPIC'); ?>" name="points_topic" /></td>
		</tr>

		<tr>
			<td>Punkte pro Beitrag:</td>
			<td><input type="number" value="<?=template::getVar('POINTS_POST'); ?>" name="points_post" /></td>
		</tr>
	</table>

	<br /><br />
	<h2 class="title">Spamschutz</h2>

	<table class="two-column">
		<tr>
			<td width="50%">Maximale Anzahl Beiträge/Nachrichten pro Tag:</td>
			<td width="50%"><input type="text" value="<?=template::getVar('POSTS_PERDAY'); ?>" name="posts_perday" /></td>
		</tr>

		<tr>
			<td>Maximale Anzahl an Zeichen im Text:<br /><span class="blue">Gib 0 ein für unendlich</span></td>
			<td valign="top"><input type="text" value="<?=template::getVar('MAX_POST_CHARS'); ?>" name="max_post_chars" /></td>
		</tr>

		<tr>
			<td>Captcha bei Registrierung aktivieren:</td>
			<td><input type="radio" value="1" id="enable_captcha1" name="enable_captcha"<?php if (template::getVar('ENABLE_CAPTCHA')): ?>checked<?php endif; ?> /> <label for="enable_captcha1">Ja</label> &nbsp; <input type="radio" value="0" id="enable_captcha2" name="enable_captcha"<?php if (!template::getVar('ENABLE_CAPTCHA')): ?> checked<?php endif; ?> /> <label for="enable_captcha2">Nein</label></td>
		</tr>

		<tr>
			<td>E-Mail-Bestätigung bei Registrierung:</td>
			<td><input type="radio" value="1" id="enable_unlock1" name="enable_unlock"<?php if (template::getVar('ENABLE_UNLOCK')): ?> checked<?php endif; ?> /> <label for="enable_unlock1">Ja</label> &nbsp; <input type="radio" value="0" id="enable_unlock2" name="enable_unlock"<?php if (!template::getVar('ENABLE_UNLOCK')): ?> checked<?php endif; ?> /> <label for="enable_unlock2">Nein</label></td>
		</tr>

		<tr>
			<td>
				Nicht freigeschaltete Mitglieder löschen nach:<br />
				<small class="grey" style="font-weight: 500;">Gib 0 ein um das Löschen zu deaktivieren</small>
			</td>

			<td valign="top">
				<input type="number" value="<?=template::getVar('UNLOCK_DELETE'); ?>" name="unlock_delete" />
				<div style="display:inline-block;">
					Tagen
				</div>
			</td>
		</tr>
	</table>

	<br /><br />
	<h2 class="title">Avatare</h2>

	<table class="two-column">
		<tr>
			<td width="50%">
				Avatar hochladen erlauben:
			</td>

			<td>
				<input type="radio" value="1" id="enable_avatars1" name="enable_avatars" <?php if (template::getVar('ENABLE_AVATARS')): ?> checked<?php endif; ?> />
				<label for="enable_avatars1">Ja</label> &nbsp;

				<input type="radio" value="0" id="enable_avatars2" name="enable_avatars"<?php if (!template::getVar('ENABLE_AVATARS')): ?> checked<?php endif; ?> />
				<label for="enable_avatars2">Nein</label>
			</td>
		</tr>

		<tr>
			<td>
				Avatar anzeigen wenn keiner hochgeladen wurde:<br />

				<small class="grey" style="font-weight: 500;">
					Datei darf mit keiner Zahl anfangen und muss im Ordner images/avatar und images/avatar/mini liegen.
				</small>
			</td>

			<td valign="top">
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<select name="default_avatar" style="width: 100%" id="defaultAvatar">
								<?php
									if (isset(template::$blocks['avatars'])):
										foreach(template::$blocks['avatars'] as $avatars):
								?>
											<option value="<?=$avatars['IMAGE']; ?>" <?php if ($avatars['IMAGE'] == 'DEFAULT_AVATAR'): ?> selected="selected"<?php endif; ?>><?=$avatars['IMAGE']; ?></option>
								<?php
										endforeach;
									endif;
								?>
							</select>
						</td>

						<td width="50px" class="defaultAvatar">
							<img src="../images/avatar/<?=template::getVar('DEFAULT_AVATAR'); ?>" alt="Default" width="50px" height="50px" />
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td>Minimale Breite x Höhe:</td>
			<td>
				<input type="number" value="<?=template::getVar('AVATAR_MIN_WIDTH'); ?>" name="avatar_min_width" /> x
				<input type="number" value="<?=template::getVar('AVATAR_MIN_HEIGHT'); ?>" name="avatar_min_height" />
			</td>
		</tr>

		<tr>
			<td>
				Maximale Breite x Höhe:
			</td>

			<td>
				<input type="number" value="<?=template::getVar('AVATAR_MAX_WIDTH'); ?>" name="avatar_max_width" /> x
				<input type="number" value="<?=template::getVar('AVATAR_MAX_HEIGHT'); ?>" name="avatar_max_height" />
			</td>
		</tr>
	</table>

	<br /><br />
	<h2 class="title">Private Nachrichten</h2>

	<table class="two-column">
		<tr>
			<td>Private Nachrichten Ordner Limit:</td>
			<td><input type="text" value="<?=template::getVar('MAIL_LIMIT'); ?>" name="mail_limit" /></td>
		</tr>
	</table>

	<br /><br />

	<div style="text-align:right">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>

<?php template::display('footer'); ?>