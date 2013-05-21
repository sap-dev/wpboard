<?php template::display('header'); ?>

<div class="h2box">
	<h1 class="fLeft" style="width:30%;">Gruppen <span class="light">(0)</span></h1>

	<div class="fRight" style="padding-top: 30px; width: 30%; text-align: right;">
		<a href="group-new.php?menu=<?=template::getVar("menu");?>" class="button">Gruppe hinzufügen</a>
	</div>

	<div class="clear"></div>
</div>

<?php template::display('footer'); ?>