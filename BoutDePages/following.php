<?php
$following = getFollowed($_COOKIE['user_id']);

$_SESSION['user_id'] = $_COOKIE['user_id'];

?>

<script>
    async function unfollowUser(id) {
        var unfollow = await fetch('./BoutDePages/unfollow.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'idToUnfollow=' + id,
        });
        var response = await unfollow.text();
        if (response === "success") {
            var user = document.getElementById(id);
            user.remove();
        } else {
            alert(response);
        }
        return response;
    }
</script>

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
        foreach ($following as $follow) {
            echo "<div class='card outline-secondary rounded-3 item-center' id='" . $follow["id_utilisateur"] . "'>";
            echo "<div class='row'>";
            echo "<div class='col text-start'>";
            echo "<a class='nav-link active' aria-current='page' href='./profile.php?id=" . $follow["id_utilisateur"] . "'> 
                            <img src='" . $follow["avatar"] . "' class='avatar avatar-lg'>
                            <label for='nom'>" . $follow["username"] . "</label>
                           </a>";
            echo "</div>";
            echo "<div class='col text-center'>";
            echo $follow["description"];
            echo "</div>";
            echo "<div class='col text-end'>";
            echo "<button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#unfollowModal" . $follow["id_utilisateur"] . "'>Unfollow</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<br>";
            // modal pour unfollow
            echo "<div class='modal fade' id='unfollowModal" . $follow["id_utilisateur"] . "' tabindex='-1' aria-labelledby='unfollowModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog modal-dialog-centered'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='unfollowModalLabel'>Voulez-vous vraiment ne plus suivre ce compte?</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Annuler</button>";
            echo "<button type='button' class='btn btn-danger' data-bs-dismiss='modal' onclick='unfollowUser(" . $follow["id_utilisateur"] . ")'>Unfollow</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>
</div>
