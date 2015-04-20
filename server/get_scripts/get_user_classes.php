<?php
	include '../studyrlib.php';

	$db = new Studyr();
	if(!$_GET['user']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$classes = $db->getUserClasses($_GET['user']);
		echo json_encode($classes);
	}
?>
