<?php
session_start(); // Démarrer la session pour récupérer les informations de l'utilisateur connecté

include("../loc.php"); // Inclure le fichier de localisation pour accéder à la base de données et d'autres fonctionnalités

// Vérifier si le numéro de page et l'identifiant du post sont spécifiés dans la requête GET et s'ils sont numériques
if (isset($_GET['page']) && is_numeric($_GET['page']) && isset($_GET['postId']) && is_numeric($_GET['postId'])) {
    $page = intval($_GET['page']); // Convertir le numéro de page en entier
    $postId = intval($_GET['postId']); // Convertir l'identifiant du post en entier
    $start = ($page - 1) * 5; // Calculer l'indice de départ pour la récupération des réponses

    // Récupérer les 5 prochaines réponses à partir de la base de données pour le post spécifié
    $morePosts = $SQLconn->profile->GetNextReponse($postId, $start, 5);

    // Vérifier s'il y a des réponses à afficher
    if (count($morePosts) > 0) {
        // Afficher chaque réponse récupérée
        foreach ($morePosts as $post) {
            $InfoUser = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]); // Récupérer les informations de l'utilisateur pour chaque réponse
            $SQLconn->profile->afficherPosts($post, $InfoUser); // Afficher la réponse avec les informations de l'utilisateur
        }
    } else {
        echo ''; // Afficher une chaîne vide si aucune réponse n'est trouvée pour la page spécifiée
    }
} else {
    echo 'Invalid page number'; // Afficher un message d'erreur si le numéro de page ou l'identifiant du post spécifié est invalide
}

?>
