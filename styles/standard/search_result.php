<?php template::display('header'); ?>

<h1 class="reset">
	<a href="./forum.php">Forum</a> &rsaquo;
	<a href="./search.php">Suche</a> &rsaquo;
	Ergebnisse (<?=template::getVar('NUM'); ?>)
</h1>

<br /><br />

<style type="text/css">span.result { padding:2px 5px;background:#e2f287; color:#222222; }</style>

<div id="posts">
	<?php
		if (isset(template::$blocks['posts'])):
			foreach(template::$blocks['posts'] as $posts):
	?>
				
				<!-- <tr>
					<td width="24%" class="top" style="background:#f3f3f3;border-bottom:1px solid #cccccc;border-right:1px solid #e7e7e7;padding:10px 4px 10px 10px;">

						<?php if ($posts['USER_ID']): ?>

						<b><a class="<?=$posts['USER_LEGEND']; ?>" href="user.php?id=<?=$posts['USER_ID']; ?>"><?=$posts['USERNAME']; ?></a></b>
						<br /><?=$posts['USER_RANK']; ?>
						<?php if ($posts['USER_RANK_ICON']): ?><br /><img src="images/ranks/<?=$posts['USER_RANK_ICON']; ?>" border="0" /><?php endif; ?>
						<br /><a href="user.php?id=<?=$posts['USER_ID']; ?>"><img class="img" style="margin:5px 0;" src="images/avatar/<?=$posts['USER_AVATAR']; ?>" border="0" /></a>

						<?php else: ?>

						<b>Unbekannt</b>
						<br />Mitglied existiert nicht
						<br /><a href="#" onclick="return !1"><img class="img" style="margin:5px 0;" src="images/avatar/<?=template::getVar('AVATAR'); ?>" /></a>

						<?php endif; ?>
						</td>
						<td width="76%" class="top" style="padding:4px 7px;border-bottom:1px solid #cccccc;">

						<div style="border-bottom:1px dotted #cccccc;padding:3px;margin-bottom:7px;"><a href="viewtopic.php?id=<?=$posts['TOPIC_ID']; ?>&p=<?=$posts['ID']; ?>#<?=$posts['ID']; ?>"><img src="styles/<?= template::getStyleDirName(); ?>/images/icons/topics/newtopic.png" border="0" /></a><small><span> - <?=$posts['TIME']; ?> Uhr</span></small></div>
						<a class="forum" href="viewtopic.php?id=<?=$posts['TOPIC_ID']; ?>"><?=$posts['TOPIC_TITLE']; ?></a>
						<br /><div style="padding:5px 0;"><?=$posts['TEXT']; ?></div>
						<a href="viewtopic.php?id=<?=$posts['TOPIC_ID']; ?>&p=<?=$posts['ID']; ?>#<?=$posts['ID']; ?>">mehr</a>

					</td>
				</tr> -->

				<table width="100%" class="post" cellspacing="0" cellpadding="0">
					<tr>
						<td width="60px" valign="top" style="padding: 0;">
							<?php if ($posts['USER_ID']): ?>
								<a href="user.php?id=<?=$posts['USER_ID']; ?>">
									<img src="images/avatar/<?=$posts['USER_AVATAR']; ?>" border="0" height="50" width="50" />
								</a>
							<?php else: ?>
								<img border="0" height="50" width="50" src="images/avatar/<?=template::getVar('AVATAR'); ?>" />
							<?php endif; ?>
						</td>

						<td valign="top" class="postContent">
							<a name="<?=$posts['TRACK']; ?>"></a>

							<div class="user">
								<?php if ($posts['USER_ID']): ?>
									<b><a class="<?=$posts['USER_LEGEND']; ?>" href="user.php?id=<?=$posts['USER_ID']; ?>"><?=$posts['USERNAME']; ?></a></b><?php if ($posts['USER_LEGEND'] == 'admin'): ?> <small class="grey">(Administrator)</small><?php endif; ?>
								<?php else: ?>
									<b>Unbekannt</b>
								<?php endif; ?>
							</div>

							<?=$posts['TEXT']; ?>
						</td>
					</tr>

					<tr>
						<td>&nbsp;</td>

						<td>
							<div class="fLeft" style="width: 49%;">
								<small><span><?=$posts['TIME']; ?> Uhr</span></small>
							</div>

							<div class="fRight" style="width: 49%; text-align: right;">
								<a href="viewtopic.php?id=<?=$posts['TOPIC_ID']; ?>&p=<?=$posts['ID']; ?>#<?=$posts['ID']; ?>" class="button">mehr &rsaquo;</a>
							</div>

							<div class="clear"></div>
						</td>
					</tr>
				</table>
	<?php
			endforeach;
		else:
	?>
		<div class="info">Keine Suchergebnisse :(</div>
	<?php
		endif;
	?>
</div>

<table width="100%">
	<tr>
		<td>
			<?=template::getVar('NUM'); ?> Ergebnis<?php if (template::getVar('NUM') != 1): ?>se<?php endif; ?>

			<?php if (template::getVar('PAGES_NUM') > '1'): ?> | <?=template::getVar('PAGE'); ?> Seite von <?=template::getVar('PAGES_NUM'); ?> | <?=template::getVar('PAGES'); ?><?php endif; ?>
		</td>
		<td></td>
	</tr>
</table>

<?php template::display('footer'); ?>