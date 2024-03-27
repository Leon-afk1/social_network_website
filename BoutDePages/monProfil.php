<div class="card text-bg-dark border-secondary">
    <div class="text-center card-header">
        <h1 class="card-title"><?php echo $Infos["username"];
        if ($Infos["avatar"] != NULL){
            echo "<img src='".$Infos["avatar"]."' class='img-fluid rounded-circle w-25 p-3'>";
        }
         ?></h1>
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
        </div>
        <div class="form-group text-center">
            <form action="./profile.php" method="post">
                <input type="hidden" name="modifierProfile" value="true">
                <button type="submit" class="btn btn-outline-secondary">Modifier profil</button>
            </form>
            <form action="./profile.php" method="post">
                <input type="hidden" name="modifierMotDePasse" value="true">
                <button type="submit" class="btn btn-outline-secondary">Changer de mot de passe</button>
            </form>
        </div>
        <div class="form-group form-field">
            <label for="post">Posts :</label>
            <br>
            <?php
                $posts = GetPosts($_COOKIE['user_id']);
                foreach ($posts as $post){
                    echo "<div class='card text-bg-dark border-secondary'>";
                    echo "<div class='card-header'>";
                    echo "</div>";
                    echo "<div class='card-body'>";
                    echo "<img src='".$post["image"]."' class='img-fluid'>";
                    echo "<p class='card-text'>".$post["contenu"]."</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "<br>";
                }
            ?>
        </div>
    </div>
</div>