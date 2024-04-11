<?php
include ("loc.php");

include ("BoutDePages/repondrePost.php");

$AccountStatus = CheckLogin();

include ("BoutDePages/header.php");


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
        <div class="px-3">
          <div class="container">
            <?php 
              if ($AccountStatus["loginSuccessful"]){
                ?>
              <div class="row align-items-start">
                  <div class="col">
                    <div class='card rounded-3 outline-secondary text-bg-secondary ps-10 pe-10'>
                      <h2 class="card-title text-center">Meilleurs posts du moment :</h2>
                      <br>
                      <?php
                          $allPosts = afficherBestPosts($_COOKIE['user_id']);
                          foreach ($allPosts as $post){
                              $infos = GetInfos($post["id_utilisateur"]);
                              afficherPosts($post,$infos);
                          }
                      ?>
                    </div>
                  </div>
                  <div class="col">
                    <div class='card rounded-3 outline-secondary text-bg-secondary ps-10 pe-10'>
                      <h2 class="card-title text-center">Post recent que vous suivez :</h2>
                      <br>
                      <?php
                          $allPosts = afficherRecentPostsFollowed($_COOKIE['user_id']);
                          foreach ($allPosts as $post){
                              $infos = GetInfos($post["id_utilisateur"]);
                              afficherPosts($post,$infos);
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
                    $allPosts = afficherBestPosts(0);
                    foreach ($allPosts as $post){
                        $infos = GetInfos($post["id_utilisateur"]);
                        afficherPosts($post,$infos);
                    }
                ?>
                <?php
              }
            ?>
          </div>
        </div>
        <br>
    </main>
    <?php include ("BoutDePages/footer.php"); ?>
  </body>
</html>