<?php

include ("../loc.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = $SQLconn->profile->unfollow( $_SESSION['Infos']['id_utilisateur'],$_POST["idToUnfollow"]);
    if ($result) {

        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>