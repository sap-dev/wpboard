<?php template::display('header'); ?>

<div class="fLeft" style="width: 59%;">
	<h1 class="reset">
		<a href="./forum.php">Forum</a> &rsaquo;
		<a href="./viewforum.php?id=<?=template::getVar('FORUM_ID'); ?>"><?=template::getVar('FORUM_NAME'); ?></a> &rsaquo;
		<? if(template::getVar('LABEL_EXIST')): ?><?=template::getVar('LABEL');?>&nbsp;<? endif; ?><?=template::getVar('TOPIC_TITLE'); ?>
	</h1>
</div>

<div class="fRight" style="width: 39%; text-align: right; padding-top: 2.5px; padding-bottom: 25px;">
	<?php if (template::getVar('FORUM_CLOSED') || template::getVar('TOPIC_CLOSED')): ?>
		Geschlossen
	<?php else: ?>
		<a href="newpost.php?id=<?=template::getVar('TOPIC_ID'); ?>" class="button">Beitrag schreiben</a>
	<?php endif; ?></b>
</div>

<div class="clear"></div>

<div id="posts">
	<?php foreach(template::$blocks['posts'] as $posts): ?>

			<table width="100%" class="firstPost">
				<tr>
					<td width="25%" valign="top">
					
					<center>
					<?php if ($posts['USER_ID']): ?>
							<b><a style="font-size: 15px;" class="<?=$posts['USER_LEGEND']; ?>" href="user.php?id=<?=$posts['USER_ID']; ?>"><?=$posts['USERNAME']; ?></a> <?php if ($posts['IS_ONLINE'] == '1'): ?><img src="http://www.woltlab.com/forum/wcf/icon/onlineS.png" style="vertical-align: middle;"><?php else: ?><img src="http://www.woltlab.com/forum/wcf/icon/offlineS.png" style="vertical-align: middle;"><?php endif; ?></b><br />
							<small style="color: #000000; font-size: 11px;">
							<?php if ($posts['USER_LEGEND'] == 'admin'): ?><?php endif; ?><?=$posts['USER_RANK']; ?><?php if ($posts['USER_LEGEND'] == 'admin'): ?><?php endif; ?> <br />
							</small>
						<?php else: ?>
							<b>Unbekannt</b>
						<?php endif; ?>
					<img style="margin-top: 10px; padding: 10px;" src="./images/avatar/<?php if ($posts['USER_ID']): ?><?=$posts['USER_AVATAR']; ?><?php else: ?><?=template::getVar('AVATAR'); ?><?php endif; ?>" border="0" height="100" width="100" />
						<br />
						<small style="color: #000000; font-size: 11px;">
						Beiträge: <?=$posts['USER_POSTS']; ?><br />
						Registriert: <?=$posts['USER_REGISTER']; ?></small><br /><br />
						<a href="mail.php?dir=1&mode=new&to=TO"><img src="http://www.woltlab.com/forum/wcf/icon/pmEmptyS.png" title="PN schreiben"></a>
						<? if($posts['USER_WEBSITE']): ?><a href="<?=$posts['USER_WEBSITE'];?>"><img src="http://www.woltlab.com/forum/wcf/icon/websiteS.png"><? endif; ?>
						<? if($posts['USER_ICQ']): ?><a href="http://www.icq.com/people/<?=$posts['USER_ICQ'];?>"><img src="http://www.woltlab.com/forum/wcf/icon/icqS.png"><? endif; ?>
						<? if($posts['USER_SKYPE']): ?><a href="http://www.myskype.info/<?=$posts['USER_SKYPE'];?>/"><img src="http://www.woltlab.com/forum/wcf/icon/skypeS.png"><? endif; ?>
						</center>
							
					</td>

					
				
					<td width="75%" valign="top">
							<div style="word-wrap: break-word;">
								<?php if (template::getVar('POLL_TITLE')): ?>
									<div class="fRight" style="padding: 0 5px 5px 15px;width: 45%;">
										<form action="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>" method="post">
											<h2 class="title" style="margin-top: 0; padding-top: 0;">
												<b><?=template::getVar('POLL_TITLE'); ?></b>
											</h2>

											<div class="clear"></div>

											<?php if (template::getVar('USER_VOTED')): ?>
												<?php foreach (template::$blocks['options'] as $options): ?>
													<table width="100%">
														<tr>
															<td>
																<?=$options['TEXT']; ?><br />
																<small class="Grey"><?=$options['PRO']; ?>%</small>
															</td>

															<td align="right">
																<div style="height: 30px;
																			line-height: 30px;
																			padding-left: 5px;
																			padding-right: 5px;
																			background: #82323e;
																			width:<?=$options['PIXEL']; ?>px;
																			color:white;
																			margin-bottom: 5px;
																">
																	<b><?=$options['VOTES']; ?></b>
																</div>

																<small class="grey"></small>
															</td>
														</tr>
													</table>
												<?php endforeach; ?>

												<div style="margin-top: 20px;">
													<small class="grey">Stimmen gesamt: <b><?=template::getVar('POLL_VOTES'); ?></b></small>
												</div>
											<?php else: ?>

												<?php foreach (template::$blocks['options'] as $options): ?>
													<div style="padding: 10px;">
													<label for="option_<?=$options['ID']; ?>" style="cursor:pointer">
														<div style="float:left;width:30px">
															<input type="radio" id="option_<?=$options['ID']; ?>" value="<?=$options['ID']; ?>" name="option" />
														</div>

														<?=$options['TEXT']; ?>
													</label>
												</div>
												<?php endforeach; ?>

												<br />

												<input type="submit" name="submit" value="Voten" />
												<a href="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>&result=1" class="button greyB">Ergebnis anzeigen</a>

											<?php endif; ?>
										</form>
									</div>

								<?php endif; ?>

								<?=$posts['TEXT']; ?>
									
								
							</div>
							
					</td>
				</tr><tr>
				<td></td>
				<td>
				<div style="position: relative; float: left;margin-left: 0px;">
							<div id="lastposter" style="width: 150px; text-align: center;"><?=$posts['TIME'];?> Uhr</div>
						</div>
				<div style="position: relative; float: right;margin-right: 0px;">
							<div id="lastposter" style="width: 18px; text-align: center;">#<?=$posts['POSTS_NUM'];?></div>
						</div>
						<div style="position: relative; float: right; margin-right: 2px;"><div id="lastposter" style="width: 150px; text-align: center;">
								<?php if ($user->row): ?>
							<?php if (template::getVar('IS_MOD')): ?>
								<a href="movetopic.php?id=<?=template::getVar('TOPIC_ID'); ?>"><img src="http://cdn2.iconfinder.com/data/icons/aspneticons_v1.0_Nov2006/move_16x16.gif" title="Verschieben"></a>
								<a href="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>&important=1"><img src="http://cdn2.iconfinder.com/data/icons/fugue/icon/pin.png" title="<?php if (template::getVar('TOPIC_IMPORTANT')): ?>un<?php endif; ?>wichtig markieren"></a>
								<a href="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>&close=1"><img src="http://cdn2.iconfinder.com/data/icons/Siena/16/lock%20yellow.png" title="<?php if (template::getVar('TOPIC_CLOSED')): ?>öffnen<?php else: ?>schlie&szlig;en<?php endif; ?>"></a>
								
							<?php endif; ?>
	
							<?php if ($posts['USER_ID'] == $user->row['user_id'] || template::getVar('IS_MOD')): ?>
								<a href="<?php if ($posts['IS_TOPIC']): ?>newtopic.php?edit=1&id=<?=template::getVar('TOPIC_ID'); ?><?php else: ?>newpost.php?edit=1&id=<?=$posts['ID']; ?><?php endif; ?>"><img src="http://cdn2.iconfinder.com/data/icons/diagona/icon/16/018.png" title="Bearbeiten"></a>
								<a href="<?php if ($posts['IS_TOPIC']): ?>viewforum.php?id=<?=template::getVar('FORUM_ID'); ?><?php else: ?>viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?><?php endif; ?>&delete=<?=$posts['ID']; ?>"><img src="http://cdn1.iconfinder.com/data/icons/CrystalClear/16x16/actions/button_cancel.png" title="Löschen"></a>
							<?php endif; ?>

							<a href="newpost.php?id=<?=template::getVar('TOPIC_ID'); ?>&quote=<?=$posts['ID']; ?>"><img src="http://cdn5.iconfinder.com/data/icons/fatcow/16x16/document_quote.png" title="Zitieren"></a>
							<?php endif; ?></div></div>
				
				</td>
			</table>

			<?php endforeach; ?>
</div>

<br />

<div id="lastposter" style="width: 100%; padding: 10px;">
<h3 onClick="$('#schnellantwort').slideToggle();"><img src="http://www.woltlab.com/forum/wcf/icon/messageQuickReplyM.png" style="vertical-align: middle;"> Antworten</h3>
	<div id="schnellantwort" style="display: none; padding-top: 7px;">
	<? 
	if($user->row) {
	?>	
		<form action="newpost.php?id=<?=template::getVar('TOPIC_ID'); ?>" method="post" name="eintrag">
		<div class="editor" style="padding-bottom: 7px;">
			<div class="write"><center>
				<textarea name="text" id="postContent" style="width: 95%;" rows="8" cols="40"><?=template::getVar('TEXT'); ?></textarea>
			</div>
		</div><center>
			<input type="submit" name="submit" class="button" value="Absenden" />
	</div>
	<?
	} else {
	?>
	<div class="info2">Sie müssen angemeldet sein, um auf ein Thema zu antworten</div>
	<?
	}
	?>
	</form>
</div>


<?php template::display('footer'); ?>