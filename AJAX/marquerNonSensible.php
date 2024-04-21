<?php

include ("../loc.php");

if(isset($_POST['postId']) ) {
    $postId = $_POST['postId'];


    $result1= $SQLconn->notification->notificationNonSensible($postId);
    $result2= $SQLconn->profile->enleverMarqueSensible($postId);

    if($result1 && $result2) {
        echo "Post marqué comme non sensible";
    } else {
        echo "Erreur lors du marquage du post.";
    }

} else {
    echo "Erreur lors de la récupération des données.";
}

?>