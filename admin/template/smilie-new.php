<?php template::display('header'); ?>

<form action="smilie-new.php?id=<?=template::getVar('ID'); ?>&menu=<?=template::getVar("menu");?>" method="post">
	<div class="h2box noBottom">
		<h1>Neuer Smiley</h1>
	</div>

	<table class="form" cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%">Smilie:<br /><small class="grey">zum Beispiel: =)</small></td>
			<td width="60%"><input value="<?=template::getVar('EMOTION'); ?>" type="text" size="30" name="emotion" /></td>
		</tr>

		<tr>
			<td>Bild:</td>
			<td>
				<table border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td width="32px" class="smileyContainer" style="<?php if (!template::getVar('SMILIE')): ?> display:none<?php endif; ?>">
							<img id="smilie" style="display: block;<?php if (!template::getVar('SMILIE')): ?> display:none<?php endif; ?>" src="../images/smilies/<?=template::getVar('SMILIE'); ?>" />
						</td>

						<td>
							<select name="smilie" onchange="if ($(this).val()) { $('#smilie').attr('src', '../images/smilies/' + $(this).val()).show(); $('.smileyContainer').show(); } else { $('#smilie, .smileyContainer').hide(); }">
								<option<?php if (!template::getVar('SMILIE')): ?> selected<?php endif; ?> value="">---</option>

								<?php
									if (isset(template::$blocks['smilies'])):
										foreach(template::$blocks['smilies'] as $smilies):
								?>
											<option<?php if ($smilies['SMILIE'] == 'SMILIE'): ?>  selected<?php endif; ?>><?=$smilies['SMILIE']; ?></option>
								<?php
										endforeach;
									endif;
								?>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div style="text-align: right; margin-top: 10px;">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>

<?php template::display('footer'); ?>