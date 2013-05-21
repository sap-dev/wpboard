<?php template::display('header'); ?>

<?php if (template::getVar('REFRESH')): ?>
	<meta http-equiv="refresh" content="<?=template::getVar('REFRESH'); ?>; URL=<?=template::getVar('LINK'); ?>"> 
<?php endif; ?>


<div class="info">
	<?=template::getVar('MESSAGE'); ?>
</div>

<br />

<b>
	<a href="<?=template::getVar('LINK'); ?>">
		<?=template::getVar('LINK_TEXT'); ?>
	</a>
</b>

<?php if (template::getVar('LINK2')): ?>
	|
	<a href="<?=template::getVar('LINK2'); ?>">
		<?=template::getVar('LINK2_TEXT'); ?>
	</a>
<?php endif; ?>

<br /><br />

<?php template::display('footer'); ?>