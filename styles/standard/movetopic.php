<?php template::display('header'); ?>

<h1 class="title">
	<a href="./forum.php">Forum</a> &rsaquo;
	<?=template::getVar('FORUM_NAME'); ?>
</h1>

<form action="movetopic.php?id=<?=template::getVar('TOPIC_ID'); ?>" method="post">
	<table class="form" cellpadding="5" cellspacing="0">
		<tr>
			<td class="title" colspan="2">Titel</td>
			<td class="title" align="center">Aufrufe</td>
			<td class="title" align="center">Beiträge</td>
			<td width="22%" class="title">Letzter Beitrag</td>
		</tr>

		<?php
			if (isset(template::$blocks['topics'])):
				foreach(template::$blocks['topics'] as $topics):
		?>

		<tr>
			<td align="center" width="46"><input class="checkbox" type="checkbox" value="true" name="<?=$topics['ID']; ?>"<?php if ($topics['ID'] == 'TOPIC_ID'): ?>  checked<?php endif; ?>></td>
			<td><a class="forum" href="viewtopic.php?id=<?=$topics['ID']; ?>"><?=$topics['TITLE']; ?></a><br />von <a class="<?=$topics['USER_LEGEND']; ?>" href="user.php?id=<?=$topics['USER_ID']; ?>"><?=$topics['USERNAME']; ?></a> - <small><span><?=$topics['TIME']; ?> Uhr</span></small></td>
			<td class="center" width="10%"><?=$topics['POSTS']; ?></td>
			<td class="center" width="10%"><?=$topics['VIEWS']; ?></td>
			<td style="padding-left:10px">
				von <a class="<?=$topics['LAST_POST_USER_LEGEND']; ?>" href="user.php?id=<?=$topics['LAST_POST_USER_ID']; ?>">
						<?=$topics['LAST_POST_USERNAME']; ?>
					</a>&nbsp;

				<a href="viewtopic.php?id=<?=$topics['ID']; ?>&p=<?=$topics['LAST_POST_ID']; ?>#<?=$topics['LAST_POST_ID']; ?>">
					<img src="styles/standard/images/neubeitrag.gif" border="0" title="Letzter Beitrag" />
				</a><br />

				<span>
					<small><?=$topics['LAST_POST_TIME']; ?> Uhr</small>
				</span>
			</td>
		</tr>

		<?php
				endforeach;
			endif;
		?>

	</table>

	<table width="100%">
		<tr>
			<td valign="top">

				<?=template::getVar('FORUM_TOPICS'); ?> <?php if (template::getVar('FORUM_TOPICS') == '1'): ?>Thema<?php else: ?>Themen<?php endif; ?>
				<?php if (template::getVar('PAGES_NUM') > '1'): ?> | Seite <?=template::getVar('PAGE'); ?> von <?=template::getVar('PAGES_NUM'); ?> | <?=template::getVar('PAGES'); ?><?php endif; ?>

			</td>

			<td align="right">
				<a onclick="return Mark(1);" href="#">Alle markieren</a> | <a onclick="return Mark(0);" href="#">Alle unmarkieren</a><br /><br />in Forum 

				<select class="select" name="forum_id">
					<?php
						if (isset(template::$blocks['forums'])):
							foreach(template::$blocks['forums'] as $forums):
					?>
								<option value="<?=$forums['ID']; ?>"><?=$forums['NAME']; ?></option>
					<?php
							endforeach;
						endif;
					?>
				</select>

				<input type="submit" value="Verschieben" />
			</td>
		</tr>
	</table>
</form>

<?php template::display('footer'); ?>