<!-- Inclusion de la feuille de style -->
<link rel="stylesheet" href="styles.css">

<!-- Carte pour afficher les informations de profil -->
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
        <?php echo "".$Infos["username"]."  ";
        
        // Affichage de l'icône d'administrateur si l'utilisateur est un administrateur
        if ($Infos['admin'] == 1){
            echo "<img src='./images/admin.jpg' class='avatar avatar-xs'>";
        }
        
        // Vérification si l'utilisateur est banni et affichage de l'icône de bannissement si nécessaire
        $ban = $SQLconn->profile->checkBan($_COOKIE['user_id']);
        if ($ban){
            echo "<img src='./images/ban.png' class='avatar avatar-xs'>";
        }
        ?>
        <br>
        <br>
        <?php
        
        // Affichage du message de bannissement si l'utilisateur est banni
        if ($ban){
            $dateBan = $SQLconn->profile->getDateBan($_COOKIE['user_id']);
            if ($dateBan){
                echo "<div class='alert alert-danger' role='alert'>";
                echo "Vous êtes banni jusqu'au ".$dateBan;
                echo "</div>";
            }else{
                echo "<div class='alert alert-danger' role='alert'>";
                echo "Vous êtes banni définitivement";
                echo "</div>";
            }
        }
        ?>
        <br>
        <!-- Affichage du nombre de followers et du bouton "Follower" -->
        <div class="container text-center align-items-center">
            <div class="row align-items-center ">
                <div class="col">
                    <div class="card outline-secondary rounded-3">
                        <div class="text-center card-header">
                            <?php echo $SQLconn->profile->totalFollowers($_COOKIE['user_id']) ?>
                        </div>
                        <div class="card-body">
                            <form action="./profile.php?id=<?php echo $_COOKIE['user_id']?>" method="post">
                                <input type="hidden" name="follower" value="true">
                                <button type="submit" class="btn btn-primary">Follower</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Affichage du nombre de following et du bouton "Following" -->
                <div class="col">
                    <div class="card outline-secondary rounded-3">
                        <div class="text-center card-header">
                            <?php echo $SQLconn->profile->totalFollowing($InfosCompteExterne["id_utilisateur"]) ?>  
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
        <!-- Affichage des informations de profil -->
        <div class="form-group form-field">
            <label for="nom"><?php echo $Infos["nom"]." ".$Infos["prenom"] ?></label>
        </div>
        <div class="form-group form-field">
            <!-- Calcul de l'âge de l'utilisateur -->
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
            <!-- Affichage de la description de l'utilisateur -->
            <label for="description"><?php echo $Infos["description"] ?></label>
            <br>
            <br>
        </div>
        <div class="form-group text-center">
            <!-- Boutons de modification du profil et du mot de passe -->
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
        <!-- Affichage des posts de l'utilisateur -->
        <div class="form-group form-field">
            <div id="posts">
                <?php
                    $allPosts =  $SQLconn->profile->GetNextPosts($_COOKIE['user_id'],0,5);
                    foreach ($allPosts as $post){
                        $SQLconn->profile->afficherPosts($post,$Infos);
                    }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Inclusion de jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Inclusion du script JavaScript pour la gestion des interactions sur le profil -->
<script src="JS/monProfile.js"></script>
