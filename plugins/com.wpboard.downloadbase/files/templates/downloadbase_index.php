<?php template::display('header'); ?>

<div class="fLeft">
	<h1 class="title">Downloaddatenbank</h1>
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
									<?=$forum['NAME']; ?>
								</a>
							</h3>

							
						</td>

						<td width="12%" class="center">
							<?=$forum['NUM']; ?><small class="grey"> Download<?=(($forum['NUM'] == 1) ? '' : 's'); ?></small><br>
						</td>

					

						<td width="30%">
							<?php if ($forum['LAST_DL_NAME']): ?>
							<div id="lastposter" style="height: 37px;">
							
							<a class="forum" href="viewtopic.php?id=<?=$forum['LAST_POST_TOPIC_ID'];?>"><?=$forum['LAST_DL_NAME'];?></a><br />
								<span>
									<small class="grey"><small class="grey"><?=$forum['LAST_DL_DOWNLOADS'];?> Download<?=(($forum['LAST_DL_DOWNLOADS'] == 1) ? '' : 's'); ?>&nbsp; - &nbsp;<?=$forum['LAST_DL_TIME']; ?> Uhr</small>
								</span>
							<?php else: ?>
								<div id="lastposter" style="height: 37px;"><center><small class="grey">kein Download vorhanden</small></center></div>
							<?php endif; ?>
							
							</div>
						</td>
					</tr>
				</table>
			</div>

	<?php endforeach; ?>
</div>
<br />

<div class="info2">Sie k&ouml;nnen diese Downloaddatenbank in Ihrem WPBoard einfach eintragen - dieser fungiert als Pluginserver. <a href="plugins.json" style="color: #000000;">Plugin-Server-URL</a></div>
<br />

<h2 class="title">Statistik</h2>

<table class="form" width="100%">
	<tr>
		<td width="60"><img src="http://cdn5.iconfinder.com/data/icons/48_px_web_icons/48/Statistics.png"></td>
		<td class="inhalt">

			<?=template::getVar('download_num'); ?> Download Eintr&auml;ge
			</td>
	</tr>
</table>

<?php template::display('footer'); ?>