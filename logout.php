<?php
include ("loc.php");

//On récupère l'endroit où on doit rediriger l'utilisateur
echo $_GET['redirect'];

//On déconnecte l'utilisateur en le gardant sur la page ou il était
$SQLconn->loginStatus->logout($_GET['redirect']);

exit();
?>

