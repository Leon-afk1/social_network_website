<?php
include ("loc.php");

$newAccountStatus = register();

if ($newAccountStatus["Successful"]){
  if (isset($_COOKIE['user_id'])){
    header("Location:./profile.php");
    exit();
  }else{
    header("Location:./login.php");
    exit();
  }
}

include ("BoutDePages/header.php");

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Créer un compte</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">

  </head>
  <body class="text-bg-dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <main>
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                  <?php include ("BoutDePages/newlogin.php"); ?>
                </div>
            </div>
        </div>
        <br>
    </main>
    <script src="script.js"></script>
    <?php include ("BoutDePages/footer.php"); ?>
  </body>
</html>