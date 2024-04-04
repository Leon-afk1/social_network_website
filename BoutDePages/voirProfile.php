<link rel="stylesheet" href="styles.css">
<div class="card text-bg-dark border-secondary">
    <div class="text-center card-header">
        <h1 class="card-title"><?php 
        if ($InfosCompteExterne["avatar"] != NULL){
            echo "<img src='".$InfosCompteExterne["avatar"]."' class='avatar avatar-xxl'>";
        }
         ?>
        </h1>
        <?php echo $InfosCompteExterne["username"] ?>
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
                    echo "<div class='card text-bg-dark border-secondary'>";
                    echo "<div class='card-header border-secondary text-bg-dark'>";
                    echo "<img src='".$InfosCompteExterne["avatar"]."' class='avatar avatar-ml'>";
                    echo "<label for='nom'>". $InfosCompteExterne["nom"]." ".$InfosCompteExterne["prenom"]."</label>";
                    echo "</div>";
                    echo "<div class='card-body'>";
                    if (!empty($post['image'])) {
                        echo "<img src='{$post['image']}' class='img-fluid'>";
                    }
                    echo "<p class='card-text'>".$post["contenu"]."</p>";
                    echo "</div>";
                    echo "<p class='card-text'>".$post["date"]."</p>";
                    echo "</div>";
                    echo "<br>";
                }
            ?>
        </div>
    </div>
</div>