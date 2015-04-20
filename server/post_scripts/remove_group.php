<?php
    include '../studyrlib.php';

    $db = new Studyr();
    // use get instead of post b/c easier to write client side
    if(!$_GET['group']){
        echo json_encode(['ERROR: incorrect parameters']);
    }
    else{
    	$db->removeGroup($_GET['group']);
    }
?>