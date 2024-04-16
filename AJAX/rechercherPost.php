<?php
    include ("../loc.php");

    $protectedText = $SQLconn->SecurizeString_ForSQL($_GET["var"]);

    if ($protectedText == "") {
        echo "";
        exit();
    }
    echo '<i>';

    if ($protectedText != "") {
        $query =  "SELECT post.id_post, post.id_utilisateur, post.contenu,  utilisateur.username, utilisateur.avatar FROM `post` INNER JOIN `utilisateur` ON post.id_utilisateur = utilisateur.id_utilisateur WHERE LOWER(post.contenu) LIKE LOWER('%$protectedText%')";
        $result = $SQLconn->executeRequete($query);

        if ($result->num_rows > 0) {
            while( $row = $result->fetch_assoc() ){
                echo "
                <a class='nav-link active' aria-current='page' href='./post.php?id=".$row["id_post"]."'> 
                    <img src='".$row["avatar"]."' class='avatar avatar-xs'>
                    <label for='nom'>". $row["username"]."</label>
                    <p>". $row["contenu"]."</p>
                </a>";
            }
        } else {
            echo "Aucun résultat";
        }
    }

    echo '</i>';
?>