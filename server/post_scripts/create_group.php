<?php
    include '../studyrlib.php';

    $db = new Studyr();
    // use get instead of post b/c easier to write client side
    if(!$_GET['groupname'] or !$_GET['user'] or !$_GET['description'] or !$_GET['class']){
        echo json_encode(['ERROR: incorrect parameters']);
    }
    else{
    	$db->createGroup($_GET['groupname'], $_GET['user'], $_GET['description'], $_GET['class']);
    	
    }
?>