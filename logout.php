<?php
include ("loc.php");
$SQLconn->loginStatus->logout();
if (isset($_GET['redirect'])) {
    $redirectURL = $_GET['redirect'];
    header("Location: $redirectURL");
} else {
    header("Location: index.php");
}
exit();
?>