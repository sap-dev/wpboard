<?php template::display('header'); ?>

<div class="h2box">
	<h1 class="fLeft" style="width: 40%;">Smilies <span class="light">(<?=template::getVar('NUM'); ?>)</span></h1>
	
	<div class="fRight" style="padding-top: 30px; width: 40%; text-align: right;">
		<a href="smilie-new.php?menu=<?=template::getVar("menu");?>" class="button">Smilie hinzufügen</a>
	</div>
	
	<div class="clear"></div>
</div>

<table class="form" cellpadding="5" cellspacing="0">
	<tr>
		<td width="20%" class="title">Smilie</td>
		<td class="title">Emotion</td>
		<td width="175px" class="title">&nbsp;</td>
	</tr>

	<?php
if (isset(template::$blocks['smilies'])):
foreach(template::$blocks['smilies'] as $smilies):
?>

	<tr>
		<td class="inhalt center"><img src="../images/smilies/<?=$smilies['IMAGE']; ?>" border="0" /></td>
		<td><?=$smilies['EMOTION']; ?></td>
		<td align="right">
			<a href="smilie-new.php?id=<?=$smilies['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button greyB">bearbeiten</a>
			<a href="smilies.php?delete=<?=$smilies['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button redB">löschen</a>
		</td>
	</tr>

	<?php
endforeach;
endif;
?>

</table>

<?php template::display('footer'); ?>