<?php
    include '../studyrlib.php';

    $db = new Studyr();
    // use get instead of post b/c easier to write client side
    if(!$_GET['user'] or !$_GET['classes']){
        echo json_encode(['ERROR: incorrect parameters']);
    }
    else{
    	$db->createUser($_GET['user'], $_GET['classes']);
    }
?>