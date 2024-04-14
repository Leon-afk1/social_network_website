<?php

$followers=getFollowers($_COOKIE['user_id']);
$followed=getFollowed($_COOKIE['user_id']);
$posts=getPosts($_COOKIE['user_id']);

$nbFollowers=count($followers);
$nbFollowed=count($followed);
$nbPosts=count($posts);

$moyenneAgeFollower=0;
foreach ($followers as $follower){
    $moyenneAgeFollower+=getAge($follower["dateNaissance"]);
}
if ($nbFollowers==0){
    $moyenneAgeFollower=0;
}else{
    $moyenneAgeFollower=$moyenneAgeFollower/$nbFollowers;
}

$moyenneAgeFollowed=0;
foreach ($followed as $follow){
    $moyenneAgeFollowed+=getAge($follow["dateNaissance"]);
}
if  ($nbFollowed==0){
    $moyenneAgeFollowed=0;
}else{
    $moyenneAgeFollowed=$moyenneAgeFollowed/$nbFollowed;
}

$nbPostParJour=getNbPostParJour($_COOKIE['user_id']);

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

            <div class="row align-items-center">
                <div class="col">
                    <label for="nbPostParJour">Nombre de post par jour : <?php echo $nbPostParJour ?></label>
                </div>
            </div>
        </div>
    </div>
</div>
    
