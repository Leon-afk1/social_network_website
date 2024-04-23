<?php
session_start(); 

include("../loc.php");
// include("repondrePost.php");

if (isset($_GET['page']) && is_numeric($_GET['page']) && isset($_GET['postId'])) {
    $page = intval($_GET['page']);
    $postId = intval($_GET['postId']);
    $start = ($page - 1) * 5; 

    
    // Récupérer les 5 prochains posts à partir de la base de données
    $morePosts = $SQLconn->profile->GetNextReponse($postId, $start, 5);
    if (count($morePosts) > 0) {
        foreach ($morePosts as $post) {
            $InfoUser = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]);
            $SQLconn->profile->afficherPosts($post, $InfoUser);
        }
    } else {
        echo ''; 
    }
} else {
    echo 'Invalid page number'; 
}


?>
