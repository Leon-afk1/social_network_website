<?php
include ("loc.php");

$ajouterPost = false;
if (isset($_POST["submitReponse"])) {
    $ajouterPost = true;
    $result=$SQLconn->profile->ajouterNewPost($_COOKIE['user_id']);
    if ($result["Successful"]){
        $ajouterPost = false;
        $SQLconn->notification->notifyPost($_COOKIE['user_id'], $result["idPost"]);
        header("Location:./index.php");
        exit();
    }else{
        $erreurPost = $result["ErrorMessage"];
    }
}


if (!$SQLconn->loginStatus->loginSuccessful) {
    echo "non connecté";
    header("Location:./index.php");
    exit();
}

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
    <title>Poster</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  </head>
  <body class="text-body bg-body" data-bs-theme="dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <main id="mainContent">
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-6">
                  <?php 
                    include ("BoutDePages/ajouterPost.php");
                  ?>
                </div>
            </div>
        </div>
        <br>
    </main>
    <!-- <?php include ("BoutDePages/footer.php"); ?> -->
  </body>
</html>