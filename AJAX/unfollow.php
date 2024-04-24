<?php

include ("../loc.php"); // Inclut le fichier loc.php contenant les paramètres de connexion à la base de données

session_start(); // Démarre une session PHP

// Vérifie si la méthode de requête HTTP est POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Appelle la méthode 'unfollow' du profil avec l'identifiant de l'utilisateur connecté et l'identifiant de l'utilisateur à ne plus suivre
    $result = $SQLconn->profile->unfollow($_SESSION['Infos']['id_utilisateur'], $_POST["idToUnfollow"]);
    
    // Vérifie si l'opération 'unfollow' a réussi
    if ($result) {
        echo "success"; // Affiche "success" si l'opération a réussi
    } else {
        echo "error"; // Affiche "error" si une erreur s'est produite
    }
} else {
    echo "error"; // Affiche "error" si la méthode de requête HTTP n'est pas POST
}
?>
