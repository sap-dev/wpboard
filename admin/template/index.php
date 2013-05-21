<?php template::display('header'); ?>

<style>
	.number {
		font-weight: bold;
		font-size: 18px;
		display: block;
		padding-bottom: 5px;
	}

	.s td:not(:last-child) {
		border-right: 1px solid #e0e0e0;
	}

	.s {
		border-top: 1px solid #e0e0e0;
	}

	.s tr td {
		border-bottom: 1px solid #e0e0e0;
	}
</style>

<div class="h2box" style="margin-bottom:0;">
	<h2>Übersicht</h2>
</div>

<table border="0" cellspacing="0" cellpadding="5" width="100%" style="text-align: center;" class="s">
	<tr>
		<td width="25%">
			<span class="number"><?=template::getVar('FORUMS_NUM'); ?></span>
			For<?php if (template::getVar('FORUMS_NUM') == '1'): ?>um<?php else: ?>en<?php endif; ?>
		</td>

		<td width="25%">
			<span class="number"><?=template::getVar('TOPICS_NUM'); ?></span>
			Them<?php if (template::getVar('TOPICS_NUM') == '1'): ?>a<?php else: ?>en<?php endif; ?>
		</td>

		<td width="25%">
			<span class="number"><?=template::getVar('POSTS_NUM'); ?></span>
			Beitr<?php if (template::getVar('POSTS_NUM') == '1'): ?>ag<?php else: ?>äge<?php endif; ?>
		</td>

		<td width="25%">
			<span class="number"><?=template::getVar('USERS_NUM'); ?></span>
			Mitglied<?php if (template::getVar('USERS_NUM') != 1): ?>er<?php endif; ?>
		</td>
	</tr>

	<tr>
		<td colspan="4" style="border-right: 0;">
			Das neueste Mitglied ist <b><a class="<?=template::getVar('NEWEST_USER_LEVEL'); ?>" href="../user.php?id=<?=template::getVar('NEWEST_USER_ID'); ?>"><?=template::getVar('NEWEST_USERNAME'); ?></a></b>. Du verwendest Itschi <?=template::getVar('VERSION'); ?>.
		</td>
	</tr>
</table>

<div class="h2box">
	<h1 class="fLeft" style="width:30%;">Synchronisieren</h1>

	<div class="fRight" style="padding-top: 30px; width: 30%; text-align: right;">
		<a href="index.php?sync=0&menu=<?=template::getVar("menu");?>" class="button">Alles synchronisieren</a>
	</div>

	<div class="clear"></div>
</div>

<form action="index.php" method="post">
	<table cellpadding="5" cellspacing="0" width="100%">
		<tr>
			<td width="90%"><b>Globale Statistiken</b><br /><span class="grey">Mitglieder-, Themen-, Beitragszähler; Neuster User</span></td>
			<td valign="top" align="center">
				<a href="index.php?sync=1&menu=<?=template::getVar("menu");?>" class="button">Synchronisieren</a>
				<?php if (template::getVar('SYNC') == 1 || template::getVar('SYNC') == 0): ?> <br /><br /><span class="green">Synchronisiert</span><?php endif; ?>
			</td>
		</tr>
		<tr>
			<td><b>Themenstatistiken</b><br /><span class="grey">Letzter Beitrag, Anzahl an Beiträgen</span></td>
			<td align="center">
				<a href="index.php?sync=2&menu=<?=template::getVar("menu");?>" class="button">Synchronisieren</a>
				<?php if (template::getVar('SYNC') == 2|| template::getVar('SYNC') == 0): ?> <br /><br /><span class="green">Synchronisiert</span><?php endif; ?>
			</td>
		</tr>
		<tr>
			<td><b>Forenstatistiken</b><br /><span class="grey">Letzter Beitrag, Anzahl an Themen</span></td>
			<td align="center">
				<a href="index.php?sync=3&menu=<?=template::getVar("menu");?>" class="button">Synchronisieren</a>
				<?php if (template::getVar('SYNC') == 3|| template::getVar('SYNC') == 0): ?> <br /><br /><span class="green">Synchronisiert</span><?php endif; ?>
			</td>
		</tr>
		<tr>
			<td><b>Cache leeren</b><br /><span class="grey">Templates, Bots, Ränge, Smilies</span></td>
			<td align="center">
				<a href="index.php?sync=4&menu=<?=template::getVar("menu");?>" class="button">Synchronisieren</a>
				<?php if (template::getVar('SYNC') == 4|| template::getVar('SYNC') == 0): ?> <br /><br /><span class="green">Synchronisiert</span><?php endif; ?>
			</td>
		</tr>
	</table>
</form>

<?php template::display('footer'); ?>