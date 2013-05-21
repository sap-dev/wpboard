<div id="feed">
	<?php
		if (isset(template::$blocks['feed'])) {
			foreach (template::$blocks['feed'] as $feed):
	?>
				<div class="item">
					<table width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<td width="70" valign="top">
								<?php if ($feed['USER_ID']): ?>
									<a href="user.php?id=<?=$feed['USER_ID']; ?>"><img class="img" height="50" width="50" src="images/avatar/mini/<?=$feed['AVATAR']; ?>" /></a>
								<?php else: ?>
									<img class="img" height="50" width="50" src="images/avatar/mini/<?=$feed['AVATAR']; ?>" />
								<?php endif; ?>
							</td>
							
							<td>
								<?php if ($feed['USER_ID']): ?>
									<a class="<?=$feed['USER_LEGEND']; ?>" href="user.php?id=<?=$feed['USER_ID']; ?>"><?=$feed['USERNAME']; ?></a>
								<?php else: ?>
									<span>Unbekannt</span>
								<?php endif; ?>
								
								in <b><a href="viewtopic.php?id=<?=$feed['TOPIC_ID']; ?>&p=<?=$feed['POST_ID']; ?>#<?=$feed['POST_ID']; ?>"><?=$feed['TOPIC_TITLE']; ?></a></b><br />
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
		<a href="#" class="button" onclick="return feed.more(<?=template::getVar('MORE'); ?>)">Mehr anzeigen &rsaquo;</a>
	<?php endif; ?>
</div>