<?php

include ("../loc.php"); // Inclut le fichier loc.php contenant les paramètres de connexion à la base de données

if(isset($_POST['postId']) && isset($_POST['message'])) { // Vérifie si les identifiants de post et le message ont été envoyés via une requête POST
    $postId = $_POST['postId']; // Récupère l'identifiant du post depuis la requête POST
    $message = $_POST['message']; // Récupère le message depuis la requête POST

    // Utilise deux fonctions différentes pour marquer le post comme sensible
    $result1= $SQLconn->notification->notifMarquerSensible($postId, $message); // Notifie le post comme sensible avec un message spécifier
    $result2= $SQLconn->profile->marquerSensible($postId); // Marque le post comme sensible via la fonction marquerSensible

    // Vérifie si les deux opérations de marquage ont réussi
    if($result1 && $result2) {
        echo "Post marqué sensible"; // Affiche un message de réussite si les deux opérations ont réussi
    } else {
        echo "Erreur lors du marquage du post sensible."; // Affiche un message d'erreur si une ou les deux opérations ont échoué
    }

} else {
    echo "Erreur lors de la récupération des données."; // Affiche un message d'erreur si les données requises n'ont pas été envoyées
}

?>
