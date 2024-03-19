<?php 

function SecurizeString_ForSQL($string) {
    $string = trim($string);
    $string = stripcslashes($string);
    $string = addslashes($string);
    $string = htmlspecialchars($string);
    return $string;
} //TD3 : éviter les injections SQL et les requêtes malicieuses

?>
