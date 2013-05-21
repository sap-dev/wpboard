<?php template::display('header'); ?>

<div class="h2box">
	<h1 class="fLeft" style="width:30%;">Ränge <span class="light">(<?=template::getVar('NUM'); ?>)</span></h1>
	
	<div class="fRight" style="padding-top: 30px; width: 30%; text-align: right;">
		<a href="rank-new.php?menu=<?=template::getVar("menu");?>" class="button">Rang hinzufügen</a>
	</div>
	
	<div class="clear"></div>
</div>

<table class="form" cellpadding="5" cellspacing="0">
	<tr>
		<td width="20%" class="title">Rang-Bild</td>
		<td class="title">Titel</td>
		<td class="title">ab Beiträge</td>
		<td width="175px" class="title">&nbsp;</td>
	</tr>

	<?php
if (isset(template::$blocks['ranks'])):
foreach(template::$blocks['ranks'] as $ranks):
?>

	<tr>
		<td class="center"><?php if ($ranks['IMAGE']): ?><img style="margin:0 15px;" src="../images/ranks/<?=$ranks['IMAGE']; ?>" border="0" /><?php else: ?>-<?php endif; ?></td>
		<td><?=$ranks['TITLE']; ?></td>
		<td><?=$ranks['POSTS']; ?></td>
		<td>
			<a href="rank-new.php?id=<?=$ranks['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button greyB">bearbeiten</a>
			<a href="ranks.php?delete=<?=$ranks['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button redB">löschen</a>
		</td>
	</tr>

	<?php
endforeach;
endif;
?>

</table>

<?php template::display('footer'); ?>