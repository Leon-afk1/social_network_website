<link rel="stylesheet" href="styles.css">
<div class="card outline-secondary">
    <div class="text-center card-header">
        <h1 class="card-title"><?php 
        if ($InfosCompteExterne["avatar"] != NULL){
            echo "<img src='".$InfosCompteExterne["avatar"]."' class='avatar avatar-xxl'>";
        }
         ?>
        </h1>
        <?php echo $InfosCompteExterne["username"] ;
        if ($InfosCompteExterne['admin'] == 1){
            echo "<img src='./images/admin.jpg' class='avatar avatar-xs'>";
        }
        ?>
        <br>
        <?php 
        $ban = $SQLconn->profile->checkBan($InfosCompteExterne["id_utilisateur"]);
        if ($ban){
            $dateBan = $SQLconn->profile->getDateBan($InfosCompteExterne["id_utilisateur"]);
            if ($dateBan){
                echo "<div class='alert alert-danger' role='alert'>";
                echo "Utilisateur banni jusqu'au ".$dateBan;
                echo "</div>";
            }else{
                echo "<div class='alert alert-danger' role='alert'>";
                echo "Utilisateur banni définitivement";
                echo "</div>";
            }
        }


        if ($SQLconn->loginStatus->loginSuccessful and $SQLconn->profile->checkAdmin($_COOKIE['user_id']) and !$monCompte){
            ?>
            <p class="d-inline-flex gap-1">
                <button class="btn btn-danger" type="button" data-bs-toggle="collapse" data-bs-target="#Ban" aria-expanded="false" aria-controls="collapseExample">
                    Menu admin
                </button>
            </p>
            <?php 
            if ($ban){
                ?>
                <div class="collapse" id="Ban">
                    <div class="card card-body">
                        <div class="row">
                            <div class="col">
                                <form action='./profile.php?id=<?php echo $InfosCompteExterne["id_utilisateur"] ?>' method='post'>
                                    <input type='hidden' name='unban' value='true'>
                                    <button type='submit' class='btn btn-danger'>Unban</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }else{?>
            <div class="collapse" id="Ban">
                <div class="card card-body">
                    <div class="row">
                        <div class="col">
                            <p class="d-inline-flex gap-1">
                                <button class="btn btn-danger" type="button" data-bs-toggle="collapse" data-bs-target="#banTemp" aria-expanded="false" aria-controls="collapseExample">
                                    Bannir temporairement
                                </button>
                            </p>
                            <div class="collapse" id="banTemp">
                                <div class="card card-body">
                                    <form action='./profile.php?id=<?php echo $InfosCompteExterne["id_utilisateur"] ?>' method='post'>
                                        <input type='datetime-local' name='dateFin' value='2022-01-01T00:00' required>
                                        <!-- <input type='text' name='raison' placeholder='Raison du ban' value='Contenue inapproprié'> -->
                                        <textarea name="raison" placeholder='Raison du ban' rows="3">Contenue inapproprié</textarea>

                                        <input type='hidden' name='banTemp' value='true'>
                                        <button type='submit' class='btn btn-danger'>Bannir temporairement</button>
                                    </form>
                                </div> 
                            </div>                    
                        </div>
                        <div class="col">
                            <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#BanDefModal'>Bannir définitivement</button>
                            <!-- modal pour bannir définitivement -->
                            <div class='modal fade' id='BanDefModal' tabindex='-1' aria-labelledby='BanDefModal' aria-hidden='true'>
                                <div class='modal-dialog modal-dialog-centered'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='BanDefModalLabel'>Voulez vous vraiment bannir définitivement ce compte?</h5>
                                        </div>
                                        <div class='modal-footer'>
                                            <div class="row">
                                                <div class="col">
                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Annuler</button>
                                                </div>
                                                <div class="col">
                                                    <form action='./profile.php?id=<?php echo $InfosCompteExterne["id_utilisateur"] ?>' method='post'>
                                                        <textarea name="raison" placeholder='Raison du ban' rows="3">Contenue inapproprié</textarea>

                                                        <input type='hidden' name='banDef' value='true'>
                                                        <button type='submit' class='btn btn-danger'>Bannir définitivement</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <?php
            }
        }

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
                echo    "<input type='hidden' name='follow' value='true'>";
                echo    "<button type='submit' class='btn btn-primary'>Follow</button>";
                echo "</form>";
            }
        }
        ?>
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col ">
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
<script src="JS/profile.js"></script>

