<?php
include ("loc.php");

$modifierProfile = false;
if (isset($_POST["modifierProfile"])) {
    $modifierProfile = true;
}


if (isset($_POST["submitModification"]) && isset($_FILES["avatar"]) && !empty($_FILES["avatar"]["name"])) {
    $resultAvatar=UpdateAvatar($_COOKIE['user_id']);
    $result=UpdateInfosProfile($_COOKIE['user_id']);
    if ($resultAvatar["Successful"] && $result["Successful"]){
        $modifierProfile = false;
    }
}else if (isset($_POST["submitModification"])) {
    $result=UpdateInfosProfile($_COOKIE['user_id']);
    if ($result["Successful"]){
        $modifierProfile = false;
    }
}

$modifierMotDePasse = false;
if (isset($_POST["modifierMotDePasse"])) {
    $modifierMotDePasse = true;
}

if (isset($_POST["submitModificationMdp"])) {
    $result=changermdp($_COOKIE['user_id']);
    if ($result["Successful"]){
        $modifierMotDePasse = false;
    }
}

$AccountStatus = CheckLogin();

if (!$AccountStatus["loginSuccessful"]){
    echo "non connecté";
	header("Location:http://".__ROOT__."/index.php");
    exit();
}

$Infos = GetInfoProfile($_COOKIE['user_id']);

include ("BoutDePages/header.php");

?>

<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  </head>
  <body class="text-bg-dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <main>
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                  <?php 
                    if ($modifierProfile){
                        include ("BoutDePages/modifierProfile.php");
                    }else if ($modifierMotDePasse){
                        include ("BoutDePages/modifiermdp.php");
                    }else{
                        include ("BoutDePages/monProfil.php");
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