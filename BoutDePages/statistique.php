<?php
$decimales_max = 1;

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
$moyenneAgeFollower = number_format($moyenneAgeFollower, $decimales_max);
$moyenneAgeFollowed = $SQLconn->statistiques->calculateAverageAge($followed);
$moyenneAgeFollowed = number_format($moyenneAgeFollowed, $decimales_max);

// Récupération du nombre de posts par jour, semaine et mois de l'utilisateur
$nbPost = $SQLconn->statistiques->getNbPost($_COOKIE['user_id']);
$nbPostParJour = $SQLconn->statistiques->getNbPostParJour($_COOKIE['user_id']);
$nbPostParJour = number_format($nbPostParJour, $decimales_max);
$nbPostParSemaine = $SQLconn->statistiques->getNbPostParSemaine($_COOKIE['user_id']);
$nbPostParSemaine = number_format($nbPostParSemaine, $decimales_max);
$nbPostParMois = $SQLconn->statistiques->getNbPostParMois($_COOKIE['user_id']);
$nbPostParMois = number_format($nbPostParMois, $decimales_max);

// Récupération du nombre total de likes de l'utilisateur
$nbLikeEmis = $SQLconn->statistiques->getNbLikeEmis($_COOKIE['user_id']);
$nbLikeEmisParSemaine = $SQLconn->statistiques->getNbLikeEmisParSemaine($_COOKIE['user_id']);
$nbLikeEmisParSemaine = number_format($nbLikeEmisParSemaine, $decimales_max);
$nbLikeEmisParMois = $SQLconn->statistiques->getNbLikeEmisParMois($_COOKIE['user_id']);
$nbLikeEmisParMois = number_format($nbLikeEmisParMois, $decimales_max);

$nbLikeRecu = $SQLconn->statistiques->getNbLikeRecu($_COOKIE['user_id']);
$nbLikeRecuParSemaine = $SQLconn->statistiques->getNbLikeRecuParSemaine($_COOKIE['user_id']);
$nbLikeRecuParSemaine = number_format($nbLikeRecuParSemaine, $decimales_max);
$nbLikeRecuParMois = $SQLconn->statistiques->getNbLikeRecuParMois($_COOKIE['user_id']);
$nbLikeRecuParMois = number_format($nbLikeRecuParMois, $decimales_max);

$nbLikeRecuParPost = $nbLikeRecu / $nbPosts;
$nbLikeRecuParPost = number_format($nbLikeRecuParPost, $decimales_max);
?>

<head>
    <title>Statistiques</title>
</head>

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
                        <image src="./icon/followers.png" alt="followers" width="50" height="50">
                        <br>
                        <label for="followers">Followers : <?php echo $nbFollowers ?></label>
                    </div>
                    <!-- Affichage du nombre de followed -->
                    <div class="col">
                        <image src="./icon/followed.png" alt="followed" width="50" height="50">
                        <br>
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
                <br>
            </div>
        </div>
        <br>
        <!-- Carte pour afficher les statistiques de posts -->
        <!-- Carte pour afficher les statistiques de posts -->
<div class="card outline-secondary rounded-3">
    <div class="container text-center">
        <div class="row align-items-center">
            <!-- Affichage du nombre total de posts -->
            <div class="col">
                <image src="./icon/post.png" alt="posts" width="50" height="50">
                <br>
                <label for="nbPost">Nombre de posts total : <?php echo $nbPosts ?></label>
            </div>
            <!-- Affichage du nombre moyen de posts par jour, semaine et mois -->
            <div class="col">
                <image src="./icon/stats.png" alt="stats" width="50" height="50">
                <br>
                <label for="moyenne" style="font-size: 20px;">Moyenne de posts :</label>
                <br>
                <label for="nbPostParJour">Par jour : <?php echo $nbPostParJour ?></label>
                <br>
                <label for="nbPostParSemaine">Par semaine : <?php echo $nbPostParSemaine ?></label>
                <br>
                <label for="nbPostParMois">Posts par mois : <?php echo $nbPostParMois ?></label>
            </div>
        </div>
        <br>
    </div>
</div>
<br>
<div class="card outline-secondary rounded-3">
    <div class="container text-center">
        <div class="row align-items-center">
            <!-- Affichage du nombre total de likes -->
            <div class="col">
                <image src="./icon/heart_red.png" alt="likes" width="50" height="50">
                <br>
                <label for="intitulé" style="font-size: 20px;">Likes émis</label>
                <br>
                <label for="nbLike">Total : <?php echo $nbLikeEmis ?></label>
                <br>
                <label for="nbLikeParSemaine">Par semaine : <?php echo $nbLikeEmisParSemaine ?></label>
                <br>
                <label for="nbLikeParMois">Par mois : <?php echo $nbLikeEmisParMois ?></label>
            </div>
            <div class="col">
                <image src="./icon/hearts.png" alt="likes" width="50" height="50">
                <br>
                <label for="intitulé" style="font-size: 20px;">Likes reçus</label>
                <br>
                <label for="nbLikeRecuTotal">Total : <?php echo $nbLikeRecu ?></label>
                <br>
                <label for="nbLikeRecuParSemaine">Par semaine : <?php echo $nbLikeRecuParSemaine ?></label>
                <br>
                <label for="nbLikeRecuParMois">Par mois : <?php echo $nbLikeRecuParMois ?></label>
                <br>
                <label for="nbLikeParPost">Par post : <?php echo $nbLikeRecuParPost ?></label>
        </div>
    </div>
    <br>
</div>
</div>

