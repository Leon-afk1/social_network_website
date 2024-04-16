<?php
    include ("../loc.php");

    $protectedText = $SQLconn->SecurizeString_ForSQL($_GET["var"]);

    if ($protectedText == "") {
        echo "";
        exit();
    }
    echo '<i>';

    if ($protectedText != "") {
        $query = "SELECT id_utilisateur, username, avatar FROM `utilisateur` WHERE LOWER(username) LIKE LOWER('%$protectedText%')";
        $result = $SQLconn->executeRequete($query);

        if ($result->num_rows > 0) {
            while( $row = $result->fetch_assoc() ){
                echo "
                <a class='nav-link active' aria-current='page' href='./profile.php?id=".$row["id_utilisateur"]."'> 
                    <img src='".$row["avatar"]."' class='avatar avatar-xs'>
                    <label for='nom'>". $row["username"]."</label>
                </a>";
            }
        } else {
            echo "Aucun résultat";
        }
    }

    echo '</i>';
?>