<?php template::display('header'); ?>

<div class="h2box">
	<h1>
		Styles
	</h1>
</div>

<div id="styles">
	<?php
		if (isset(template::$blocks['styles'])):
			foreach (template::$blocks['styles'] as $style):
	?>

				<div class="item">
					<div class="fLeft" style="width: 69%;">
						<span class="vendor">
							<?php if ($style['URL']): ?>
								<a href="<?=$style['URL']; ?>">
							<?php endif; ?>

							<?=$style['AUTHOR']; ?>

							<?php if ($style['URL']): ?>
								</a>
							<?php endif; ?>
						</span>
					
						<h2><?=$style['TITLE']; ?> <span class="version"><?=$style['VERSION']; ?></span></h2>

						<span class="dir">
							<?=$style['DIR']; ?>
						</span>
					</div>

					<div class="fRight" style="width: 29%; text-align: right;">
						<?php if (!$style['ACTIVATED']): ?>
							<?php if ($style['NONREMOVABLE']): ?>
								<span class="grey">nicht löschbar</span> &nbsp;
							<?php else: ?>
								<a href="./styles.php?removeStyle=<?=$style['DIRNAME']; ?>&menu=<?=template::getVar("menu");?>" class="button redB" onclick="return confirm('Willst du diesen Style (<?=$style['TITLE']; ?>) wirklich löschen?');">löschen</a>
							<?php endif; ?>

							<?php if ($style['COMPATIBLE']): ?>
								<a href="./styles.php?activateStyle=<?=$style['DIRNAME']; ?>&menu=<?=template::getVar("menu");?>" class="button greenB">Aktivieren</a>
							<?php else: ?>
								<br /><br />

								<small class="red">Nicht kompatibel.</small><br />
								<small class="grey">
									min. Version: <?=$style['MINVERSION']; ?><br />
									max. Version: <?=$style['MAXVERSION']; ?>
								</small>
							<?php endif; ?>

						<?php else: ?>

							<span class="green">Aktiviert</span>

						<?php endif; ?>
					</div>

					<div class="clear"></div>
				</div>

	<?php
			endforeach;
		else:
	?>
		
		<div class="info">Es sind keine Styles vorhanden. Bitte lade den Standard-Style erneut herunter.</div>

	<?php
		endif;
	?>

	<h2 class="title" style="margin-top: 40px;">Fehlerhafte Styles</h2>

	<?php
		if (isset(template::$blocks['badStyles'])):
			foreach (template::$blocks['badStyles'] as $badStyle):
	?>

				<div class="item">
					<div class="fLeft" style="width: 69%;">
						<h2><?=$badStyle['DIR']; ?></h2>
					</div>

					<div class="fRight" style="width: 29%; text-align: right;">
						<?php if (!$badStyle['NONREMOVABLE']): ?>
							<a href="./styles.php?removeStyle=<?=$badStyle['DIRNAME']; ?>&menu=<?=template::getVar("menu");?>" class="button redB" onclick="return confirm('Willst du diesen fehlerhaften Style wirklich löschen?');">löschen</a>
						<?php else: ?>
							<span class="grey">nicht l&ouml;schbar</span>
						<?php endif; ?>
					</div>

					<div class="clear"></div>
				</div>

	<?php
			endforeach;
		else:
	?>

		<div class="clear"></div>
		<br />

		<span class="grey">Keine fehlerhaften Styles vorhanden.</span>

	<?php
		endif;
	?>
</div>

<?php template::display('footer'); ?>