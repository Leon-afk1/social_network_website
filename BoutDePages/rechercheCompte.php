<?php
    include ("../loc.php");

    $protectedText = SecurizeString_ForSQL($_GET["var"]);

    if ($protectedText == "") {
        echo "";
        exit();
    }
    echo 'Suggestions : <i>';

    if ($protectedText != "") {
        $query = "SELECT username, id_utilisateur, avatar FROM `utilisateur` WHERE LOWER(username) LIKE LOWER('$protectedText%')";
        $result = executeRequete($query);

        if ($result->num_rows > 0) {
            while( $row = $result->fetch_assoc() ){
                echo "

                <a class='nav-link active' aria-current='page' href='./profile.php?id=".$row["id_utilisateur"]."'>".ucwords($row["username"])."</a>
                ";
            }
        } else {
            echo "Aucun résultat";
        }
    }

    echo '</i>';
?>