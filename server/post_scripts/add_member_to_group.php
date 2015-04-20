<?php
    include '../studyrlib.php';

    $db = new Studyr();
    // use get instead of post b/c easier to write client side
    if(!$_GET['user'] or !$_GET['group']){
        echo json_encode(['ERROR: incorrect parameters']);
    }
    else{
        $db->addUserToGroup($_GET['user'], $_GET['group']);
        // return value?
    }
?>