<?php


$followers = $SQLconn->statistiques->getFollowers($_COOKIE['user_id']);
$followed =$SQLconn->statistiques->getFollowed($_COOKIE['user_id']);
$posts = $SQLconn->statistiques->getPosts($_COOKIE['user_id']);

$nbFollowers = count($followers);
$nbFollowed = count($followed);
$nbPosts = count($posts);

$moyenneAgeFollower = $SQLconn->statistiques->calculateAverageAge($followers);
$moyenneAgeFollowed = $SQLconn->statistiques->calculateAverageAge($followed);
$nbPost= $SQLconn->statistiques->getNbPost($_COOKIE['user_id']);
$nbPostParJour = $SQLconn->statistiques->getNbPostParJour($_COOKIE['user_id']);
$nbPostParSemaine = $SQLconn->statistiques->getNbPostParSemaine($_COOKIE['user_id']);
$nbPostParMois = $SQLconn->statistiques->getNbPostParMois($_COOKIE['user_id']);
?>

<link rel="stylesheet" href="styles.css">
<div class="card outline-secondary rounded-3">
    <div class="text-center card-header">
        <h1 class="card-title"><?php 
        if ($Infos["avatar"] != NULL){
            echo "<img src='".$Infos["avatar"]."' class='avatar avatar-xxl'>";
        }
         ?>
        </h1>
        <?php echo $Infos["username"]?>
        <br>
    </div>
    <div class="card-body">
        <div class="card outline-secondary rounded-3">
            <div class="container text-center">
                <div class="row align-items-center">
                    <div class="col">
                        <label for="followers">Followers : <?php echo $nbFollowers ?></label>
                    </div>
                    <div class="col">
                        <label for="following">Following : <?php echo $nbFollowed ?></label>
                    </div>
                </div>

                <div class="row align-items-center">
                    <div class="col">
                        <label for="moyenneAgeFollower">Moyenne d'âge des followers : <?php echo $moyenneAgeFollower ?></label>
                    </div>
                    <div class="col">
                        <label for="moyenneAgeFollowed">Moyenne d'âge des followed : <?php echo $moyenneAgeFollowed ?></label>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="card outline-secondary rounded-3">
            <div class="container text-center">
                <div class="row align-items-center">
                    <div class="col">
                        <label for="nbPost">Nombre de post : <?php echo $nbPosts ?></label>
                    </div>
                    <div class="col">
                        <label for="nbPostParJour">Nombre de post en moyenne par jour : <?php echo $nbPostParJour ?></label>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <label for="nbPostParSemaine">Nombre de post en moyenne par semaine : <?php echo $nbPostParSemaine ?></label>
                    </div>
                    <div class="col">
                        <label for="nbPostParMois">Nombre de post en moyenne par mois : <?php echo $nbPostParMois ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <?php include("BoutDePages/footer.php"); ?> -->
