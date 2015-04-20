<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['group']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$member_ids = $db->getGroupMembers($_GET['group']);
		echo json_encode($member_ids)
?>
