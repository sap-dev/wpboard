<?php template::display('header'); ?>

<div class="h2box">
	<h1 class="fLeft" style="width: 50%;">
		Foren <span class="light">(<?=template::getVar('FORUMS_NUM'); ?>)</span> &nbsp;
			<span class="light">&amp;</span> &nbsp;
		Kategorien <span class="light">(<?=template::getVar('CATS_NUM'); ?>)</span>
	</h1>
	
	<div class="fRight" style="padding-top: 30px; width: 40%; text-align: right;">
		<a href="forum-new.php?menu=<?=template::getVar("menu");?>" class="button">Neues Forum erstellen</a>
	</div>
	
	<div class="clear"></div>
</div>


	<?php
		if (isset(template::$blocks['forums'])):
			foreach(template::$blocks['forums'] as $forums):
	?>
				<?php if ($forums['IS_CATEGORY']): ?>
					<table cellspacing="0" cellpadding="5" width="100%">
						<tr>
							<th colspan="2" width="50%" style="text-align: left;" width="50%">
								<b><?=$forums['NAME']; ?></b>
							</td>

							<th width="40px">
								<a href="forums.php?down=<?=$forums['ID']; ?>&menu=<?=template::getVar("menu");?>"><img src="../images/icons/down.png" height="14px" alt="runter" /></a> &nbsp;
								<a href="forums.php?up=<?=$forums['ID']; ?>&menu=<?=template::getVar("menu");?>"><img src="../images/icons/up.png" alt="hoch" height="14px" /></a>
							</td>

							<th width="100px" align="right">
								<a href="forum-new.php?id=<?=$forums['ID']; ?>&menu=<?=template::getVar("menu");?>" class="grey">bearbeiten</a> &nbsp;&minus;&nbsp;
								<a href="forums.php?delete=<?=$forums['ID']; ?>&menu=<?=template::getVar("menu");?>" class="red">löschen</a>
							</td>
						</tr>
					</table>

				<?php else: ?>

					<table cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td width="32px" valign="top">
								<img src="../images/icons/topics/topic.png" border="0" />
							</td>

							<td width="40%">
								<b><a href="../viewforum.php?id=<?=$forums['ID']; ?>"><?=$forums['NAME']; ?></a></b>

								<?php
									if (count($forums['SUBFORUMS']) > 0) {
										echo '
											<br /><br />
											<b class="grey">Unterforen:</b><br />
										';

										$subforums = '';
										foreach ($forums['SUBFORUMS'] as $s) {
											$subforums .= '<a href="./forum-new.php?id='.$s['forum_id'].'&menu=<?=template::getVar("menu");?>">'.$s['forum_name'].'</a>, ';
										}

										echo mb_substr($subforums, 0, mb_strlen($subforums) - 2);
									}
								?>
							</td>

							<td width="40px">
								<a href="forums.php?down=<?=$forums['ID']; ?>&menu=<?=template::getVar("menu");?>"><img src="../images/icons/down.png" height="14px" alt="runter" /></a> &nbsp;
								<a href="forums.php?up=<?=$forums['ID']; ?>&menu=<?=template::getVar("menu");?>"><img src="../images/icons/up.png" alt="hoch" height="14px" /></a>
							</td>

							<td width="100px" align="right">
								<a href="forum-new.php?id=<?=$forums['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button greyB">bearbeiten</a>
								<a href="forums.php?delete=<?=$forums['ID']; ?>&menu=<?=template::getVar("menu");?>" class="button redB">löschen</a>
							</td>
						</tr>
					</table>
	<?php
				endif;
			endforeach;
		endif;
	?>
</table>

<?php template::display('footer'); ?>