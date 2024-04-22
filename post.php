<?php

include ("loc.php");
include ("BoutDePages/repondrePost.php");


$idPost = $_GET["id"];

$post = $SQLconn->profile->GetPostById($idPost);
$Infos = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]);

if (isset($_COOKIE['user_id'])){
  $ban = $SQLconn->profile->checkBan($_COOKIE['user_id']);
  if ($ban){
      echo "Vous êtes banni définitivement";
      header("Location:profile.php?id=". $_COOKIE['user_id']);
    }
}

include ("BoutDePages/header.php");

?>

<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  </head>
  <body class="text-body bg-body" data-bs-theme="dark">
    <main id="mainContent">
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-8">
                  <div class='reponses-container'  id="reponses-container">
                    <?php
                      $SQLconn->profile->afficherPosts($post,$Infos);
                      $allPosts =  $SQLconn->profile->GetNextReponse($idPost, 0, 5);
                      foreach ($allPosts as $post){
                          $InfosUser = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]);
                          $SQLconn->profile->afficherPosts($post,$InfosUser);
                      }
                    ?>
                  </div>
              </div>
          </div>
      </div>
      <br>
    </main>
    <script src="JS/monProfile.js"></script>
    <script src="JS/post.js"></script>

    <script>
      window.addEventListener('scroll', function() {
          if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
              loadMorePosts(<?php echo $idPost; ?>);
          }
      });
    </script>

    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <!-- <?php include ("BoutDePages/footer.php"); ?> -->
  </body>
</html>