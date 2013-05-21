<?php
	require '../base.php';
	
	$statusUpdate = $db->chars(htmlspecialchars($_POST['status']));
	$db->query("
		UPDATE itschi_users
		SET user_status = '" . $statusUpdate . "'
		WHERE user_id = " . (int)$user->row['user_id']
	);
	
	die('Status gespeichert.');
?>
