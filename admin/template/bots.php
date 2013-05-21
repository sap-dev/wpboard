<?php template::display('header'); ?>

<div class="h2box">
	<h1 class="fLeft" style="width: 30%;">Bots <span class="light">(<?=template::getVar('NUM'); ?>)</span></h1>
	
	<div class="fRight" style="padding-top: 30px; width: 30%; text-align: right;">
		<a href="bot-new.php?menu=<?=template::getVar("menu");?>" class="button">Bot hinzufügen</a>
	</div>
	
	<div class="clear"></div>
</div>

<table cellpadding="5" cellspacing="0" class="form">
	<tr>
		<td class="title" width="225px">Bot</td>
		<td class="title">Agent</td>
		<td class="title" width="175px">&nbsp;</td>
	</tr>

	<?php
		if (isset(template::$blocks['bots'])):
			foreach(template::$blocks['bots'] as $bots):
	?>

				<tr>
					<td><b><?=$bots['NAME']; ?></b></td>
					<td><?=$bots['AGENT']; ?></td>
					<td align="right">
						<a href="bot-new.php?id=<?=$bots['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button greyB">bearbeiten</a>
						<a href="bots.php?delete=<?=$bots['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button redB">löschen</a>
					</td>
				</tr>

	<?php
			endforeach;
		endif;
	?>
</table>

<?php template::display('footer'); ?>