<?php
include ("loc.php");
echo $_GET['redirect'];
$SQLconn->loginStatus->logout($_GET['redirect']);
exit();
?>

