<?php
include ("loc.php");

include ("BoutDePages/header.php");

$idPost = $_GET["id"];

$post = GetPostById($idPost);
$Infos = GetInfos($post["id_utilisateur"]);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">

  </head>
  <body class="text-body bg-body" data-bs-theme="dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <main id="mainContent">
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-8">
                  <?php afficherPosts($post,$Infos);
                    $reponses = getReponsesCommentaire($post['id']);
                    if (!empty($reponses)) {
                        echo "<div class='reponses-container'>";
                        foreach ($reponses as $reponse) {
                            $query = "SELECT * FROM utilisateur WHERE id_utilisateur = ".$reponse['id_utilisateur'];
                            $result = executeRequete($query);
                            $row = $result->fetch_assoc();
                            echo "<div class='card outline-secondary'>";
                            echo "<div class='card-header outline-secondary'>";
                            echo "<a class='nav-link active' aria-current='page' href='./profile.php?id=".$row["id_utilisateur"]."'> 
                                    <img src='".$row["avatar"]."' class='avatar avatar-lg'>
                                    <label for='nom'>". $row["nom"]." ".$row["prenom"]."</label>
                                    </a>";
                            echo "</div>";
                            echo "<div class='card-body'>";
                            if (!empty($reponse['image'])) {
                                echo "<img src='{$reponse['image']}' class='img-fluid'>";
                            }
                            echo "<p class='card-text'>".$reponse["contenu"]."</p>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo "</div>";
                        echo "<button onclick='chargerPlusReponses($idPost)'>Charger plus</button>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <br>

    </main>
    <?php include ("BoutDePages/footer.php"); ?>
  </body>
</html>