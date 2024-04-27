<?php
include("../loc.php"); // Inclut le fichier loc.php contenant les paramètres de connexion

if (isset($_POST['postId'])) { // Vérifie si un identifiant de post a été envoyé via une requête POST
    $postId = $_POST['postId']; // Récupère l'identifiant du post depuis la requête POST

    // Utilise deux fonctions différentes pour remettre le post en question comme non offensant
    $result1 = $SQLconn->notification->notificationRemettrePost($postId); // Notifie que le post n'est plus offensant via la fonction notificationRemettrePost
    $result2 = $SQLconn->profile->remettrePost($postId); // Remet le post via la fonction remettrePost

    // Vérifie si les deux opérations de remise ont réussi
    if ($result1 && $result2) {
        echo "Post marqué comme non offensant"; // Affiche un message de réussite si les deux opérations ont réussi
    } else {
        echo "Erreur lors du marquage du post."; // Affiche un message d'erreur si une ou les deux opérations ont échoué
    }
} else {
    echo "Erreur lors de la récupération des données."; // Affiche un message d'erreur si aucun identifiant de post n'a été envoyé
}
?>
