<?php template::display('header'); ?>

<div class="h2box">
	<h1 class="fLeft" style="width: 30%;">Men&uuml;eintr&auml;ge <span class="light">(<?=template::getVar('NUM'); ?>)</span></h1>
	
	<div class="fRight" style="padding-top: 30px; width: 30%; text-align: right;">
		<a href="menu-new.php?menu=<?=template::getVar("menu");?>" class="button">Men&uuml;eintrag hinzufügen</a>
	</div>
	
	<div class="clear"></div>
</div>

<table cellpadding="5" cellspacing="0" class="form">
	<tr>
		<td class="title" width="225px">Icon</td>
		<td class="title">Name</td>
		<td class="title">Link</td>
		<td class="title" width="175px">&nbsp;</td>
	</tr>

	<?php
		if (isset(template::$blocks['menu'])):
			foreach(template::$blocks['menu'] as $menu):
	?>

				<tr>
					<td><b><img src="<?=$menu['ICON']; ?>" width="16" height="16"></b></td>
					<td><?=$menu['NAME']; ?></td>
					<td><?=$menu['LINK']; ?>.php</td>
					<td align="right">
						<a href="menu-new.php?id=<?=$menu['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button greyB">bearbeiten</a>
						<a href="menu.php?delete=<?=$menu['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button redB">löschen</a>
					</td>
				</tr>

	<?php
			endforeach;
		endif;
	?>
</table>

<?php template::display('footer'); ?>