<?php

include ("../loc.php");

// Vérifier si l'identifiant de l'utilisateur est défini dans les cookies
if (!isset($_COOKIE['user_id'])) {
    header("Location: index.php"); // Rediriger vers la page d'accueil si l'utilisateur n'est pas connecté
    exit();
}

// Vérifier si l'identifiant de la notification est défini dans la requête GET
if (isset($_GET["id"])) {
    // Appeler la fonction pour supprimer la notification spécifiée dans la requête GET
    $result = $SQLconn->notification->supprimerNotification($_GET["id"]);

    // Vérifier si la suppression de la notification est réussie
    if ($result) {
        echo "success"; // Afficher "success" si la suppression réussit
    } else {
        echo "error"; // Sinon, afficher "error"
    }
} 

?>
