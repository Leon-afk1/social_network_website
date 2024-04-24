<?php
// Inclure les fichiers nécessaires
include ("loc.php"); 
session_start(); // Démarrer la session

include ("BoutDePages/repondrePost.php"); // Inclure le fichier pour répondre aux posts

// Initialiser une variable pour vérifier si le profil doit être modifié
$modifierProfile = false;
if (isset($_POST["modifierProfile"])) {
    $modifierProfile = true;
}

// Sécuriser l'ID passé dans l'URL
$protectedID = $SQLconn->SecurizeString_ForSQL($_GET["id"]);

// Vérifier si l'utilisateur est connecté et s'il s'agit de son propre compte
$monCompte = false;
if (isset($_COOKIE['user_id']) && $_COOKIE['user_id'] == $protectedID){
    $monCompte = true;
}

// Obtenir les informations du compte externe à partir de l'ID protégé
$InfosCompteExterne = $SQLconn->profile->GetInfoProfile($protectedID);

// Vérifier si le formulaire de modification de profil a été soumis avec une image
if (isset($_POST["submitModification"]) && isset($_FILES["avatar"]) && !empty($_FILES["avatar"]["name"])) {
    // Mettre à jour l'avatar et les informations du profil
    $resultAvatar = $SQLconn->profile->UpdateAvatar($_COOKIE['user_id']);
    $result = $SQLconn->profile->UpdateInfosProfile($_COOKIE['user_id']);
    // Vérifier si les mises à jour ont été réussies
    if ($resultAvatar["Successful"] && $result["Successful"]){
        // Obtenir les nouvelles informations du profil
        $Infos = $SQLconn->profile->GetInfoProfile($_COOKIE['user_id']);
        $modifierProfile = false; // Désactiver la variable de modification de profil
    }
} else if (isset($_POST["submitModification"])) {
    // Mettre à jour uniquement les informations du profil sans changer l'avatar
    $result = $SQLconn->profile->UpdateInfosProfile($_COOKIE['user_id']);
    // Vérifier si la mise à jour a été réussie
    if ($result["Successful"]){
        $modifierProfile = false; // Désactiver la variable de modification de profil
    }
}

// Vérifier si le formulaire de modification du mot de passe a été soumis
$modifierMotDePasse = false;
if (isset($_POST["modifierMotDePasse"])) {
    $modifierMotDePasse = true; // Activer la variable de modification du mot de passe
}

// Vérifier si le formulaire de soumission de la modification du mot de passe a été soumis
if (isset($_POST["submitModificationMdp"])) {
    // Appeler la fonction pour changer le mot de passe
    $result = $SQLconn->profile->changermdp($_COOKIE['user_id']);
    // Vérifier si la modification a été réussie
    if ($result["Successful"]){
        $modifierMotDePasse = false; // Désactiver la variable de modification du mot de passe
    }
}

// Vérifier si l'utilisateur veut afficher ses statistiques
$statistiques = false;
if (isset($_POST["statistiques"])) {
    $statistiques = true; // Activer la variable pour afficher les statistiques
}

// Vérifier si l'utilisateur veut afficher les utilisateurs qu'il suit
$following = false;
if (isset($_POST["following"])) {
    $following = true; // Activer la variable pour afficher les utilisateurs suivis
}

// Vérifier si l'utilisateur veut afficher ses followers
$follower = false;
if (isset($_POST["follower"])) {
    $follower = true; // Activer la variable pour afficher les followers
}

// Vérifier si l'utilisateur n'est pas connecté
if (!$SQLconn->loginStatus->loginSuccessful) {
    // Vérifier s'il s'agit de son propre compte
    if ($monCompte){
        echo "non connecté"; // Afficher un message indiquant que l'utilisateur n'est pas connecté
        header("Location:".$rootpath."/index.php"); // Rediriger l'utilisateur vers la page d'accueil
        exit(); // Arrêter l'exécution du script
    }
}

// Obtenir les informations du compte à partir de l'ID protégé
$Infos = $SQLconn->profile->GetInfoProfile($protectedID);

// Vérifier si l'utilisateur veut unfollow le compte externe
if (isset($_POST["unfollow"])) {
    $SQLconn->profile->unfollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Appeler la fonction pour unfollow
    $SQLconn->notification->notifyUnfollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Notifier l'unfollow
    $SQLconn->notification->verifNotificationFollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Vérifier les notifications de follow
}

