<?php template::display('header'); ?>

<form action="search.php" method="get">
	<input type="text" name="query" style="width: 98%; padding: 10px; font-size: 28px; border: 0; border-bottom: 1px solid #e0e0e0;" placeholder="Suchbegriff" />

	<br /><br />

	<table cellpadding="5" cellspacing="0" width="100%" class="two-column">
		<tr>
			<td valign="top">
				Suche in Forum:
			</td>
			
			<td>
				<select name="forum" size="1">
				<option value="">-- Alle Foren --</option>

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
		<tr>
			<td>von Mitglied:</td>
			<td><input type="text" size="30" name="user" /></td>
		</tr>
		<tr>
			<td>Gruppieren nach:</td>
			<td>
				<input type="radio" name="group" value="p" id="group_p" checked /> <label for="group_p">Beiträgen</label> &nbsp; 
				<input type="radio" name="group" value="t" id="group_t" /> <label for="group_t">Themen</label>
			</td>
		</tr>
	</table>

	<div style="text-align: right;">
		<input type="submit" value="Suchen" />
	</div>
</form>

<?php template::display('footer'); ?>