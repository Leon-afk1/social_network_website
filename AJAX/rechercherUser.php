<?php
    include ("../loc.php"); // Inclut le fichier loc.php contenant les paramètres de connexion à la base de données

    // Sécurise la chaîne de texte provenant de la requête GET
    $protectedText = $SQLconn->SecurizeString_ForSQL($_GET["var"]);

    // Vérifie si la chaîne de texte est vide
    if ($protectedText == "") {
        echo ""; // Si la chaîne est vide, n'affiche rien et quitte le script
        exit();
    }

    // Vérifie à nouveau si la chaîne de texte n'est pas vide
    if ($protectedText != "") {
        // Construit la requête SQL pour rechercher des utilisateurs dont le nom d'utilisateur correspond à la chaîne de texte
        $query = "SELECT id_utilisateur, username, avatar FROM `utilisateur` WHERE LOWER(username) LIKE LOWER('%$protectedText%')";
        // Exécute la requête SQL
        $result = $SQLconn->executeRequete($query);

        // Vérifie s'il y a des résultats dans la requête
        if ($result->num_rows > 0) {
            // Boucle à travers les résultats et affiche chaque utilisateur correspondant
            while( $row = $result->fetch_assoc() ){
                echo "
                <a class='nav-link active' aria-current='page' href='./profile.php?id=".$row["id_utilisateur"]."'> 
                    <img src='".$row["avatar"]."' class='avatar avatar-xs'>
                    <label for='nom'>". $row["username"]."</label>
                </a>";
            }
        } else {
            echo "Aucun résultat"; // Affiche un message si aucun résultat n'est trouvé
        }
    }

?>
