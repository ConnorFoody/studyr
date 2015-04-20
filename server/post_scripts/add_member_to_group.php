<?php
    include '../studyrlib.php';

    $db = new Studyr();
    if(!$_POST['user'] or !$_POST['group']){
        echo json_encode(['ERROR: incorrect parameters']);
    }
    else{

    }
?>