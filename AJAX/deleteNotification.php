<?php


include ("../loc.php");


if (!isset($_COOKIE['user_id'])) {
    header("Location: index.php"); 
    exit();
}

if (isset($_GET["id"])) {
    $result = $SQLconn->notification->supprimerNotification($_GET["id"]);
    if ($result) {
        echo "success";
    } else {
        echo "error";
    }
} 

?>