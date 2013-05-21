<?php template::display('header'); ?>

<div class="h2box">
	<h1>News</h1>
</div>

<?php if (template::getVar('SYNC_NOTICE')): ?>
	<div class="info">
		Denke daran, die Konfiguration neu zu synchronisieren.
	</div>
<?php endif; ?>

<h2 class="title">Einstellungen</h2>

<form method="post" action="">
	<table border="0" cellspacing="0" cellpadding="5" class="two-column">
		<tr>
			<td width="40%">
				News aktivieren?
			</td>

			<td width="60%">
				<input type="radio" name="activate" value="1" id="activateYES" <?php if (template::getVar('NEWS_ACTIVE')): ?>checked<?php endif; ?> /> <label for="activateYES">Ja</label> &nbsp;
				<input type="radio" name="activate" value="0" id="activateNO" <?php if (!template::getVar('NEWS_ACTIVE')): ?>checked<?php endif; ?> /> <label for="activateNO">Nein</label>
			</td>
		</tr>
	</table>

	<div style="text-align: right;">
		<input type="submit" value="Speichern" name="submit" />
	</div>
</form>

<br /><br />
<h2 class="title">Foren ausw&auml;hlen</h2>

<ul class="third">
	<?php
		if (isset(template::$blocks['forums'])) {
			foreach (template::$blocks['forums'] as $forum):
	?>

		<?php
			if ($forum['IS_CATEGORY']):
		?>

			<div class="clear"></div>

			<li style="padding-bottom: 0;">
				<h2><?=$forum['FORUM_TITLE']; ?></h2>
			</li>

			<div class="clear"></div>

		<?php else: ?>

			<li>
				<b><?=$forum['FORUM_TITLE']; ?></b><br />
				<?php
					if ($forum['IS_NEWS']):
				?>

					<a href="./news.php?deactivate=<?=$forum['FORUM_ID']; ?>&menu=<?=template::getVar("menu");?>">Deaktivieren</a>

				<?php else: ?>

					<a href="./news.php?activate=<?=$forum['FORUM_ID']; ?>&menu=<?=template::getVar("menu");?>">Aktivieren</a>

				<?php
					endif;
				?>
			</li>

		<?php endif; ?>

	<?php
			endforeach;
		} else {
	?>

		<li><span class="grey">-- keine Foren verf&uuml;gbar</span></li>

	<?php
		}
	?>

	<div class="clear"></div>
</ul>

<?php template::display('footer'); ?>