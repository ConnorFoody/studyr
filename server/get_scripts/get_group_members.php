<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['group']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$member_ids = $db->getGroupMembers($db->getGroupname($_GET['group']));
		return $db->listOfIdsToUsers($member_ids)
	}
?>
