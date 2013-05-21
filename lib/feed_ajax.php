<?php
	header('cache-control: no-cache');
	header('pragma: no-cache');

	require '../base.php';
	include 'feed.php';

	if (empty($_POST['limit'])) {
		exit;
	}

	Itschi\lib\feed(min(200, (int)$_POST['limit'] + 5));

	template::display('feed' . ((int)$_POST['sidebar'] == 1 ? '_sidebar' : ''));
?>