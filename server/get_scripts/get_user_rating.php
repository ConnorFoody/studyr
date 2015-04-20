<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['user']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$rating = $db->getUserRating($_GET['user']);
		echo json_encode($rating);
	}
?>