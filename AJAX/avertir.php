<?php

include ("../loc.php");

if(isset($_POST['postId']) && isset($_POST['avertissement'])) {
    $postId = $_POST['postId'];
    $avertissement = $_POST['avertissement'];

    $result= $SQLconn->notification->avertissement($postId, $avertissement);

    if($result) {
        echo "Avertissement envoyé.";
    } else {
        echo "Erreur lors de l'envoi de l'avertissement.";
    }

} else {
    echo "Erreur lors de la récupération des données.";
}

?>



