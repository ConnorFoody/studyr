<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['group']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$group = $db->getGroupAsJson($_GET['group']);
		echo json_encode($group);
?>
