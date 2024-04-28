<?php

// Inclusion du fichier contenant les paramètres de connexion à la base de données
include("loc.php");

// Vérification si l'utilisateur est banni
if (isset($_COOKIE['user_id'])) {
    $ban = $SQLconn->profile->checkBan($_COOKIE['user_id']);
    if ($ban) {
        header("Location:profile.php?id=" . $_COOKIE['user_id']);
    }
}

// Inclusion du fichier contenant la fonction de réponse à un post
include("BoutDePages/repondrePost.php");

// Inclusion du fichier contenant l'en-tête commun à toutes les pages
include("BoutDePages/header.php");

?>
<!DOCTYPE html>
<html>

<head>
    <title>Y - Accueil</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body class="text-body bg-body" data-bs-theme="dark">

    <main id="mainContent">
        <div class="px-3">
            <div class="container">
                <?php
                if ($SQLconn->loginStatus->loginSuccessful) {
                    ?>
                    <div class="row mb-3">
                        <div class="col text-center">
                            <button class="btn btn-outline-secondary" id="bestPostsButton">Meilleurs posts du moment</button>
                            <button class="btn btn-outline-secondary active" id="recentPostsButton">Post récent que vous suivez</button>
                        </div>
                    </div>
                    <div id="postsContainer"></div>

                <?php
                } else {
                ?>
                    
                    <div class="row align-items-start justify-content-center">
                        <div class="col-8">
                            <div class='card rounded-3 outline-secondary text-bg-secondary ps-10 pe-10'>
                                <div class="text-center card-header">
                                    <h2 class="card-title ">Meilleurs posts du moment :</h2>
                                </div>
                                <div class="card-body" style="height: calc(100vh - 200px); overflow-y: auto;">
                                    <?php
                                    // Récupération et affichage des meilleurs posts (pour les utilisateurs non connectés)
                                    $allPosts = $SQLconn->profile->getBestPosts(0);
                                    foreach ($allPosts as $post) {
                                        $infos = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]);
                                        $SQLconn->profile->afficherPosts($post, $infos);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <br>
    </main>

    <!-- Inclusion du script jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Inclusion du script JS pour la gestion du profil -->
    <script src="JS/monProfile.js"></script>
    <!-- Inclusion du script JS pour l'index  -->
    <script src="JS/index.js"></script>
    <!-- Inclusion du script Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
