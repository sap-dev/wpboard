<?php template::display('header'); ?>
<script type="text/javascript">
	$(function() {
		$('select[name=is_category]').change(function() {
			var v = $(this).val();

			if (v == '1') { // category
				$('#description, #topforum').fadeOut();
			} else if (v == '2') { //subforum
				$('#description, #topforum').fadeIn();
			} else { // forum
				$('#description').fadeIn();
				$('#topforum').fadeOut();
			}
		});
	});
</script>

<form action="forum-new.php?id=<?=template::getVar('ID'); ?>&menu=<?=template::getVar("menu");?>" method="post">
	<div class="h2box noBottom">
		<h1>Neues Forum</h1>
	</div>

	<table cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%" valign="top"><br />Art:</td>
			<td width="60%" valign="top"><br />
				<select name="is_category" class="select">
					<option value="1"<?php if (template::getVar('IS_CATEGORY')): ?> selected<?php endif; ?>>Foren-Kategorie</option>
					<option value="0"<?php if (!template::getVar('IS_CATEGORY')): ?>selected<?php endif; ?>>Forum</option>
					<option value="2"<?php if (!template::getVar('IS_CATEGORY') && template::getVar('IS_SUBFORUM')): ?>selected<?php endif; ?>>Unterforum</option>
				</select>
			</td>
		</tr>

		<tr>
			<td width="40%">Name:</td>
			<td width="60%"><input type="text" size="30" value="<?=template::getVar('NAME'); ?>" name="name" /></td>
		</tr>

		<tr id="description"<?php if (template::getVar('IS_CATEGORY')): ?> style="display: none;"<?php endif; ?>>
			<td>Beschreibung:</td>
			<td><input type="text" size="30" value="<?=template::getVar('DESCRIPTION'); ?>" name="description" /></td>
		</tr>

		<tr id="topforum"<?php if (template::getVar('IS_SUBFORUM') == '0'): ?> style="display: none;"<?php endif; ?>>
			<td>Unterforum von:</td>
			<td>
				<select name="topforum">
					<?php
						if (isset(template::$blocks['forums'])):
							foreach(template::$blocks['forums'] as $forums):
					?>
								<option value="<?=$forums['ID']; ?>"><?=$forums['NAME']; ?></option>
					<?php
							endforeach;
						endif;
					?>
				</select>
			</td>
		</tr>

		<tr class="noBorder">
			<td>Forum nur sichtbar für:</td>
			<td>
				<input type="radio" name="level" id="level0" value="0"<?php if (template::getVar('LEVEL') == '0'): ?> checked<?php endif; ?> /> <label for="level0">Jeden</label>
				<input type="radio" name="level" id="level1" value="1"<?php if (template::getVar('LEVEL') == '1'): ?> checked<?php endif; ?> /> <label for="level1">Mitglieder</label>
				<input type="radio" name="level" id="level2" value="2"<?php if (template::getVar('LEVEL') == '2'): ?> checked<?php endif; ?> /> <label for="level2">Moderatoren</label>
				<input type="radio" name="level" id="level3" value="3"<?php if (template::getVar('LEVEL') == '3'): ?> checked<?php endif; ?> /> <label for="level3">Administratoren</label>
			</td>
		</tr>

		<tr class="noBorder">
			<td></td>
			<td><input type="checkbox" class="checkbox" name="closed" id="closed" value="1"<?php if (template::getVar('CLOSED')): ?> checked<?php endif; ?> /> <label for="closed">Forum geschlossen</label></td>
		</tr>
	</table>

	<div style="text-align: right; margin-top: 10px;">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>

<?php template::display('footer'); ?>