<?php

include ("../loc.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = $SQLconn->profile->unfollow( $_POST["idToUnfollow"],$_SESSION['Infos']['id_utilisateur']);
    if ($result) {

        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>