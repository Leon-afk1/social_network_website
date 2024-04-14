<?php
session_start(); // Démarrez la session PHP si ce n'est pas déjà fait

// Inclure vos fichiers nécessaires
include("../loc.php");
include("repondrePost.php");

// Assurez-vous que la page est définie et valide
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
    $start = ($page - 1) * 5; // Calculer le début des résultats

    // Accédez aux informations stockées dans les sessions PHP
    $Infos = $_SESSION['Infos'];
    
    // Récupérer les 5 prochains posts à partir de la base de données
    $morePosts = GetNextPosts($Infos['id_utilisateur'], $start, 5);

    if (count($morePosts) > 0) {
        foreach ($morePosts as $post) {
            afficherPosts($post, $Infos);
        }
    } else {
        echo ''; // Renvoyer une chaîne vide si aucun post n'est trouvé
    }
} else {
    echo 'Invalid page number'; // Gérer le cas où la page n'est pas définie ou invalide
}


?>
