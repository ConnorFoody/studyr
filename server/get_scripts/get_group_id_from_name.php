<?php
    include '../studyrlib.php';

    $db = new Studyr();
    if(!$_GET['groupname']){
        echo json_encode(['ERROR: incorrect parameters']);
    }
    else{
    	$id = $db->getGroupId($_GET['groupname']);
    	echo json_encode($id);
    }
?>