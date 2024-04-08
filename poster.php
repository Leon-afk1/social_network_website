<?php
include ("loc.php");


$ajouterPost = false;
if (isset($_POST["submitPost"])) {
    $ajouterPost = true;
    $result=ajouterNewPost($_COOKIE['user_id']);
    if ($result["Successful"]){
        $ajouterPost = false;
        header("Location:./index.php");
        exit();
    }else{
        echo 
        "<div class='alert alert-danger' role='alert'>
            ".$result["ErrorMessage"]."
            </div>";
    }
}


$AccountStatus = CheckLogin();
if (!$AccountStatus["loginSuccessful"]){
    echo "non connecté";
    header("Location:./index.php");
    exit();
}

include ("BoutDePages/header.php");

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Poster</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">

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
    <?php include ("BoutDePages/footer.php"); ?>
  </body>
</html>