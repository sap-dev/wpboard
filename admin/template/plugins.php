<?php template::display('header'); ?>

<div id="plugins">
	<div class="h2box" style="margin-bottom: 0;">
		<div class="fLeft" style="width: 40%">
			<h1>Installierte Plugins</h1>
		</div>

		<div class="fRight" style="padding-top: 7px;">

		</div>

		<div class="clear"></div>
	</div>

	<?php if (!template::getVar('INSTALLED')): ?>
		<div class="info">Es sind keine Plugins installiert.</div>
	<?php endif; ?>

	<?php
		if (isset(template::$blocks['plugins'])):
			foreach(template::$blocks['plugins'] as $plugins):
	?>
		<div class="item">
			<div class="fLeft" style="width: 59%;">
				<span style="font-size: 13px;"><?=$plugins['NAME']; ?></span><br /><span class="package"><?=$plugins['PACKAGE']; ?> <?=$plugins['VERSION']; ?></span><br />
							
							</div>

			<div class="fRight" style="width: 39%; text-align: right; padding-top: 10px;">
				<a href="./plugins.php?manage&plugin=<?=$plugins['PACKAGE']; ?>&menu=<?=template::getVar("menu");?>" class="button greyB"><img src="http://cdn2.iconfinder.com/data/icons/diagona/icon/16/018.png" style="vertical-align: middle;"> Verwalten</a>
				<a href="./plugins.php?uninstall=<?=$plugins['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button redB"><img src="http://cdn1.iconfinder.com/data/icons/CrystalClear/16x16/actions/button_cancel.png" style="vertical-align: middle;"> Deinstallieren</a>
							</div>

			<div class="clear"></div>
		</div>
	<?php
			endforeach;
		endif;
	?>

	<br /><br />

	<div class="h2box">
		<h1 class="fLeft" style="width:30%;">Plugin Server <span class="light">(<?=template::getVar('pluginServerCount');?>)</span></h1>

		<div class="fRight" style="padding-top: 38px; width: 30%; text-align: right;">
			<a href="server-new.php?menu=<?=template::getVar("menu");?>" class="button greenB">Server hinzufügen</a>
		</div>

		<div class="clear"></div>
	</div>

	<?php if (template::getVar('pluginServerCount') == 0): ?>
		<div class="info">Es sind keine Plugin Server eingetragen.</div>
	<?php endif; ?>

	<?php
		if (isset(template::$blocks['server'])):
			foreach(template::$blocks['server'] as $server):
	?>
				<div class="item">
					<div class="fLeft" style="width: 59%;">
						<h2 style="margin-bottom: 0;"><?=$server['NAME']; ?> - <?php echo ($server['SERVERSTATUS'] ? '<small class="green">Online</small>' : '<small class="red">Offline</small>');?></h2>
						<small><?=$server['URL']; ?><span class="grey">plugins.json</span></small>
					</div>

					<div class="fRight" style="width: 39%; text-align: right; padding-top: 18px;">
						<?php if ($server['SERVERSTATUS']): ?>
							<a href="plugins.php?list=<?=$server['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button greyB">Plugin Liste</a>
						<?php endif; ?>
							<a href="server-new.php?id=<?=$server['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button greyB">bearbeiten</a>
							<a href="plugins.php?removeServer=<?=$server['ID'];?>&menu=<?=template::getVar("menu");?>" class="button redB" onclick="return confirm('Willst du diesen Server (<?=$server['NAME']; ?>) wirklich entfernen?');">entfernen</a>
					</div>

					<div class="clear"></div>
				</div>
	<?php
			endforeach;
		endif;
	?>

	<br /><br />

	<div class="h2box">
		<div class="fLeft" style="width: 50%;">
			<h1>Lokal verfügbare Plugins</h1>
		</div>

		<div class="fRight" style="padding-top: 7px;">

		</div>

		<div class="clear"></div>
	</div>

	<?php if (!template::getVar('AVAILABLE')): ?>
		<div class="info">Es sind keine Plugins verfügbar.</div>
	<?php endif; ?>

	<?php
		if (isset(template::$blocks['available'])):
			foreach(template::$blocks['available'] as $available):
	?>
				<div class="item">
					<div class="fLeft" style="width: 51%;">
						<span style="font-size: 13px;"><?=$available['NAME']; ?></span><br /><span class="package"><?=$available['PACKAGE']; ?> <?=$available['VERSION']; ?></span>					</div>
					<div class="fRight" style="width: 49%; text-align: right;">
						<a href="./plugins.php?removePlugin=<?=$available['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button redB" onclick="return confirm('Willst du dieses Plugin (<?=$available['NAME']; ?>) wirklich löschen?');">löschen</a>

						<?php if ($available['COMPATIBLE'] && !$available['MISSING_DEPENDENCY']): ?>
							<a href="./plugins.php?install=<?=$available['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button greenB">installieren</a>
						<?php elseif ($available['MISSING_DEPENDENCY']): ?>
							<br /><br />

							<small class="grey">Es sind nicht alle Abh&auml;ngigkeiten erf&uuml;llt.</small>
						<?php else: ?>
							<br /><br />

							<small class="red">Nicht kompatibel.</small><br />
							<small class="grey">
								min. Version: <?=$available['MINVERSION']; ?><br />
								max. Version: <?=$available['MAXVERSION']; ?>
							</small>
						<?php endif; ?>
					</div>
										<div class="clear"></div>
				</div>
	<?php
			endforeach;
		endif;
	?>
</div>

<?php template::display('footer'); ?>