<?php template::display('header'); ?>

<form action="rank-new.php?id=<?=template::getVar('ID'); ?>&menu=<?=template::getVar("menu");?>" method="post">
	<div class="h2box noBottom">
		<h1>Neuer Rang</h1>
	</div>

	<table cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%">Rang:</td>
			<td width="60%"><input value="<?=template::getVar('TITLE'); ?>" type="text" size="30" name="title" /></td>
		</tr>

		<tr>
			<td>Bild:</td>
			<td>
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td style="<?php if (!template::getVar('ICON')): ?> display:none;<?php endif; ?>" class="rankContainer">
							<img id="icon" style="display:block; margin-top: 10px;<?php if (!template::getVar('ICON')): ?> display:none;<?php endif; ?>" src="../images/ranks/<?=template::getVar('ICON'); ?>" />
						</td>

						<td>
							<select name="icon" onchange="if ($(this).val()) { $('#icon').attr('src', '../images/ranks/' + $(this).val()).show(); $('.rankContainer').fadeIn(); } else { $('#icon, .rankContainer').fadeOut(); }">
								<option<?php if (!template::getVar('ICON')): ?> selected<?php endif; ?> value="">--</option>

								<?php
									if (isset(template::$blocks['icons'])):
										foreach(template::$blocks['icons'] as $icons):
								?>
											<option<?php if ($icons['ICON'] == template::getVar('ICON')): ?>  selected<?php endif; ?>><?=$icons['ICON']; ?></option>
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

		<tr>
			<td>Spezial-Rang:<br /></td>
			<td>
				<input onclick="$('#posts').hide();" type="radio" name="special" id="s1" value="1"<?php if (template::getVar('SPECIAL')): ?> checked<?php endif; ?> /> <label for="s1">Ja</label> &nbsp; 
				<input onclick="$('#posts').show();" type="radio" name="special" id="s0" value="0"<?php if (!template::getVar('SPECIAL')): ?> checked<?php endif; ?> /> <label for="s0">Nein</label> 
			</td>
		</tr>

		<tr id="posts"<?php if (template::getVar('SPECIAL')): ?> style="display:none;"<?php endif; ?>>
			<td>ab Foren-Beiträgen:</td>
			<td><input value="<?=template::getVar('POSTS'); ?>" type="number" size="4" name="posts" /></td>
		</tr>
	</table>

	<div style="text-align: right; margin-top: 10px;">
		<input type="submit" name="submit" value="Speichern" />
	</div>
</form>

<?php template::display('footer'); ?>