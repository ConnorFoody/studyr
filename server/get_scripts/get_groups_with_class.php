<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['class']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$group_ids = $db->getGroupsWithClass($_GET['class']);
		$groups[];
		$i = 0; 
		foreach($group_ids as $group_id){
			$groups[i] = $db->getGroupAsJson($group_id);
		}
		echo json_encode($groups);
	}
?>
