<?php
    include ("../loc.php");
    $AccountStatus = CheckLogin();

    $protectedText = SecurizeString_ForSQL($_GET["var"]);

    if ($protectedText == "") {
        echo "";
        exit();
    }
    echo 'Suggestions : <i>';

    if ($protectedText != "") {
        $query = "SELECT username FROM `utilisateur` WHERE LOWER(username) LIKE LOWER('$protectedText%')";
        $result = executeRequete($query);

        if ($result->num_rows > 0) {
            $i = 1;
            while( $row = $result->fetch_assoc() ){
            
                echo "<span onclick='autoFillName_fetch(this.innerHTML)' style='cursor: pointer;'>".ucwords($row["username"])."</span>";
                if ($i < $result->num_rows) {
                    echo " - ";
                }
                $i++;
            }
        } else {
            echo "Aucun résultat";
        }
    }

    echo '</i>';
?>