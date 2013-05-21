<?php template::display('header'); ?>

<div id="writePost">
	<form action="newtopic.php?edit=<?=template::getVar('EDIT'); ?>&id=<?=template::getVar('ID'); ?>" method="post" name="eintrag">
		<div class="fLeft actionsL" style="width: 80%;">
			<h1>
				<a href="./forum.php">Forum</a> &rsaquo;
				<a href="./viewforum.php?id=<?=template::getVar('FORUM_ID'); ?>"><?=template::getVar('FORUM_NAME'); ?></a> &rsaquo;
				<input type="text" name="title" value="<?=template::getVar('TITLE'); ?>" size="40" />
			</h1>
		</div>

		<div class="fRight actionsR noP" style="width: 20%;">
			<input type="submit" name="preview" class="button greyB" value="Vorschau" />
			<input type="submit" name="submit" class="button" value="Absenden" />
		</div>

		<div class="clear"></div>
		
		<?php if (template::getVar('ERROR')): ?>
			<br />
			<div class="info">
				<?php if (template::getVar('ERROR') == '1'): ?>		Du musst einen Text eingeben
				<?php elseif (template::getVar('ERROR') == '2'): ?>	Pro Tag darfst du nur <?=template::getVar('POSTS_PERDAY'); ?> Beiträge schreiben
				<?php elseif (template::getVar('ERROR') == '3'): ?>	Das Thema ist geschlossen
				<?php elseif (template::getVar('ERROR') == '4'): ?>	Du musst einen Titel eingeben
				<?php elseif (template::getVar('ERROR') == '5'): ?>	Das Forum ist geschlossen
				<?php elseif (template::getVar('ERROR') == '6'): ?>	Die Umfrage muss mindestens 2 Antworten enhalten
				<?php elseif (template::getVar('ERROR') == '7'): ?>	Die Umfrage darf nur 30 Antworten enhalten
				<?php elseif (template::getVar('ERROR') == '8'): ?>	Der Text darf maximal <?=template::getVar('MAX_CHARS'); ?> Zeichen lang sein
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if (template::getVar('PREVIEW')): ?>
			<div class="preview">
				<div class="title">Vorschau</div>
				
				<?=template::getVar('PREVIEW'); ?>
			</div>
		<?php endif; ?>

	
		<div class="editor">
			<div class="bbcodes">
				<?php template::display('bbcodes'); ?>
			</div>

			<div class="write">
				<textarea name="text" id="postContent" rows="12"><?=template::getVar('TEXT'); ?></textarea>
			</div>
		</div>
		<?php if (template::getVar('IS_MOD')): ?>
		<div class="options">
			<div class="title">
				Makierung:
			</div>

			<div class="content">
				<select name="label"><?=template::getVar('LABELS');?></select>
			</div>

			<div class="clear"></div>
		</div>
		<? endif; ?>
		<div class="options">
			<div class="title">
				Optionen:
			</div>

			<div class="content">
				<input id="option1" type="checkbox" value="1" name="bbcodes"<?php if (template::getVar('BBCODES')): ?> checked<?php endif; ?> />
				<label for="option1">BBCodes ausschalten</label>

				&nbsp;&nbsp;

				<input id="option2" type="checkbox" value="1" name="smilies"<?php if (template::getVar('SMILIES')): ?> checked<?php endif; ?>/>
				<label for="option2">Smilies ausschalten</label>

				&nbsp;&nbsp;

				<input id="option3" type="checkbox" value="1" name="urls"<?php if (template::getVar('URLS')): ?> checked<?php endif; ?>/>
				<label for="option3">URLs nicht verlinken</label>

				&nbsp;&nbsp;

				<input id="option4" type="checkbox" value="1" name="signatur"<?php if (template::getVar('SIGNATUR')): ?> checked<?php endif; ?>/>
				<label for="option4">Signatur anhängen</label>
			</div>

			<div class="clear"></div>
		</div>

		<div class="poll">
			<div class="title">
				Umfrage:
			</div>

			<div class="content" style="padding-top: 0;">
				<table width="100%" cellspacing="0" cellpadding="5" border="0">
					<tr>
						<td width="130">
							Frage:
						</td>

						<td>
							<input name="poll_title" value="<?=template::getVar('POLL_TITLE'); ?>" type="text" size="40" />
						</td>
					</tr>

					<tr>
						<td width="130">
							Antworten:<br />
							<small class="grey">
								Gib in jeder Zeile eine Antwort ein. Maximal 30 Antworten.
							</small>
						</td>

						<td>
							<textarea cols="60" rows="5" name="poll_options"><?=template::getVar('POLL_OPTIONS'); ?></textarea>
						</td>
					</tr>

					<tr>
						<td>
							Ende:
						</td>

						<td>
							In <input type="number" name="poll_days" value="<?=template::getVar('POLL_DAYS'); ?>" size="3" /> Tagen
							<small class="grey">(0 wenn es nie enden soll)</small>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</form>
</div>


<?php template::display('footer'); ?>