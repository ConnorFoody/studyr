<?php
	include 'studyrlib.php';

	$db = new StudyrLib();
	if(!$_GET['user']){
		echo json_encode(["ERROR: incorrect parameters]");
	}
	else{
		$classes = $db->getUserClasses($db->getUserID($_GET['user']));
		echo json_encode($classes);
	}
?>