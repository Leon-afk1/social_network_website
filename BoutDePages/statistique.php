<?php
// Récupération des followers, followed et posts de l'utilisateur
$followers = $SQLconn->statistiques->getFollowers($_COOKIE['user_id']);
$followed =$SQLconn->statistiques->getFollowed($_COOKIE['user_id']);
$posts = $SQLconn->statistiques->getPosts($_COOKIE['user_id']);

// Calcul du nombre de followers, de followed et de posts
$nbFollowers = count($followers);
$nbFollowed = count($followed);
$nbPosts = count($posts);

// Calcul de la moyenne d'âge des followers et des followed
$moyenneAgeFollower = $SQLconn->statistiques->calculateAverageAge($followers);
$moyenneAgeFollowed = $SQLconn->statistiques->calculateAverageAge($followed);

// Récupération du nombre de posts par jour, semaine et mois de l'utilisateur
$nbPost = $SQLconn->statistiques->getNbPost($_COOKIE['user_id']);
$nbPostParJour = $SQLconn->statistiques->getNbPostParJour($_COOKIE['user_id']);
$nbPostParSemaine = $SQLconn->statistiques->getNbPostParSemaine($_COOKIE['user_id']);
$nbPostParMois = $SQLconn->statistiques->getNbPostParMois($_COOKIE['user_id']);
?>

<!-- Inclusion de la feuille de style -->
<link rel="stylesheet" href="styles.css">

<!-- Carte pour afficher les statistiques de l'utilisateur -->
<div class="card outline-secondary rounded-3">
    <div class="text-center card-header">
        <!-- Affichage de l'avatar de l'utilisateur -->
        <h1 class="card-title"><?php 
        if ($Infos["avatar"] != NULL){
            echo "<img src='".$Infos["avatar"]."' class='avatar avatar-xxl'>";
        }
         ?>
        </h1>
        <!-- Affichage du nom d'utilisateur -->
        <?php echo $Infos["username"]?>
        <br>
    </div>
    <div class="card-body">
        <!-- Carte pour afficher les statistiques des followers et des followed -->
        <div class="card outline-secondary rounded-3">
            <div class="container text-center">
                <div class="row align-items-center">
                    <!-- Affichage du nombre de followers -->
                    <div class="col">
                        <label for="followers">Followers : <?php echo $nbFollowers ?></label>
                    </div>
                    <!-- Affichage du nombre de followed -->
                    <div class="col">
                        <label for="following">Following : <?php echo $nbFollowed ?></label>
                    </div>
                </div>

                <div class="row align-items-center">
                    <!-- Affichage de la moyenne d'âge des followers -->
                    <div class="col">
                        <label for="moyenneAgeFollower">Moyenne d'âge des followers : <?php echo $moyenneAgeFollower ?></label>
                    </div>
                    <!-- Affichage de la moyenne d'âge des followed -->
                    <div class="col">
                        <label for="moyenneAgeFollowed">Moyenne d'âge des followed : <?php echo $moyenneAgeFollowed ?></label>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <!-- Carte pour afficher les statistiques de posts -->
        <div class="card outline-secondary rounded-3">
            <div class="container text-center">
                <div class="row align-items-center">
                    <!-- Affichage du nombre total de posts -->
                    <div class="col">
                        <label for="nbPost">Nombre de post : <?php echo $nbPosts ?></label>
                    </div>
                    <!-- Affichage du nombre moyen de posts par jour -->
                    <div class="col">
                        <label for="nbPostParJour">Nombre de post en moyenne par jour : <?php echo $nbPostParJour ?></label>
                    </div>
                </div>
                <div class="row align-items-center">
                    <!-- Affichage du nombre moyen de posts par semaine -->
                    <div class="col">
                        <label for="nbPostParSemaine">Nombre de post en moyenne par semaine : <?php echo $nbPostParSemaine ?></label>
                    </div>
                    <!-- Affichage du nombre moyen de posts par mois -->
                    <div class="col">
                        <label for="nbPostParMois">Nombre de post en moyenne par mois : <?php echo $nbPostParMois ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

