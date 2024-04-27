<?php

include ("../loc.php"); // Inclut le fichier loc.php contenant les paramètres de connexion à la base de données

// Vérifie si la clé 'id' est définie dans les données GET
if (isset($_GET["id"])) {
    // Sécurise la valeur de 'id' pour une utilisation dans une requête SQL
    $idPost = $SQLconn->SecurizeString_ForSQL($_GET["id"]);
    
    // Appelle la méthode 'deletePost' du profil avec l'identifiant du post à supprimer
    $result = $SQLconn->profile->deletePost($idPost);
    
    // Vérifie si la suppression du post a réussi
    if ($result) {
        echo "Post supprimé"; // Affiche "Post supprimé" si la suppression a réussi
    } else {
        echo "Erreur lors de la suppression du post"; // Affiche "Erreur lors de la suppression du post" si une erreur s'est produite
    }
}

?>
