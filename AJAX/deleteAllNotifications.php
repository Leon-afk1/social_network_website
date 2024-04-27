<?php

include ("../loc.php");

// Vérifier si l'identifiant de l'utilisateur est défini dans les cookies
if (!isset($_COOKIE['user_id'])) {
    header("Location: index.php"); // Rediriger vers la page d'accueil si l'utilisateur n'est pas connecté
    exit();
}

// Appeler la fonction pour supprimer toutes les notifications de l'utilisateur spécifié dans la requête GET
$result = $SQLconn->notification->supprimerAllNotifications($_GET["idUser"]);

// Vérifier si la suppression des notifications est réussie
if ($result) {
    echo "success"; // Afficher "success" si la suppression réussit
} else {
    echo $result; // Sinon, afficher le message d'erreur retourné par la fonction de suppression
}

?>
