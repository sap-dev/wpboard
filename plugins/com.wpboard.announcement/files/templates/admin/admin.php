<?php if (template::getVar('MELDUNG') == '1'): ?>
	<div class="info">Einstellungen wurden gespeichert.</div>
<?php endif; ?>

<form action="plugins.php?manage&plugin=com.wpboard.announcement&menu=<?=template::getVar('menu');?>" method="post">
	<div class="h2box noBottom">
		<h1>Infobox Startseite</h1>
	</div>

	<table cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%" valign="top">Text:</td>
			<td width="60%" valign="top"><textarea name="text" cols="40" rows="10"><?=template::getVar('TEXT'); ?></textarea></td>
		</tr>

		
	</table>

	<div style="text-align: right; margin-top: 10px;">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>
