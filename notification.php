<?php
// Inclure les fichiers nécessaires
include("loc.php");

include("BoutDePages/header.php");

$user_id = $_COOKIE['user_id'];


// Supprimer une notification si l'ID est passé en paramètre
if (isset($_GET['delete_notification'])) {
    $notificationId = $_GET['delete_notification'];
    $SQLconn->notification->supprimerNotification($notificationId);
}

// Supprimer toutes les notifications
if (isset($_GET['delete_all_notifications'])) {
    $notificationHandler->supprimerAllNotifications($user_id);
}

// Récupérer les notifications pour l'utilisateur actuel
$notifications = $SQLconn->notification->getNotifications($user_id);

// Marquer les notifications comme lues
$SQLconn->notification->markNotificationsAsRead($user_id);
?>

<script src="JS/notificationHandler.js"></script>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Y - Notifications</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet">
</head>
<body class="text-body bg-body" data-bs-theme="dark">
<main id="mainContent">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-8">
            <div class="card outline-secondary rounded-3">
                <div class="text-center card-header">
                    <h1 class="card-title">
                        <?php
                        if ($Infos["avatar"] != NULL){
                            echo "<img src='".$Infos["avatar"]."' class='avatar avatar-xxl'>";
                        }
                        ?>
                    </h1>
                    <?php if (isset($notifications) && count($notifications) > 0) { ?>
                        <button type='button' class='btn btn-danger' onclick='deleteAllNotifications($Infos["id_utilisateur"])'>Supprimer toutes les notifications</button>
                </div>
                <div class="card-body">
                    <div class="form-group form-field">
                        <?php
                        // Afficher les notifications
                        foreach ($notifications as $notification) {
                            echo "<div class='notification card outline-secondary rounded-3 item-center' id='" . $notification["id_notification"] . "'>";
                            echo "<div class='row'>";
                            echo "<div class='col text-start'>";
                            echo "<a class='nav-link active' aria-current='page' href='./profile.php?id=" . $notification["id_utilisateur_cible"] . "'>
                                    <img src='" . $notification["avatar"] . "' class='avatar avatar-lg'>
                                    <label for='nom'>" . $notification["username"] . "</label>
                                    </a>";
                            echo "</div>";
                            echo "<div class='col text-center'>";
                            if ($notification["type"] == "follow") {
                                echo "Vient de vous follow";
                            } else if ($notification["type"] == "unfollow") {
                                echo "Vous a unfollow";
                            } else if ($notification["type"] == "post") {
                                echo "A posté le post suivant : <a href='./post.php?id=" . $notification["id_post_cible"] . "'>Voir le post</a>";
                            }
                            echo "</div>";
                            echo "<div class='col text-end'>";
                            echo "<button type='button' class='btn btn-danger' onclick='deleteNotification(" . $notification["id_notification"] . ")'>Supprimer</button>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "<br>";
                        }
                        ?>
                    </div>
                </div>
                <?php } else { ?>
                    <div class="text-center">
                        <h2 class="card-title">Aucune notification</h2>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>
<!-- <?php include("BoutDePages/footer.php"); ?> -->
</body>
</html>
