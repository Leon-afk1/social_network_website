<?php

if (isset($_POST["submitReponse"])) {
    $ajouterPost = true;
    $result=ajouterNewPost($_COOKIE['user_id'], $_POST["idPost"]);
    if ($result["Successful"]){
        $ajouterPost = false;
    }else{
        $erreurPost = $result["ErrorMessage"];
    }
}

?>