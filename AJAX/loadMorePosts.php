<?php
session_start(); // Démarrer la session pour récupérer les informations de l'utilisateur connecté

include("../loc.php"); // Inclure le fichier de localisation pour accéder à la base de données et d'autres fonctionnalités

// Vérifier si le numéro de page est spécifié dans la requête GET et s'il est numérique
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']); // Convertir le numéro de page en entier
    $start = ($page - 1) * 5; // Calculer l'indice de départ pour la récupération des posts

    $Infos = $_SESSION['Infos']; // Récupérer les informations de l'utilisateur connecté depuis la session

    // Récupérer les 5 prochains posts à partir de la base de données pour l'utilisateur connecté
    $morePosts = $SQLconn->profile->GetNextPosts($Infos['id_utilisateur'], $start, 5);

    // Vérifier s'il y a des posts à afficher
    if (count($morePosts) > 0) {
        // Afficher chaque post récupéré
        foreach ($morePosts as $post) {
            $SQLconn->profile->afficherPosts($post, $Infos);
        }
    } else {
        echo ''; // Afficher une chaîne vide si aucun post n'est trouvé pour la page spécifiée
    }
} else {
    echo 'Invalid page number'; // Afficher un message d'erreur si le numéro de page spécifié est invalide
}

?>
