<?php template::display('header'); ?>

<div style="height:250px;">
	<br />
	
	<div class="info"><?=template::getVar('MESSAGE'); ?></div>
	<br />

	<b><a href="<?=template::getVar('LINK'); ?>"><?=template::getVar('LINK_TEXT'); ?></a></b>
	<?php if (template::getVar('LINK2')): ?> <a href="<?=template::getVar('LINK2'); ?>" class="button greyB"><?=template::getVar('LINK2_TEXT'); ?></a><?php endif; ?>
</div>

<?php template::display('footer'); ?>