<?php
	/**
	 *	@package	com.Itschi.ACP.groups
	 */

	require '../base.php';

	if ($user->row['user_level'] != ADMIN) {
		message_box('Keine Berechtigung!', '../', 'zurück');
		exit;
	}


	/*
		permissions draft
	*/

	$permissions = array(
		/*
		'com.itschi.user.canEditThread',
		'com.itschi.user.canDeleteThread',
		'com.itschi.user.canMoveThread',
		*/
		'com.itschi.acp',

		'com.itschi.acp.page.index' => array(
			'com.itschi.acp.index.canSync'
		),

		'com.itschi.acp.page.settings' => array(
			'com.itschi.acp.settings.canChangeTitle',
			'com.itschi.acp.settings.canChangeDescription',
			'com.itschi.acp.settings.canChangeDesign',
			'com.itschi.acp.settings.canChangeBotSettings',
			'com.itschi.acp.settings.canEnableDelete',
			'com.itschi.acp.settings.canDeactivateForum',
			'com.itschi.acp.settings.canChangeDeactivateMessage',
			'com.itschi.acp.settings.canChangeTopicsPerPage',
			'com.itschi.acp.settings.canChangePostsPerPage',
			'com.itschi.acp.settings.canChangePointsPerTopic',
			'com.itschi.acp.settings.canChangePointsPerPost',
			'com.itschi.acp.settings.canChangeMaxPostsPerDay',
			'com.itschi.acp.settings.canChangeMaxTextLength',
			'com.itschi.acp.settings.canEnableCaptcha',
			'com.itschi.acp.settings.canEnableUnlock',
			'com.itschi.acp.settings.canChangeUnlockDelete',
			'com.itschi.acp.settings.canEnableAvatars',
			'com.itschi.acp.settings.canChangeDefaultAvatar',
			'com.itschi.acp.settings.canChangeMinAvatarDimensions',
			'com.itschi.acp.settings.canChangeMaxAvatarDimensions',
			'com.itschi.acp.settings.canChangeMessagesLimit'
		),

		'com.itschi.acp.page.users',
		'com.itschi.acp.page.banlist',
		'com.itschi.acp.page.forums',
		'com.itschi.acp.page.bots',
		'com.itschi.acp.page.smilies',
		'com.itschi.acp.page.ranks',

		// GROUPS
		'com.itschi.acp.page.groups' => array(
			'com.itschi.acp.groups.canAddGroup',
			'com.itschi.acp.groups.canDeleteGroup',
			'com.itschi.acp.groups.canEditGroup'
		),

		// PLUGINS
		'com.itschi.acp.page.plugins' => array(
			'com.itschi.acp.plugins.canInstallPlugins',
			'com.itschi.acp.plugins.canAddPluginServer',
			'com.itschi.acp.plugins.canRemovePluginServer',
			'com.itschi.acp.plugins.canEditPluginServer'
		)
	);

	template::display('groups', true);
?>