<?php
// Démarre la session
session_start();

// Détruit toutes les variables de session
session_destroy();

// Redirige vers la page d'accueil (index.php)
header("Location: index.php");
exit();
?>
