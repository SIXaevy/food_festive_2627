<?php
    include "session.php";
    include "models/dish.php";
    
    $result = [];

    if(array_key_exists("id", $_GET)) {
        $result['deleted'] = false;
        $is_deleted = delete_dish($_SESSION['id'], $_GET['id']);

        if($is_deleted) {
            $result['deleted'] = true;
        } 
    }

    echo json_encode($result);
?>