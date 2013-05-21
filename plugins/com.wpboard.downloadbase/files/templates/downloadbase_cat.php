<?php template::display('header'); ?>

<div class="fLeft">
	<h1 class="title"><a href="plugin.php?plugin=com.wpboard.downloadbase">Downloaddatenbank</a> &rsaquo; <?=template::getVar('CAT_NAME'); ?></h1>
</div>
<div class="clear"></div>
<div id="forums">
	<?php foreach(template::$blocks['forums'] AS $forum): ?>
		<div class="item" style="height: 70px;">
				<table width="100%" border="0" style="height: 70px;">
					<tr>
						<td class="center" width="6%">
							<img src="styles/standard/images/icons/topics/topic.png" OnDblClick="window.location.href='viewforum.php?id=<?=$forum['ID']; ?>&mark=1'">
						</td>

						<td style="padding: 10px;">
							<h3>
								<a class="forum" href="plugin.php?plugin=com.wpboard.downloadbase&action=cat&id=<?=$forum['ID']; ?>" width="50%">
									<?=$forum['NAME']; ?> <font class="grey">(<?=$forum['PACKAGE']; ?>, <?=$forum['VERSION']; ?>)</font>
								</a><br /><small class="grey"><?=$forum['TEXT']; ?></small>
							</h3>

							
						</td>

						<td width="12%" class="center">
							<?=$forum['DOWNLOADS']; ?><small class="grey"> Download<?=(($forum['DOWNLOADS'] == 1) ? '' : 's'); ?></small><br>
						</td>

					

											</tr>
				</table>
			</div>

	<?php endforeach; ?>
</div>
<br />
<br />
<?php template::display('footer'); ?>