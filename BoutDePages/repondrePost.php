<?php

if (isset($_POST["submitReponse"])) {
    $ajouterPost = true;
    $result=$SQLconn->profile->ajouterNewPost($_COOKIE['user_id']);
    if ($result["Successful"]){
        $ajouterPost = false;
    }else{
        $erreurPost = $result["ErrorMessage"];
    }
}

?>