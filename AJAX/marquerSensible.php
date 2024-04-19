<?php

include ("../loc.php");

if(isset($_POST['postId']) && isset($_POST['message'])) {
    $postId = $_POST['postId'];
    $message = $_POST['message'];


    $result1= $SQLconn->notification->notifMarquerSensible($postId, $message);
    $result2= $SQLconn->profile->marquerSensible($postId);

    if($result1 && $result2) {
        echo "Post marqué sensible.";
    } else {
        echo "Erreur lors du marquage du post sensible.";
    }

} else {
    echo "Erreur lors de la récupération des données.";
}

?>