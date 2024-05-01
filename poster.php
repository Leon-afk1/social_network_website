<?php
// Inclure les fichiers nécessaires
include ("loc.php"); 

$ajouterPost = false; // Initialiser une variable pour vérifier si un post doit être ajouté

// Vérifier si le formulaire de soumission de post a été soumis
if (isset($_POST["submitReponse"])) {
    $ajouterPost = true; 

    // Appeler la fonction pour ajouter un nouveau post
    $result = $SQLconn->profile->ajouterNewPost($_COOKIE['user_id']);
    
    // Vérifier si l'ajout de post a été réussi
    if ($result["Successful"]) {
        $ajouterPost = false; 
        // Notifier l'utilisateur de la création du post
        $SQLconn->notification->notifyPost($_COOKIE['user_id'], $result["idPost"]);
        // Rediriger l'utilisateur vers la page d'accueil
        header("Location:./index.php");
        exit(); // Arrêter l'exécution du script
    } else {
        $erreurPost = $result["ErrorMessage"]; // Récupérer le message d'erreur en cas d'échec de l'ajout de post
    }
}

// Vérifier si l'utilisateur n'est pas connecté
if (!$SQLconn->loginStatus->loginSuccessful) {
    echo "non connecté"; // Afficher un message indiquant que l'utilisateur n'est pas connecté
    header("Location:./index.php"); // Rediriger l'utilisateur vers la page d'accueil
    exit(); // Arrêter l'exécution du script
}

// Vérifier si l'utilisateur est banni
if (isset($_COOKIE['user_id'])){
  $ban = $SQLconn->profile->checkBan($_COOKIE['user_id']);
  if ($ban){
      header("Location:profile.php?id=". $_COOKIE['user_id']); // Rediriger l'utilisateur vers son profil
    }
}

// Inclure l'en-tête de la page
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

    <main id="mainContent">
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-6">
                  <?php 
                    // Inclure le formulaire d'ajout de post
                    include ("BoutDePages/ajouterPost.php");
                  ?>
                </div>
            </div>
        </div>
        <br>
    </main>
    <script src="https://code.jquery.com/jquery.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
