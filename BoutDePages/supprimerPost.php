<?php

function ConnectToDataBase() {
    $serveur = 'localhost';
    $utilisateur = 'root';
    $motDePasse = '';
    $nomBaseDeDonnees = 'twitterlike';

    global $conn;

    $conn = new mysqli($serveur, $utilisateur, $motDePasse, $nomBaseDeDonnees);
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }
}

connectToDataBase();

if (isset($_COOKIE["user_id"]) == false){
    echo "Vous n'êtes pas connecté";
}

if (isset($_GET["id"]) == false){
    echo "Erreur lors de la suppression du post";
}else{
    deletePost($_GET["id"]);
    // echo "post numéro ".$_GET["id"]." supprimé avec succès !";
}

function executeRequete($req){
    global $conn;
    $resultat = $conn->query($req);
    if(!$resultat) 
    {
        die("Erreur sur la requete sql.<br>Message : " . $conn->error . "<br>Code: " . $req);
    }
    return $resultat; 
}

function SecurizeString_ForSQL($string){
    $string = trim($string);
    $string = stripslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}



function deletePost($id){

    $query = "SELECT * FROM `post` WHERE `id_post` = $id";
    $result = executeRequete($query);
    if ($result->num_rows == 0){
        exit();
    }
    $result = $result->fetch_assoc();


    

    if ($result["id_utilisateur"] != $_COOKIE["user_id"]){
        exit();
    }

    if ($result["image_path"] != ""){
        unlink("../".$result["image_path"]);
    }

    $query = "DELETE FROM `post` WHERE `id_post` = $id";
    $result = executeRequete($query);

    if ($result){
        echo "Post supprimé";
    }else{
        echo "Erreur lors de la suppression du post";
    }
}


?>
