<?php

require_once(__ROOT__.'/classes/verifLogin.php');
require_once(__ROOT__.'/classes/notificationClass.php');
require_once(__ROOT__.'/classes/statistiqueClass.php');
require_once(__ROOT__.'/classes/profileClass.php');

class ConnexionBDD {
    
    public $conn = NULL;
    public $loginStatus = NULL;
    public $notification = NULL;
    public $statistiques = NULL;
    public $profile = NULL;


    public function __construct() {
        $serveur = 'localhost';
        $utilisateur = 'root';
        $motDePasse = '';
        $nomBaseDeDonnees = 'twitterlike';

        global $conn;

        $conn = new mysqli($serveur, $utilisateur, $motDePasse, $nomBaseDeDonnees);
        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        $this->loginStatus = new verifLogin($this);
        $this->notification = new Notification($this);
        $this->statistiques = new Statistiques($this);
        $this->profile = new profile($this);
    }

    public function executeRequete($query) {
        try {
            global $conn;
            $result = $conn->query($query);
            return $result;
        } catch (PDOException $e) {
            echo "Erreur d'exécution de la requête : " . $e->getMessage();
            return false;
        }
    }
    

    public function SecurizeString_ForSQL($string) {
        $string = trim($string);
        $string = stripcslashes($string);
        $string = addslashes($string);
        $string = htmlspecialchars($string);
        return $string;
    }    

}
?>
