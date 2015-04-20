<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['user']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$rating = $db->getUserRating($db->getUserId($_GET['user_id']));
		echo json_encode($rating);
	}
?>