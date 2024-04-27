<?php
// Si il y a une requete post
if (isset($_POST["submitReponse"])) {
    $ajouterPost = true;
    //Appelle de la fonction qui va rajouter le formulaire de post
    $result=$SQLconn->profile->ajouterNewPost($_COOKIE['user_id'], $_POST["idPost"]);
    if ($result["Successful"]){
        $ajouterPost = false;
    }else{
        $erreurPost = $result["ErrorMessage"];
    }
}

?>