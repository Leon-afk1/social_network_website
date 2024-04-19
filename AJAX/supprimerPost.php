<?php

include ("../loc.php");

if (isset($_GET["id"])) {
    $idPost = $SQLconn->SecurizeString_ForSQL($_GET["id"]);
    $result=$SQLconn->profile->deletePost($idPost);
    if ($result){
        echo "Post supprimé";
    }else{
        echo "Erreur lors de la suppression du post";
    }
}

?>