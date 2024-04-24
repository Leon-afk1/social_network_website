<?php

include ("../loc.php"); // Inclut le fichier loc.php contenant les paramètres de connexion à la base de données

// Vérifie si les données POST 'postId' et 'message' sont définies
if(isset($_POST['postId']) && isset($_POST['message'])) {
    $postId = $_POST['postId']; // Récupère l'identifiant du post depuis les données POST
    $message = $_POST['message']; // Récupère le message depuis les données POST

    // Appelle la méthode de notification pour retirer le post avec l'identifiant et le message donnés
    $result1= $SQLconn->notification->notificationRetirerPost($postId, $message);
    // Appelle la méthode de profil pour retirer le post avec l'identifiant donné
    $result2= $SQLconn->profile->retirerPost($postId);

    // Vérifie si les deux opérations de retrait ont réussi
    if($result1 && $result2) {
        echo "Post retiré."; // Affiche un message de succès si le post a été retiré avec succès
    } else {
        echo "Erreur lors du retrait du post."; // Affiche un message d'erreur si une erreur s'est produite lors du retrait du post
    }
} else {
    echo "Erreur lors de la récupération des données."; // Affiche un message d'erreur si les données POST 'postId' et 'message' ne sont pas définies
}

?>