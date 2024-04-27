<?php 
include ("../loc.php");

if(isset($_POST['userID']) && isset($_POST['postID'])) {

    $userId = $_POST['userID'];
    $postId = $_POST['postID'];
    $isLiked = $_POST['isLiked'];

    if($isLiked == 1) {
        $result= $SQLconn->profile->removeLike($postId, $userId);
        if($result) {
            echo "Like removed";
        } else {
            echo "Erreur lors du retrait du like.";
        }
    } else {
        $result= $SQLconn->profile->addLike($postId, $userId);
        if($result) {
            echo "Like added";
        } else {
            echo "Erreur lors de l'ajout du like.";
        }
    }
} else {
    echo "Erreur lors de la récupération des données.";
}
?>