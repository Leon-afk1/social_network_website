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


if ($monCompte){
    $_SESSION["Infos"]=$Infos;
}else{
    $_SESSION["Infos"]=$InfosCompteExterne;
}

$bandefinitif = false;
if (isset($_POST["banDef"])) {
    $bandefinitif = true;
    $result=$SQLconn->profile->bandef($InfosCompteExterne["id_utilisateur"], $_POST["raison"]);
    if ($result){
        $SQLconn->notification->notifyBan($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
        $bandefinitif = false;
    }else{
        echo "Erreur lors du bannissement de l'utilisateur";
    }
}

$banTemporaire = false;
if (isset($_POST["banTemp"]) && isset($_POST["dateFin"])) {
    $banTemporaire = true;
    $result=$SQLconn->profile->banTemp($InfosCompteExterne["id_utilisateur"], $_POST["dateFin"], $_POST["raison"]);
    if ($result){
        $SQLconn->notification->notifyBan($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
        $banTemporaire = false;
    }else{
        echo "Erreur lors du bannissement de l'utilisateur";
    }
}

$unban = false;
if (isset($_POST["unban"])) {
    $unban = true;
    $result=$SQLconn->profile->unban($InfosCompteExterne["id_utilisateur"]);
    if ($result){
        $SQLconn->notification->notifyUnban($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]);
        $unban = false;
    }else{
        echo "Erreur lors du débannissement de l'utilisateur";
    }
}



$banDef = false;
if (isset($_COOKIE['user_id'])){
    $banDef =  $SQLconn->profile->checkBan($_COOKIE['user_id']);
    if ($banDef and !$monCompte){
        echo "Vous êtes banni définitivement";
        header("Location:./profile.php?id=". $_COOKIE['user_id']);
    }
}

include ("BoutDePages/header.php");


?>

<!DOCTYPE html>
<html>
  <head>
    <title>Profil</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  </head>
  <body class="text-body bg-body" data-bs-theme="dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <main id="mainContent">
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-8">
                  <?php 
                    if (!$monCompte && $banDef){
                        include ("BoutDePages/monProfile.php");
                    }else if (!$monCompte && !$banDef){
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

