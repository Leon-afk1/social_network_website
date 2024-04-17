<?php
include ("loc.php");
session_start();

include ("BoutDePages/repondrePost.php");

$modifierProfile = false;
if (isset($_POST["modifierProfile"])) {
    $modifierProfile = true;
}

$protectedID = $SQLconn->SecurizeString_ForSQL($_GET["id"]);

$monCompte = false;
if (isset($_COOKIE['user_id']) && $_COOKIE['user_id'] == $protectedID){
    $monCompte = true;
}
$InfosCompteExterne = $SQLconn->profile->GetInfoProfile($protectedID);

if (isset($_POST["submitModification"]) && isset($_FILES["avatar"]) && !empty($_FILES["avatar"]["name"])) {
    $resultAvatar=$SQLconn->profile->UpdateAvatar($_COOKIE['user_id']);
    $result=$SQLconn->profile->UpdateInfosProfile($_COOKIE['user_id']);
    if ($resultAvatar["Successful"] && $result["Successful"]){
        $Infos = $SQLconn->profile->GetInfoProfile($_COOKIE['user_id']);
        $modifierProfile = false;
    }
}else if (isset($_POST["submitModification"])) {
    $result=$SQLconn->profile->UpdateInfosProfile($_COOKIE['user_id']);
    if ($result["Successful"]){
        $modifierProfile = false;
    }
}

$modifierMotDePasse = false;
if (isset($_POST["modifierMotDePasse"])) {
    $modifierMotDePasse = true;
}

if (isset($_POST["submitModificationMdp"])) {
    $result=$SQLconn->profile->changermdp($_COOKIE['user_id']);
    if ($result["Successful"]){
        $modifierMotDePasse = false;
    }
}

$statistiques = false;
if (isset($_POST["statistiques"])) {
    $statistiques = true;
}

$following = false;
if (isset($_POST["following"])) {
    $following = true;
}

$follower = false;
if (isset($_POST["follower"])) {
    $follower = true;
}

if (!$SQLconn->loginStatus->loginSuccessful) {
    if ($monCompte){
        echo "non connecté";
        header("Location:".$rootpath."/index.php");
        exit();
    }
}

$Infos = $SQLconn->profile->GetInfoProfile($protectedID);

if (isset($_POST["unfollow"])) {
    $SQLconn->profile->unfollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
    $SQLconn->notification->notifyUnfollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
    $SQLconn->notification->verifNotificationFollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
}

if (isset($_POST["follow"])) {
    $SQLconn->profile->follow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
    $SQLconn->notification->notifyFollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
    $SQLconn->notification->verifNotificationUnfollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
}

include ("BoutDePages/header.php");

if ($monCompte){
    $_SESSION["Infos"]=$Infos;
}else{
    $_SESSION["Infos"]=$InfosCompteExterne;
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Profil</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">

  </head>
  <body class="text-body bg-body" data-bs-theme="dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <main id="mainContent">
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-8">
                  <?php 
                    if (!$monCompte){
                        include ("BoutDePages/voirProfile.php");
                    }else if ($modifierProfile){
                        include ("BoutDePages/modifierProfile.php");
                    }else if ($modifierMotDePasse){
                        include ("BoutDePages/modifiermdp.php");
                    }else if ($following){
                        include ("BoutDePages/following.php");
                    }else if ($follower){
                        include ("BoutDePages/follower.php");
                    }else if ($statistiques){
                        include ("BoutDePages/statistique.php");
                    }else{
                        include ("BoutDePages/monProfile.php");
                    }
                  ?>
                </div>
            </div>
        </div>
        <br>

    </main>
    <!-- <?php include ("BoutDePages/footer.php"); ?> -->
  </body>
</html>
<script src="JS/profile.js"></script>

