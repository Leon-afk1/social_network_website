<?php

if (isset($_POST["submitPost"]) && isset($_FILES["image"])) {
    $ajouterPost = true;
    $result=ajouterNewPost($_COOKIE['user_id'], $_POST["idPost"]);
    if ($result["Successful"]){
        $ajouterPost = false;
    }else{
        $erreurPost = $result["ErrorMessage"];
    }
}

?>