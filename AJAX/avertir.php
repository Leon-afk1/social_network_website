<?php

include ("../loc.php");

// Vérifier si les données postId et avertissement sont définies dans la requête POST
if(isset($_POST['postId']) && isset($_POST['avertissement'])) {
    $postId = $_POST['postId']; // Récupérer l'identifiant du post à avertir
    $avertissement = $_POST['avertissement']; // Récupérer le contenu de l'avertissement

    // Appeler la fonction pour envoyer un avertissement pour le post spécifié
    $result= $SQLconn->notification->avertissement($postId, $avertissement);

    // Vérifier si l'opération d'envoi de l'avertissement a réussi
    if($result) {
        echo "Avertissement envoyé."; // Afficher un message de succès si l'avertissement est envoyé avec succès
    } else {
        echo "Erreur lors de l'envoi de l'avertissement."; // Afficher un message d'erreur en cas d'échec de l'envoi de l'avertissement
    }

} else {
    echo "Erreur lors de la récupération des données."; // Afficher un message d'erreur si les données postId et avertissement ne sont pas définies
}

?>
