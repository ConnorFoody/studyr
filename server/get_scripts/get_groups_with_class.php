<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['class']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$group_ids = $db->getGroupsWithClass($_GET['class']);
		echo json_encode($group_ids);
	}
?>
