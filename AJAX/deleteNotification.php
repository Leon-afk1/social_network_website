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
} else if (isset($_GET["idUser"])) {
    $result = $SQLconn->notification->supprimerAllNotifications($_GET["idUser"]);
    if ($result) {
        echo "success";
    } else {
        echo "error all post";
    }
}

?>