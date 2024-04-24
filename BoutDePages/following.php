<?php
// Récupération des utilisateurs suivis par l'utilisateur actuel
$following = $SQLconn->profile->getFollowed($InfosCompteExterne["id_utilisateur"]);
?>

<link rel="stylesheet" href="styles.css">

<div class="card outline-secondary rounded-3">
    <div class="text-center card-header">
        <!-- Affichage de l'avatar de l'utilisateur -->
        <h1 class="card-title"><?php
            if ($Infos["avatar"] != NULL) {
                echo "<img src='" . $Infos["avatar"] . "' class='avatar avatar-xxl'>";
            }
            ?>
        </h1>
        <!-- Affichage du nom d'utilisateur -->
        <?php echo $Infos["username"] ?>

        <br>
    </div>
    <div class="card-body">
        <?php
        // Boucle pour chaque utilisateur suivi
        foreach ($following as $follow) {
            // Affichage des informations de chaque utilisateur suivi
            echo "<div class='card outline-secondary rounded-3 item-center' id='" . $follow["id_utilisateur"] . "'>";
            echo "<div class='row'>";
            echo "<div class='col text-start'>";
            // Lien vers le profil de l'utilisateur suivi avec son avatar et son nom
            echo "<a class='nav-link active' aria-current='page' href='./profile.php?id=" . $follow["id_utilisateur"] . "'> 
                            <img src='" . $follow["avatar"] . "' class='avatar avatar-lg'>
                            <label for='nom'>" . $follow["username"] . "</label>
                           </a>";
            echo "</div>";
            echo "<div class='col text-center'>";
            // Affichage de la description de l'utilisateur suivi
            echo $follow["description"];
            echo "</div>";
            echo "<div class='col text-end'>";
            // Bouton pour cesser de suivre l'utilisateur avec modal de confirmation
            echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#unfollowModal" . $follow["id_utilisateur"] . "'>Unfollow</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<br>";
            // Modal de confirmation pour cesser de suivre l'utilisateur
            echo "<div class='modal fade' id='unfollowModal" . $follow["id_utilisateur"] . "' tabindex='-1' aria-labelledby='unfollowModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog modal-dialog-centered'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='unfollowModalLabel'>Voulez-vous vraiment ne plus suivre ce compte?</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Annuler</button>";
            // Bouton de désabonnement avec appel à la fonction JavaScript pour cesser de suivre l'utilisateur
            echo "<button type='button' class='btn btn-danger' data-bs-dismiss='modal' onclick='unfollowUser(" . $follow["id_utilisateur"] . ")'>Unfollow</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>


<script src="./JS/follower_following.js"></script>

