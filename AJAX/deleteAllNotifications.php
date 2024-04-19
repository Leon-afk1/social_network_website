<?php

include ("../loc.php");


if (!isset($_COOKIE['user_id'])) {
    header("Location: index.php"); 
    exit();
}

$result = $SQLconn->notification->supprimerAllNotifications($_GET["idUser"]);
if ($result) {
    echo "success";
} else {
    echo $result;
}

?>