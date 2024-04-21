<?php

include ("../loc.php");

if(isset($_POST['postId'])) {
    $postId = $_POST['postId'];


    $result1= $SQLconn->notification->notificationRemettrePost($postId);
    $result2= $SQLconn->profile->remettrePost($postId);

    if($result1 && $result2) {
        echo "Post marqué comme non offensant";
    } else {
        echo "Erreur lors du marquage du post.";
    }
}else {
    echo "Erreur lors de la récupération des données.";
}

?>