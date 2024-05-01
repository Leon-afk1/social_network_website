<?php
    //On définie Root pour pouvoir le réutiliser
    define ("__ROOT__", dirname(__FILE__));

    //On appelle la classe qui va nous connecter à la base de donnée
    require_once(__ROOT__.'/classes/connexionBDD.php');

    //On créer une nouvelle instance de la classe
    $SQLconn = new ConnexionBDD();
?>