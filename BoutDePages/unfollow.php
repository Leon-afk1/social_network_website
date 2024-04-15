<?php

session_start();

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

function unfollow($userId, $userIdToUnfollow){
    global $conn;

    $query = "DELETE FROM follower WHERE id_utilisateur = $userId AND id_utilisateur_suivi = $userIdToUnfollow";
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

ConnectToDataBase();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = unfollow($_SESSION['user_id'], $_POST['idToUnfollow']);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}

?>
