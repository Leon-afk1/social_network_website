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
        if ($SQLconn->loginStatus->loginSuccessful){
            if ($SQLconn->profile->verifFollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"])){
                echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#unfollowModal'>Unfollow</button>";

                // modal pour unfollow
                echo            "<div class='modal fade' id='unfollowModal' tabindex='-1' aria-labelledby='unfollowModal' aria-hidden='true'>";
                echo                "<div class='modal-dialog modal-dialog-centered'>";
                echo                    "<div class='modal-content'>";
                echo                        "<div class='modal-header'>";
                echo                            "<h5 class='modal-title' id='unfollowModalLabel'>Voulez vous vraiment ne plus suivre ce compte?</h5>";
                echo                            "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                echo                        "</div>";
                echo                        "<div class='modal-body text-center'>";
                echo                            "<p>Êtes-vous sûr de vouloir unfollow ce compte?</p>";
                echo                        "</div>";
                echo                        "<div class='modal-footer'>";
                echo                            "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Annuler</button>";
                echo                            "<form action='./profile.php?id=".$InfosCompteExterne["id_utilisateur"]."' method='post'>";
                echo                                "<input type='hidden' name='unfollow' value='true'>";
                echo                                "<button type='submit' class='btn btn-danger'>Unfollow</button>";
                echo                            "</form>";
                echo                        "</div>";
                echo                    "</div>";
                echo                "</div>";
                echo            "</div>";
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
                    <label for="followers">Followers : <?php echo $SQLconn->profile->totalFollowers($InfosCompteExterne["id_utilisateur"]) ?></label>
                </div>
                <div class="col">
                    <label for="following">Following : <?php echo $SQLconn->profile->totalFollowing($InfosCompteExterne["id_utilisateur"]) ?></label>
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
            <div id="posts">
                <?php
                    $allPosts =  $SQLconn->profile->GetNextPosts($InfosCompteExterne["id_utilisateur"],0,5);
                    foreach ($allPosts as $post){
                        $SQLconn->profile->afficherPosts($post,$InfosCompteExterne);
                    }
                ?>
            </div>
        </div>
    </div>
</div>
