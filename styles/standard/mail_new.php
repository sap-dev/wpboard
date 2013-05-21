<?php template::display('header'); ?>

<div id="writePost">
	<form name="eintrag" action="mail.php?dir=<?=template::getVar('DIR'); ?>&mode=new" method="post">
		<div class="fLeft actionsL" style="width: 80%;">
			<h1>
				<a href="./mail.php">Mails</a> &rsaquo;
				Neue Mail an&nbsp;
				<input name="user" value="<?=template::getVar('TO_USER'); ?>" type="text" style="width: 300px;" />
			</h1>

			<h1 style="margin-top: 5px;">
				<span style="visibility:hidden">Mails &rsaquo;</span>
				Betreff:
				<input name="title" value="<?=template::getVar('TITLE'); ?>" type="text" style="width: 355px;" />
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
				<?php if (template::getVar('ERROR') == '1'): ?>		Pro Tag darfst du nur <?=template::getVar('PERDAY'); ?> Nachrichten schreiben
				<?php elseif (template::getVar('ERROR') == '2'): ?>	Der Empfänger existiert nicht
				<?php elseif (template::getVar('ERROR') == '3'): ?>	Du musst einen Betreff und einen Text eingeben	
				<?php elseif (template::getVar('ERROR') == '4'): ?>	Der Posteingang vom Empfänger ist voll
				<?php elseif (template::getVar('ERROR') == '5'): ?>	Dein Ordner Gesendete Nachrichten ist voll
				<?php elseif (template::getVar('ERROR') == '6'): ?>	Der Text darf maximal <?=template::getVar('MAX_CHARS'); ?> Zeichen lang sein
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
	</form>
</div>

<?php template::display('footer'); ?>