<?php

include ("loc.php");

if (isset($_COOKIE['user_id'])){
    $ban = $SQLconn->profile->checkBan($_COOKIE['user_id']);
    if ($ban){
        echo "Vous êtes banni définitivement";
        header("Location:profile.php?id=". $_COOKIE['user_id']);
      }
}


include ("BoutDePages/repondrePost.php");

include ("BoutDePages/header.php");


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Y - Accueil</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  </head>
  <body class="text-body bg-body" data-bs-theme="dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
    <main id="mainContent">
        <div class="px-3">
          <div class="container">
            <?php 
              if ($SQLconn->loginStatus->loginSuccessful){
                ?>
              <div class="row align-items-start">
                  <div class="col">
                    <div class='card rounded-3 outline-secondary text-bg-secondary ps-10 pe-10'>
                      <h2 class="card-title text-center">Meilleurs posts du moment :</h2>
                      <br>
                      <?php
                          $allPosts = $SQLconn->profile->getBestPosts($SQLconn->loginStatus->userID);
                          foreach ($allPosts as $post){
                              $infos = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]);
                              $SQLconn->profile->afficherPosts($post,$infos);
                          }
                      ?>
                    </div>
                  </div>
                  <div class="col">
                    <div class='card rounded-3 outline-secondary text-bg-secondary ps-10 pe-10'>
                      <h2 class="card-title text-center">Post recent que vous suivez :</h2>
                      <br>
                      <?php
                          $allPosts = $SQLconn->profile->getRecentPostsFollowed($SQLconn->loginStatus->userID);
                          foreach ($allPosts as $post){
                              $infos = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]);
                              $SQLconn->profile->afficherPosts($post,$infos);
                          }
                          if (count($allPosts) == 0){
                              echo "Vous ne suivez personne";
                          }
                       ?>
                    </div>
                  </div>
              </div>
              <?php
              }else{
                ?>
                <h2 class="card-title">Meilleurs posts du moment :</h2>
                <br>
                <?php
                    $allPosts = $SQLconn->profile->getBestPosts(0);
                    foreach ($allPosts as $post){
                        $infos = $SQLconn->profile->GetInfoProfile($post["id_utilisateur"]);
                        $SQLconn->profile->afficherPosts($post,$infos);
                    }
                ?>
                <?php
              }
            ?>
          </div>
        </div>
        <br>
    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="JS/monProfile.js"></script>

    <!-- <?php include ("BoutDePages/footer.php"); ?> -->
  </body>
</html>