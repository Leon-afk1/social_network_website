<?php

include ("../loc.php"); // Inclut le fichier loc.php contenant les paramètres de connexion à la base de données
session_start(); // Démarre une session

// Vérifie si la requête HTTP est de type POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Vérifie si la clé 'idToUnfollow' est définie dans les données POST
    if (isset($_POST["idToUnfollow"])) {
        // Appelle la méthode 'unfollow' du profil avec les identifiants du compte et de l'utilisateur à ne plus suivre
        $result = $SQLconn->profile->unfollow($_POST["idToUnfollow"], $_SESSION['Infos']['id_utilisateur']);
        
        // Vérifie si l'action de ne plus suivre a réussi
        if ($result) {
            echo "success"; // Affiche "success" si l'action a réussi
        } else {
            echo "error"; // Affiche "error" si une erreur s'est produite
        }
    } else {
        echo "error"; // Affiche "error" si la clé 'idToUnfollow' n'est pas définie dans les données POST
    }
} else {
    echo "error"; // Affiche "error" si la requête HTTP n'est pas de type POST
}

?>
