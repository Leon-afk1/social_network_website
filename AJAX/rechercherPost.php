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
        // Construit la requête SQL pour rechercher des posts contenant la chaîne de texte
        $query =  "SELECT post.id_post, post.id_utilisateur, post.contenu,  utilisateur.username, utilisateur.avatar FROM `post` INNER JOIN `utilisateur` ON post.id_utilisateur = utilisateur.id_utilisateur WHERE LOWER(post.contenu) LIKE LOWER('%$protectedText%')";
        // Exécute la requête SQL
        $result = $SQLconn->executeRequete($query);

        // Vérifie s'il y a des résultats dans la requête
        if ($result->num_rows > 0) {
            // Boucle à travers les résultats et affiche chaque post correspondant
            while( $row = $result->fetch_assoc() ){
                echo "
                <a class='nav-link active' aria-current='page' href='./post.php?id=".$row["id_post"]."'> 
                    <img src='".$row["avatar"]."' class='avatar avatar-xs'>
                    <label for='nom'>". $row["username"]."</label>
                    <p>". $row["contenu"]."</p>
                </a>";
            }
        } else {
            echo "Aucun résultat"; // Affiche un message si aucun résultat n'est trouvé
        }
    }

?>
