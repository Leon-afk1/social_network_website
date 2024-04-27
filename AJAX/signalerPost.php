<?php

include ("../loc.php");

// Vérifier si les données postId et avertissement sont définies dans la requête POST
if(isset($_POST['idPost']) && isset($_POST['message']) && isset($_POST['idUser'])) {
    $idPost = $_POST['idPost']; // Récupérer l'identifiant du post à signaler
    $message = $_POST['message']; // Récupérer le contenu du signalement
    $idUser = $_POST['idUser']; // Récupérer l'identifiant de l'utilisateur qui signale le post

    // Appeler la fonction pour signaler le post spécifié
    $result= $SQLconn->notification->signaler($idPost, $message,$idUser);

    // Vérifier si l'opération de signalement a réussi
    if($result) {
        echo "true"; // Afficher un message de succès si le post est signalé avec succès
    } else {
        echo "Erreur lors du signalement du post."; // Afficher un message d'erreur en cas d'échec du signalement du post
    }

} else {
    echo "Erreur lors de la récupération des données."; // Afficher un message d'erreur si les données idPost et message ne sont pas définies
}



?>