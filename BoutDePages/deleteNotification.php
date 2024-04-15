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

function supprimerAllNotifications($userId){
    $query = "DELETE FROM notification WHERE id_utilisateur = $userId";
    if (executeRequete($query)){
        return true;
    } else {
        return false;
    }
}

function supprimerNotification( $idNotification){
    $query = "DELETE FROM notification WHERE id_notification = $idNotification";
    if (executeRequete($query)){
        return true;
    } else {
        return false;
    }
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


ConnectToDataBase();


if (!isset($_COOKIE['user_id'])) {
    header("Location: index.php"); 
    exit();
}

if (isset($_GET["id"])) {
    $result = supprimerNotification($_GET["id"]);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
} else if (isset($_GET["idUser"])) {
    $result = supprimerAllNotifications($_GET["idUser"]);
    if ($result) {
        echo "success";
    } else {
        echo "error all post";
    }
}

?>