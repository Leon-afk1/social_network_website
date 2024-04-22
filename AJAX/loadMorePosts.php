<?php
session_start(); 

include("../loc.php");

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
    $start = ($page - 1) * 5; 

    $Infos = $_SESSION['Infos'];
    
    // Récupérer les 5 prochains posts à partir de la base de données
    $morePosts = $SQLconn->profile->GetNextPosts($Infos['id_utilisateur'], $start, 5);

    if (count($morePosts) > 0) {
        foreach ($morePosts as $post) {
            $SQLconn->profile->afficherPosts($post, $Infos);
        }
    } else {
        echo ''; 
    }
} else {
    echo 'Invalid page number'; 
}


?>
