<?php

include ("../loc.php");

if(isset($_POST['postId']) && isset($_POST['message'])) {
    $postId = $_POST['postId'];
    $message = $_POST['message'];


    $result1= $SQLconn->notification->notificationRetirerPost($postId, $message);
    $result2= $SQLconn->profile->retirerPost($postId);

    if($result1 && $result2) {
        echo "Post retiré.";
    } else {
        echo "Erreur lors du retrait du post.";
    }
}else {
    echo "Erreur lors de la récupération des données.";
}

?>