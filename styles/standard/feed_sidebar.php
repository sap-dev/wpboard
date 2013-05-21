<div id="feed">
	<?php
		if (isset(template::$blocks['feed'])) {
			foreach (template::$blocks['feed'] as $feed):
	?>
				<div class="item" style="border-bottom: 1px solid #e0e0e0;">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<?php if ($feed['USER_ID']): ?>
									<a class="<?=$feed['USER_LEGEND']; ?>" href="user.php?id=<?=$feed['USER_ID']; ?>"><?=$feed['USERNAME']; ?></a>
								<?php else: ?>
									<span>Unbekannt</span>
								<?php endif; ?>
								<br />

								in <b><a href="viewtopic.php?id=<?=$feed['TOPIC_ID']; ?>&p=<?=$feed['POST_ID']; ?>#<?=$feed['POST_ID']; ?>"><?=$feed['TOPIC_TITLE']; ?></a></b><br /><br />

								<?=$feed['POST_TEXT']; ?><?php if ($feed['MORE']): ?>... <a href="viewtopic.php?id=<?=$feed['TOPIC_ID']; ?>&p=<?=$feed['POST_ID']; ?>#<?=$feed['POST_ID']; ?>">mehr</a><?php endif; ?><br />
								
								<div class="footerBar">
									<small class="grey"><span><?=$feed['POST_TIME']; ?> | <a href="viewforum.php?id=<?=$feed['FORUM_ID']; ?>"><?=$feed['FORUM_NAME']; ?></a></span></small>
								</div>
							</td>
							
							<td width="25px" class="tdMore" onClick="self.location.href = 'viewtopic.php?id=<?=$feed['TOPIC_ID']; ?>&p=<?=$feed['POST_ID']; ?>#<?=$feed['POST_ID']; ?>';">
								&nbsp;
							</td>
						</tr>
					</table>
				</div>
	<?php
			endforeach;
		}
	?>

	<?php if (template::getVar('MORE')): ?>
		<br />
		<a href="#" class="button" onclick="return feed.more(<?=template::getVar('MORE'); ?>, 1);">Mehr anzeigen &rsaquo;</a>
	<?php endif; ?>
</div>