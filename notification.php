<?php
include ("loc.php");

$AccountStatus = CheckLogin();

session_start();

$_SESSION['user_id'] = $_COOKIE['user_id'];

if (!isset($_COOKIE['user_id'])) {
  header("Location: index.php"); 
  exit();
}

$userId = $_COOKIE['user_id'];

$query = "SELECT notification.id_notification, notification.type, notification.id_utilisateur, notification.date_notification, notification.bool_lue, 
          notification.id_utilisateur_cible, notification.id_post_cible, utilisateur.username, utilisateur.avatar 
          FROM notification INNER JOIN utilisateur ON notification.id_utilisateur_cible = utilisateur.id_utilisateur
          WHERE notification.id_utilisateur = '$userId'";
$result = executeRequete($query);

include ("BoutDePages/header.php");

?>

<script>
  async function deleteNotification(id) {
    var ajax = await fetch("BoutDePages/deleteNotification.php?id=" + id);
    var response = await ajax.text();
    if (response === "success") {
      var notification = document.getElementById(id);
      notification.remove();
    } else {
      alert(response);
    }
  }

  async function deleteAllNotifications() {
    var ajax = await fetch("BoutDePages/deleteNotification.php?idUser="+<?php echo $userId ?>);
    var response = await ajax.text();
    if (response === "success") {
      var notifications = document.getElementsByClassName("notification");
      for (var i = 0; i < notifications.length; i++) {
        notifications[i].remove();
      }
    } else {
      alert(response);
    }
  }

</script>


<!DOCTYPE html>
<html>
  <head>
    <title>Y - Notifications</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
  </head>
  <body class="text-body bg-body" data-bs-theme="dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
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
              <?php echo "<button type='button' class='btn btn-danger' onclick='deleteAllNotifications()'>Supprimer toutes les notifications</button>";?>
            </div>
            <div class="card-body">
              <div class="form-group form-field">
                <?php
                  while ($res = $result->fetch_assoc()) {
                    if ($res["bool_lue"] == 0) {
                      echo "<div class='notification card outline-secondary rounded-3 item-center text-bg-secondary' id='" . $res["id_notification"] . "'>";
                    } else {
                      echo "<div class='notification card outline-primary rounded-3 item-center ' id='" . $res["id_notification"] . "'>";
                    }
                    echo "<div class='row'>";
                    echo "<div class='col text-start'>";
                    echo "<a class='nav-link active' aria-current='page' href='./profile.php?id=" . $res["id_utilisateur_cible"] . "'>
                                    <img src='" . $res["avatar"] . "' class='avatar avatar-lg'>
                                    <label for='nom'>" . $res["username"] . "</label>
                                    </a>";
                    echo "</div>";
                    echo "<div class='col text-center'>";
                    if ($res["type"] == "follow") {
                      echo "Vient de vous follow";
                    } else if ($res["type"] == "unfollow") {
                      echo "Vous a unfollow";
                    } else if ($res["type"] == "post") {
                      echo "A posté le post suivant : <a href='./post.php?id=" . $res["id_post_cible"] . "'>Voir le post</a>";
                    }
                    echo "</div>";
                    echo "<div class='col text-end'>";
                    echo "<button type='button' class='btn btn-danger' onclick='deleteNotification(" . $res["id_notification"] . ")'>Supprimer</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<br>";
                  }
                  $query = "UPDATE notification SET bool_lue = 1 WHERE id_utilisateur = '$userId'";
                  $result2 = executeRequete($query);
                ?> 
              </div>
            </div> 
          </div>
        </div>
      </div>
    </main>
    <?php include ("BoutDePages/footer.php"); ?>
  </body>
</html>