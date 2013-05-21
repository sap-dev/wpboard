<?php template::display('header'); ?>

<div class="h2box">
	<h1>Mitglieder <span class="light">(<?=template::getVar('NUM'); ?>)</span></h1>
	
	<span>
		<form action="users.php?menu=<?=template::getVar("menu");?>">
			<table align="right" cellspacing="0" cellpadding="5" class="noBorder">
				<tr>
					<td width="10%" align="right">Username:</td>
					<td width="20%"><input type="text" class="w100" value="<?=template::getVar('S_USERNAME'); ?>" name="user" /></td>

					<td width="10%" align="right">E-Mail:</td>
					<td width="20%"><input type="text" class="w100" value="<?=template::getVar('S_EMAIL'); ?>" name="email" /></td>

					<td width="10%" align="right">IP:</td>
					<td width="20%"><input type="text" class="w100" value="<?=template::getVar('S_IP'); ?>" name="ip" /></td>

					<td width="14%"><input type="submit" value="Suchen" /></td>
				</tr>
			</table>
		</form>
	</span>

	<div class="clear"></div>
</div>

<table class="form" cellpadding="5" cellspacing="0">
	<tr>
		<td class="title">Mitglied</td>
		<td class="title">E-Mail</td>
		<td class="title">Beiträge</td>
		<td width="30%" class="title">Aktion</td>
	</tr>

	<?php
		if (isset(template::$blocks['users'])):
			foreach(template::$blocks['users'] as $users):
	?>
				<tr>
					<td><a href="../user.php?id=<?=$users['ID']; ?>"><?=$users['USERNAME']; ?></a></td>
					<td><?=$users['EMAIL']; ?></td>
					<td><?=$users['POSTS']; ?></td>
					<td>
						<a href="user.php?id=<?=$users['ID']; ?>" class="button greyB">bearbeiten</a>
						<a href="users.php?delete=<?=$users['ID']; ?>" class="button redB">löschen</a>
					</td>
				</tr>
	<?php
			endforeach;
		endif;
	?>

</table>

<div style="padding:5px">
	<?php if (template::getVar('PAGES_NUM') > '1'): ?> | Seite <?=template::getVar('PAGE'); ?> von <?=template::getVar('PAGES_NUM'); ?> | <?=template::getVar('PAGES'); ?><?php endif; ?>
</div>

<?php template::display('footer'); ?>