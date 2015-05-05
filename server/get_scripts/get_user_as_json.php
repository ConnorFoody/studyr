<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['user']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$user = $db->getUserAsJson($_GET['user']);
		echo json_encode($user);
	}
?>