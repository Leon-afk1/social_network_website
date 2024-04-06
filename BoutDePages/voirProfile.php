<link rel="stylesheet" href="styles.css">
<div class="card outline-secondary">
    <div class="text-center card-header">
        <h1 class="card-title"><?php 
        if ($InfosCompteExterne["avatar"] != NULL){
            echo "<img src='".$InfosCompteExterne["avatar"]."' class='avatar avatar-xxl'>";
        }
         ?>
        </h1>
        <?php echo $InfosCompteExterne["username"] ?>
        <br>
        <?php 
        if ($AccountStatus["loginSuccessful"]){
            if (verifFollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"])){
                echo "<form action='./profile.php?id=".$InfosCompteExterne["id_utilisateur"]."' method='post'>";
                echo "<input type='hidden' name='unfollow' value='true'>";
                echo "<button type='submit' class='btn btn-primary'>Unfollow</button>";
                echo "</form>";
            }else{
                echo "<form action='./profile.php?id=".$InfosCompteExterne["id_utilisateur"]."' method='post'>";
                echo "<input type='hidden' name='follow' value='true'>";
                echo "<button type='submit' class='btn btn-primary'>Follow</button>";
                echo "</form>";
            }
        }
        ?>
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col">
                    <label for="followers">Followers : <?php echo totalFollowers($InfosCompteExterne["id_utilisateur"]) ?></label>
                </div>
                <div class="col">
                    <label for="following">Following : <?php echo totalFollowing($InfosCompteExterne["id_utilisateur"]) ?></label>
                </div>
            </div>
        </div>
        

    </div>
    <div class="card-body">

        <div class="form-group form-field">
            <label for="nom"><?php echo $InfosCompteExterne["nom"]." ".$InfosCompteExterne["prenom"] ?></label>
            
        </div>
        <div class="form-group form-field">
            <label for="description"><?php echo $InfosCompteExterne["description"] ?></label>
            <br>
        </div>
        <div class="form-group form-field">
            <label for="post">Posts :</label>
            <br>
            <?php
                $allPosts = GetAllPosts($InfosCompteExterne["id_utilisateur"]);
                foreach ($allPosts as $post){
                    afficherPosts($post,$InfosCompteExterne);
                }
            ?>
        </div>
    </div>
</div>