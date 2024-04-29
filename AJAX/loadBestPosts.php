<?php
// Inclure le fichier de configuration ou de connexion à la base de données
include("../loc.php");
?>

<div class="row align-items-start justify-content-center">
    <div class="col-8">
        <div class='card rounded-3 outline-secondary text-bg-secondary ps-10 pe-10'>
            <div class="card-body" style="height: calc(100vh - 200px); overflow-y: auto;">
                <?php
                // Récupération et affichage des meilleurs posts
                $allPosts = $SQLconn->profile->getBestPosts($SQLconn->loginStatus->userID);
                foreach ($allPosts as $post) {
                    $infos = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]);
                    $SQLconn->profile->afficherPosts($post, $infos);
                }
                ?>
            </div>
        </div>
    </div>
</div>