// Vérifier si l'utilisateur veut follow le compte externe
if (isset($_POST["follow"])) {
    $SQLconn->profile->follow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Appeler la fonction pour follow
    $SQLconn->notification->notifyFollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Notifier le follow
    $SQLconn->notification->verifNotificationUnfollow($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Vérifier les notifications de unfollow
}

// Si c'est le compte de l'utilisateur connecté, utiliser ses propres informations de compte, sinon utiliser celles du compte externe
if ($monCompte){
    $_SESSION["Infos"]=$Infos; // Stocker les informations du compte dans la session
} else {
    $_SESSION["Infos"]=$InfosCompteExterne; // Stocker les informations du compte externe dans la session
}

// Vérifier si l'utilisateur veut bannir définitivement le compte externe
$bandefinitif = false;
if (isset($_POST["banDef"])) {
    $bandefinitif = true; // Activer la variable pour le bannissement définitif
    // Appeler la fonction pour bannir définitivement
    $result = $SQLconn->profile->bandef($InfosCompteExterne["id_utilisateur"], $_POST["raison"]);
    // Vérifier si le bannissement a été réussi
    if ($result){
        $SQLconn->notification->notifyBan($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Notifier le bannissement
        $bandefinitif = false; // Désactiver la variable de bannissement définitif
    } else {
        echo "Erreur lors du bannissement de l'utilisateur"; // Afficher un message d'erreur en cas d'échec du bannissement
    }
}

// Vérifier si l'utilisateur veut bannir temporairement le compte externe
$banTemporaire = false;
if (isset($_POST["banTemp"]) && isset($_POST["dateFin"])) {
    $banTemporaire = true; // Activer la variable pour le bannissement temporaire
    // Appeler la fonction pour bannir temporairement
    $result = $SQLconn->profile->banTemp($InfosCompteExterne["id_utilisateur"], $_POST["dateFin"], $_POST["raison"]);
    // Vérifier si le bannissement a été réussi
    if ($result){
        $SQLconn->notification->notifyBan($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Notifier le bannissement
        $banTemporaire = false; // Désactiver la variable de bannissement temporaire
    } else {
        echo "Erreur lors du bannissement de l'utilisateur"; // Afficher un message d'erreur en cas d'échec du bannissement
    }
}

// Vérifier si l'utilisateur veut débannir le compte externe
$unban = false;
if (isset($_POST["unban"])) {
    $unban = true; // Activer la variable pour le débannissement
    // Appeler la fonction pour débannir
    $result = $SQLconn->profile->unban($InfosCompteExterne["id_utilisateur"]);
    // Vérifier si le débannissement a été réussi
    if ($result){
        $SQLconn->notification->notifyUnban($_COOKIE['user_id'], $InfosCompteExterne["id_utilisateur"]); // Notifier le débannissement
        $unban = false; // Désactiver le drapeau de débannissement
    } else {
        echo "Erreur lors du débannissement de l'utilisateur"; // Afficher un message d'erreur en cas d'échec du débannissement
    }
}

// Vérifier si l'utilisateur est banni définitivement
$banDef = false;
if (isset($_COOKIE['user_id'])){
    $banDef =  $SQLconn->profile->checkBan($_COOKIE['user_id']);
    // Vérifier si l'utilisateur est banni définitivement et si ce n'est pas son propre compte
    if ($banDef and !$monCompte){
        echo "Vous êtes banni définitivement"; // Afficher un message indiquant que l'utilisateur est banni définitivement
        header("Location:./profile.php?id=". $_COOKIE['user_id']); // Rediriger l'utilisateur vers son profil
    }
}

// Inclure l'en-tête de la page
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
    <main id="mainContent">
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-8">
                  <?php 
                    // Inclure les différents modules en fonction des actions de l'utilisateur
                    if (!$monCompte && $banDef){
                        include ("BoutDePages/monProfile.php"); // Afficher le profil de l'utilisateur connecté s'il est banni
                    } else if (!$monCompte && !$banDef){
                            include ("BoutDePages/voirProfile.php"); // Afficher le profil de l'utilisateur externe s'il n'est pas banni
                    } else if ($modifierProfile){
                        include ("BoutDePages/modifierProfile.php"); // Afficher le formulaire de modification du profil
                    } else if ($modifierMotDePasse){
                        include ("BoutDePages/modifiermdp.php"); // Afficher le formulaire de modification du mot de passe
                    } else if ($following){
                        include ("BoutDePages/following.php"); // Afficher les utilisateurs suivis
                    } else if ($follower){
                        include ("BoutDePages/follower.php"); // Afficher les followers
                    } else if ($statistiques){
                        include ("BoutDePages/statistique.php"); // Afficher les statistiques du compte
                    } else {
                        include ("BoutDePages/monProfile.php"); // Par défaut, afficher le profil de l'utilisateur connecté
                    }
                  ?>
                </div>
            </div>
        </div>
        <br>
    </main>
    <script src="JS/profile.js"></script>
    <script src="JS/monProfile.js"></script>
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
