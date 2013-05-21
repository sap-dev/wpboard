<?php template::display('header'); ?>

<?php if (template::getVar('ERROR') == '1'): ?>
	<div class="info">Du musst einen Server-Namen und eine Server-URL eingeben.</div>
<?php endif; ?>

<form action="server-new.php?id=<?=template::getVar('ID'); ?>&menu=<?=template::getVar("menu");?>" method="post">
	<div class="h2box noBottom">
		<?php if (template::getVar('ID') == 0): ?>
			<h1>Neuer Plugin-Server</h1>
		<?php else: ?>
			<h1>Plugin-Server bearbeiten</h1>
		<?php endif; ?>
	</div>

	<table cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%">Server-Name:</td>
			<td width="60%"><input type="text" size="30" value="<?=template::getVar('NAME'); ?>" name="name" /></td>
		</tr>

		<tr>
			<td>Server-URL:</td>
			<td><input type="text" size="30" value="<?=template::getVar('URL'); ?>" name="url" /></td>
		</tr>
	</table>

	<div style="text-align: right; margin-top: 10px;">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>

<?php template::display('footer'); ?>