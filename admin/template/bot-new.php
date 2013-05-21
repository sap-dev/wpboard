<?php template::display('header'); ?>

<?php if (template::getVar('ERROR') == '1'): ?>
	<div class="info">Du musst einen Bot-Namen und Bot-Agent eingeben.</div>
<?php endif; ?>

<form action="bot-new.php?id=<?=template::getVar('ID'); ?>&menu=<?=template::getVar("menu");?>" method="post">
	<div class="h2box noBottom">
		<h1>Neuer Bot</h1>
	</div>

	<table cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%">Bot-Name:</td>
			<td width="60%"><input type="text" size="30" value="<?=template::getVar('NAME'); ?>" name="name" /></td>
		</tr>

		<tr>
			<td>Bot-Agent:</td>
			<td><input type="text" size="30" value="<?=template::getVar('AGENT'); ?>" name="agent" /></td>
		</tr>
	</table>

	<div style="text-align: right; margin-top: 10px;">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>

<?php template::display('footer'); ?>