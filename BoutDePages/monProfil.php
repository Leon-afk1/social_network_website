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
        <br>
        <div class="container text-center align-items-center">
            <div class="row align-items-center ">
                <div class="col">
                    <div class="card outline-secondary rounded-3">
                        <div class="text-center card-header">
                            <?php echo totalFollowers($InfosCompteExterne["id_utilisateur"]) ?>
                        </div>
                        <div class="card-body">
                            <form action="./profile.php?id=<?php echo $_COOKIE['user_id']?>" method="post">
                                <input type="hidden" name="follower" value="true">
                                <button type="submit" class="btn btn-primary">Follower</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card outline-secondary rounded-3">
                        <div class="text-center card-header">
                            <?php echo totalFollowing($InfosCompteExterne["id_utilisateur"]) ?>  
                        </div>
                        <div class="card-body">
                            <form action="./profile.php?id=<?php echo $_COOKIE['user_id']?>" method="post">
                                <input type="hidden" name="following" value="true">
                                <button type="submit" class="btn btn-primary">Following</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="form-group form-field">
            <label for="nom"><?php echo $Infos["nom"]." ".$Infos["prenom"] ?></label>
            
        </div>
        <div class="form-group form-field">
            <label for="age">
                <?php 
                    $date_naissance = new DateTime($Infos["dateNaissance"]);
                    $date_actuelle = new DateTime();
                    $age = $date_naissance->diff($date_actuelle)->y;
                    echo $age." ans";
                ?>
        
            </label>
        </div>
        <div class="form-group form-field">
            <label for="description"><?php echo $Infos["description"] ?></label>
            <br>
            <br>
        </div>
        <div class="form-group text-center">
            <div class="row align-items-center">
                <div class="col">
                    <form action="./profile.php?id=<?php echo $_COOKIE['user_id']?>" method="post">
                        <input type="hidden" name="modifierProfile" value="true">
                        <button type="submit" class="btn btn-outline-secondary ">Modifier profil</button>
                    </form>
                </div>
                <div class="col">
                    <form action="./profile.php?id=<?php echo $_COOKIE['user_id']?>" method="post">
                        <input type="hidden" name="modifierMotDePasse" value="true">
                        <button type="submit" class="btn btn-outline-secondary">Modifier mot de passe</button>
                    </form>
                </div>
            </div>
        </div>
        <br>

        <div class="form-group form-field">
            <div id="posts">
                <?php
                    $allPosts =  GetNextPosts($_COOKIE['user_id'],0,5);
                    foreach ($allPosts as $post){
                        afficherPosts($post,$Infos);
                    }
                ?>
            </div>
        </div>
    </div>
</div>

