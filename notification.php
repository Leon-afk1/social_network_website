<?php
// Inclure les fichiers nécessaires
include("loc.php");

// Rediriger l'utilisateur non connecté vers la page d'accueil
if (!$SQLconn->loginStatus->loginSuccessful) {
    header("Location: index.php");
}

// Inclure l'en-tête commun à toutes les pages
include("BoutDePages/header.php");

// Récupérer l'ID de l'utilisateur depuis les cookies
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

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Y - Notifications</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="text-body bg-body" data-bs-theme="dark">

    <main id="mainContent">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-8">
                <div class="card outline-secondary rounded-3">
                    <div class="text-center card-header">
                        <h1 class="card-title">
                            <?php
                            // Afficher l'avatar de l'utilisateur s'il existe
                            if ($Infos["avatar"] != NULL) {
                                echo "<img src='" . $Infos["avatar"] . "' class='avatar avatar-xxl'>";
                            }
                            ?>
                        </h1>
                        <?php
                        // Bouton pour supprimer toutes les notifications s'il y en a
                        if (isset($notifications) && count($notifications) > 0) {
                            echo "<button type='button' class='btn btn-danger' onclick='deleteAllNotifications(" . $Infos['id_utilisateur'] . ")'>Supprimer toutes les notifications</button>";
                        
                        ?>
                    </div>
                    <div class="card-body">
                        <div class="form-group form-field">
                            <div id="allNotifications">
                                <?php
                                // Afficher les notifications
                                foreach ($notifications as $notification) {
                                    echo "<div class='notification' id='" . $notification["id_notification"] . "'>";
                                    // Ajouter une classe supplémentaire si la notification n'est pas lue
                                    if ($notification["bool_lue"] == 0) {
                                        echo "<div class='card outline-secondary rounded-3 item-center text-bg-secondary'>";
                                    } else {
                                        echo "<div class='card outline-secondary rounded-3 item-center '>";
                                    }
                                    echo "<div class='row align-items-center'>";
                                    echo "<div class='col text-start ms-4'>";
                                    echo "<a class='nav-link active' aria-current='page' href='./profile.php?id=" . $notification["id_utilisateur_cible"] . "'>
                                        <img src='" . $notification["avatar"] . "' class='avatar avatar-lg'>
                                        <label for='nom'>" . $notification["username"] . "</label>
                                        </a>";
                                    echo "</div>";
                                    echo "<div class='col text-center'>";
                                    // Afficher le type de notification et éventuellement des liens supplémentaires
                                    if ($notification["type"] == "follow") {
                                        echo "Vient de vous follow";
                                    } else if ($notification["type"] == "unfollow") {
                                        echo "Vous a unfollow";
                                    } else if ($notification["type"] == "post") {
                                        echo "A posté le post suivant : <a href='./post.php?id=" . $notification["id_post_cible"] . "'>Voir le post</a>";
                                    } else if ($notification["type"] == "ban") {
                                        // Afficher la raison du bannissement
                                        $ban = $SQLconn->profile->getJustificationBan($notification["id_utilisateur"]);
                                        if ($ban["date_fin_ban"] == NULL) {
                                            echo "Vous avez été banni définitivement pour la raison suivante : ";
                                        } else {
                                            echo "Vous banni jusqu'au : " . $ban["date_fin_ban"] . " pour la raison suivante : ";
                                        }
                                        echo $ban["justification_ban"];
                                    } else if ($notification["type"] == "unban") {
                                        echo "Vous avez été débanni";
                                    } else if ($notification["type"] == "avertissement") {
                                        echo $notification["message_notification"];
                                        echo "<br>";
                                        echo "Avertissement en lien avec le post suivant : <a href='./post.php?id=" . $notification["id_post_cible"] . "'>Voir le post</a>";
                                    } else if ($notification["type"] == "sensible") {
                                        echo $notification["message_notification"];
                                        echo "<br>";
                                        echo "Avertissement en lien avec le post suivant : <a href='./post.php?id=" . $notification["id_post_cible"] . "'>Voir le post</a>";
                                    } else if ($notification["type"] == "retirerPost") {
                                        echo $notification["message_notification"];
                                        echo "<br>";
                                        echo "Avertissement en lien avec le post suivant : <a href='./post.php?id=" . $notification["id_post_cible"] . "'>Voir le post</a>";
                                    } else if ($notification["type"] == "nonSensible") {
                                        echo ("Le post suivant a été jugé non sensible : <a href='./post.php?id=" . $notification["id_post_cible"] . "'>Voir le post</a>");
                                    } else if ($notification["type"] == "remettrePost") {
                                        echo ("Le post suivant a été remis en ligne : <a href='./post.php?id=" . $notification["id_post_cible"] . "'>Voir le post</a>");
                                    }else if ($notification["type"] == "signalement") {
                                        echo ("Le post suivant a été signalé : <a href='./post.php?id=" . $notification["id_post_cible"] . "'>Voir le post</a>");
                                    }
                                    echo "</div>";
                                    echo "<div class='col text-center'>";
                                    // Bouton pour supprimer la notification
                                    echo "<button type='button' class='btn btn-danger' onclick='deleteNotification(" . $notification["id_notification"] . ")'>Supprimer</button>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "<br>";
                                    echo "</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div class="text-center">
                        <h2 class="card-body">Aucune notification</h2>
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Inclure le script JS pour la gestion des notifications -->
    <script src="JS/notificationHandler.js"></script>
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
