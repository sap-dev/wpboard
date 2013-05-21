<?php template::display('header'); ?>

<div id="writePost">
	<form action="newpost.php?edit=<?=template::getVar('EDIT'); ?>&id=<?=template::getVar('ID'); ?>" method="post" name="eintrag">
		<div class="fLeft actionsL" style="width: 80%;">
			<h1>
				<a href="./forum.php">Forum</a> &rsaquo;
				<a href="./viewforum.php?id=<?=template::getVar('FORUM_ID'); ?>"><?=template::getVar('FORUM_NAME'); ?></a> &rsaquo;
				<a href="./viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>"><?=template::getVar('TOPIC_TITLE'); ?></a> &rsaquo;
				<?php if (template::getVar('EDIT')): ?>Beitrag bearbeiten<?php else: ?>Beitrag verfassen<?php endif; ?>
			</h1>
		</div>

		<div class="fRight actionsR noP" style="width: 20%;">
			<input type="submit" name="preview" class="button greyB" value="Vorschau" />
			<input type="submit" name="submit" class="button" value="Absenden" />
		</div>

		<div class="clear"></div>
		
		<?php if (template::getVar('ERROR')): ?>
			<br />
			
			<div class="info">
				<?php if (template::getVar('ERROR') == '1'): ?>		Du musst einen Text eingeben
				<?php elseif (template::getVar('ERROR') == '2'): ?>	Pro Tag darfst du nur <?=template::getVar('POSTS_PERDAY'); ?> Beiträge schreiben
				<?php elseif (template::getVar('ERROR') == '3'): ?>	Das Thema ist geschlossen
				<?php elseif (template::getVar('ERROR') == '4'): ?>	Das Forum ist geschlossen
				<?php elseif (template::getVar('ERROR') == '5'): ?>	Der Text darf maximal <?=template::getVar('MAX_CHARS'); ?> Zeichen lang sein
				<?php endif; ?>

			</div>
		<?php endif; ?>

		<?php if (template::getVar('PREVIEW')): ?>
			<div class="preview">
				<div class="title">Vorschau</div>
				
				<?=template::getVar('PREVIEW'); ?>
			</div>
		<?php endif; ?>

	
		<div class="editor">
			<div class="bbcodes">
				<?php template::display('bbcodes'); ?>
			</div>

			<div class="write">
				<textarea name="text" id="postContent" rows="12"><?=template::getVar('TEXT'); ?></textarea>
			</div>
		</div>

		<div class="options">
			<div class="title">
				Optionen:
			</div>

			<div class="content">
				<input id="option1" type="checkbox" value="1" name="bbcodes"<?php if (template::getVar('BBCODES')): ?> checked<?php endif; ?> />
				<label for="option1">BBCodes ausschalten</label>

				&nbsp;&nbsp;

				<input id="option2" type="checkbox" value="1" name="smilies"<?php if (template::getVar('SMILIES')): ?> checked<?php endif; ?>/>
				<label for="option2">Smilies ausschalten</label>

				&nbsp;&nbsp;

				<input id="option3" type="checkbox" value="1" name="urls"<?php if (template::getVar('URLS')): ?> checked<?php endif; ?>/>
				<label for="option3">URLs nicht verlinken</label>

				&nbsp;&nbsp;

				<input id="option4" type="checkbox" value="1" name="signatur"<?php if (template::getVar('SIGNATUR')): ?> checked<?php endif; ?>/>
				<label for="option4">Signatur anhängen</label>
			</div>

			<div class="clear"></div>
		</div>
	</form>

	<?php if (!template::getVar('EDIT')): ?>
		<br />
		<h3>Letzte Beiträge</h3>
		<br />

		<div id="posts">
			<?php
				if (isset(template::$blocks['posts'])):
					foreach(template::$blocks['posts'] as $posts):
			?>
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
											<b><a class="<?=$posts['USER_LEGEND']; ?>" href="user.php?id=<?=$posts['USER_ID']; ?>"><?=$posts['USERNAME']; ?></a></b><?php if ($posts['USER_LEGEND'] == 'admin'): ?>  <small class="grey">(Administrator)</small><?php endif; ?>
										<?php else: ?>
											<b>Unbekannt</b>
										<?php endif; ?>
									</div>

									<?=$posts['TEXT']; ?>

									<?php if ($posts['EDIT_USER_ID']): ?>
										<div style="border-top:1px solid #dddddd;padding:5px;">Der Eintrag wurde am <?=$posts['EDIT_TIME']; ?> Uhr von <?php if ($posts['EDIT_USER_ID']): ?><a class="<?=$posts['EDIT_USER_LEGEND']; ?>" href="user.php?id=<?=$posts['EDIT_USER_ID']; ?>"><?=$posts['EDIT_USERNAME']; ?></a><?php else: ?><span>Unbekannt</span><?php endif; ?> geändert.</div><br />
									<?php endif; ?>

									<?php if ($posts['USER_SIGNATUR']): ?>
										<div style="border-top:1px solid #dddddd;padding:5px;"><?=$posts['USER_SIGNATUR']; ?></div>
									<?php endif; ?>

									</div>
								</td>
							</tr>

							<tr>
								<td>&nbsp;</td>

								<td>
									<div class="fLeft" style="width: 49%;">
										<a href="viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?>&page=<?=template::getVar('PAGE'); ?>#<?=$posts['ID']; ?>">
											<small><span><?=$posts['TIME']; ?> Uhr</span></small>
										</a>
									</div>

									<div class="fRight" style="width: 49%; text-align: right;">
										<?php if (template::getVar('USER_ID')): ?>
											<small>
												<?php if ($posts['USER_ID'] == $user->row['user_id'] || template::getVar('IS_MOD')): ?>
													<a href="<?php if ($posts['IS_TOPIC']): ?>newtopic.php?edit=1&id=<?=template::getVar('TOPIC_ID'); ?><?php else: ?>newpost.php?edit=1&id=<?=$posts['ID']; ?><?php endif; ?>">Bearbeiten</a> &nbsp;&nbsp;
													<a href="<?php if ($posts['IS_TOPIC']): ?>viewforum.php?id=<?=template::getVar('FORUM_ID'); ?><?php else: ?>viewtopic.php?id=<?=template::getVar('TOPIC_ID'); ?><?php endif; ?>&delete=<?=$posts['ID']; ?>">Löschen</a> &nbsp;&nbsp;
												<?php endif; ?>

												<a href="newpost.php?id=<?=template::getVar('TOPIC_ID'); ?>&quote=<?=$posts['ID']; ?>">Zitieren</a>
											</small>
										<?php endif; ?>
									</div>

									<div class="clear"></div>
								</td>
							</tr>
						</table>
			<?php
					endforeach;
				endif;
			?>
		</div>
	<?php endif; ?>
</div>

<?php template::display('footer'); ?>