<?php

include ("../loc.php"); // Inclut le fichier loc.php contenant les paramètres de connexion à la base de données

if(isset($_POST['postId']) ) { // Vérifie si un identifiant de post a été envoyé via une requête POST
    $postId = $_POST['postId']; // Récupère l'identifiant du post depuis la requête POST

    // Utilise deux fonctions différentes pour marquer le post comme non sensible
    $result1= $SQLconn->notification->notificationNonSensible($postId); // Marque le post comme non sensible via la fonction notificationNonSensible
    $result2= $SQLconn->profile->enleverMarqueSensible($postId); // Enlève la marque sensible du post via la fonction enleverMarqueSensible

    // Vérifie si les deux opérations de marquage ont réussi
    if($result1 && $result2) {
        echo "Post marqué comme non sensible"; // Affiche un message de réussite si les deux opérations ont réussi
    } else {
        echo "Erreur lors du marquage du post."; // Affiche un message d'erreur si une ou les deux opérations ont échoué
    }

} else {
    echo "Erreur lors de la récupération des données."; // Affiche un message d'erreur si aucun identifiant de post n'a été envoyé
}

?>
