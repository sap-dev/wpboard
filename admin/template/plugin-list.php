<?php template::display('header'); ?>

	<?php if (template::getVar('MESSAGE')): ?>
		<div class="info"><?=template::getVar('MESSAGE');?></div>
	<?php endif; ?>

<div id="plugins">
	<div class="h2box">
		<h1 style="width:100%;">Verfügbare Plugins <span class="light">(<?=template::getVar('SERVERNAME');?>)</span></h1>
	</div>
	<?php if (template::getVar('PLUGINCOUNT') == 0): ?>
		<div class="info">Es sind keine Plugin auf dem Server vorhanden.</div>
	<?php else: ?>
		<?php //
		if (isset(template::$blocks['plugins'])):
			foreach(template::$blocks['plugins'] as $plugin):
				?>
				<div class="item">
					<div class="fLeft" style="width: 69%;">
						<h2 style="margin-bottom: 0;"><?=$plugin['NAME']; ?> - <span class="grey"><?=$plugin['PACKAGE']; ?></span></h2>
						<small><?=$plugin['DESCRIPTION']; ?></small>
						<br /><br />
						<b>Entwickler:</b> <?=$plugin['DEVELOPER']; ?><br /><br />
						<b>Version:</b> <?=$plugin['VERSION']; ?><br /><br />
						<b>Letztes Update:</b> <?=date('d.m.Y', $plugin['LASTUPDATE']); ?>
					</div>
					<div class="fRight" style="width: 29%; text-align: right;">
					<?php if ($plugin['EXISTING']): ?>
						<small class="red">bereits lokal vorhanden.</small>
					<?php else: ?>
						<a href="./plugins.php?list=<?=template::getVar('SERVERID');?>&download=<?=$plugin['PACKAGE']; ?>&menu=<?=template::getVar("menu");?>" class="button greenB">herunterladen</a>
					<?php endif; ?>
					</div>
					<div class="clear"></div>
				</div>
				<?php
			endforeach;
		endif;
		?>
	<?php endif; ?>
</div>

<?php template::display('footer'); ?>