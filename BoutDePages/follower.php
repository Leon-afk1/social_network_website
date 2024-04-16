<?php
$followers = $SQLconn->profile->GetFollowers($InfosCompteExterne["id_utilisateur"]);


?>


<link rel="stylesheet" href="styles.css">

<div class="card outline-secondary rounded-3">
    <div class="text-center card-header">
        <h1 class="card-title"><?php
            if ($Infos["avatar"] != NULL) {
                echo "<img src='" . $Infos["avatar"] . "' class='avatar avatar-xxl'>";
            }
            ?>
        </h1>
        <?php echo $Infos["username"] ?>

        <br>
    </div>
    <div class="card-body">
        <?php
        foreach ($followers as $follower) {
            echo "<div class='card outline-secondary rounded-3 item-center' id='" . $follower["id_utilisateur"] . "'>";
            echo "<div class='row'>";
            echo "<div class='col text-start'>";
            echo "<a class='nav-link active' aria-current='page' href='./profile.php?id=" . $follower["id_utilisateur"] . "'> 
                            <img src='" . $follower["avatar"] . "' class='avatar avatar-lg'>
                            <label for='nom'>" . $follower["username"] . "</label>
                           </a>";
            echo "</div>";
            echo "<div class='col text-center'>";
            echo $follower["description"];
            echo "</div>";
            echo "<div class='col text-end'>";
            echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#supprimerFollowModal" . $follower["id_utilisateur"] . "'>Supprimer</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<br>";
            // modal pour unfollow
            echo "<div class='modal fade' id='supprimerFollowModal" . $follower["id_utilisateur"] . "' tabindex='-1' aria-labelledby='supprimerFollowModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog modal-dialog-centered'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='supprimerFollowModalLabel'>Voulez-vous vraiment que ce compte ne vous suive plus?</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Annuler</button>";
            echo "<button type='button' class='btn btn-danger' data-bs-dismiss='modal' onclick='supprimerFollowUser(" . $follower["id_utilisateur"] . ")'>Supprimer</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>

<script src="./JS/follower_following.js"></script>
