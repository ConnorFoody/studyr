<?php
    include '../studyrlib.php';

    $db = new Studyr();
    if(!$_GET['username']){
        echo json_encode(['ERROR: incorrect parameters']);
    }
    else{
    	$id = $db->getUserId($_GET['username']);
    	echo json_encode($id);
    }
?>