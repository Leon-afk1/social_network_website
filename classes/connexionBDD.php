<?php

// Inclusion des classes nécessaires
require_once(__ROOT__.'/classes/verifLogin.php');
require_once(__ROOT__.'/classes/notificationClass.php');
require_once(__ROOT__.'/classes/statistiqueClass.php');
require_once(__ROOT__.'/classes/profileClass.php');

// Définition de la classe ConnexionBDD
class ConnexionBDD {
    
    // Propriétés de la classe
    public $conn = NULL; // Objet de connexion à la base de données
    public $loginStatus = NULL; // Objet pour la gestion de la connexion utilisateur
    public $notification = NULL; // Objet pour la gestion des notifications
    public $statistiques = NULL; // Objet pour la gestion des statistiques
    public $profile = NULL; // Objet pour la gestion des profils utilisateur

    // Constructeur de la classe
    public function __construct() {
        // Paramètres de connexion à la base de données
        $serveur = 'localhost';
        $utilisateur = 'root';
        $motDePasse = '';
        $nomBaseDeDonnees = 'twitterlike';

        // Connexion à la base de données
        global $conn; // Utilisation de la variable $conn dans toute la classe
        $conn = new mysqli($serveur, $utilisateur, $motDePasse, $nomBaseDeDonnees);
        if ($conn->connect_error) {
            die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        // Initialisation des objets
        $this->loginStatus = new verifLogin($this); // Initialisation de l'objet pour la vérification de la connexion utilisateur
        $this->notification = new Notification($this); // Initialisation de l'objet pour la gestion des notifications
        $this->statistiques = new Statistiques($this); // Initialisation de l'objet pour la gestion des statistiques
        $this->profile = new profile($this); // Initialisation de l'objet pour la gestion des profils utilisateur
    }

    // Méthode pour exécuter une requête SQL
    public function executeRequete($query) {
        try {
            global $conn; // Utilisation de la variable $conn définie dans le constructeur
            $result = $conn->query($query); // Exécution de la requête SQL
            return $result; // Renvoi du résultat de la requête
        } catch (PDOException $e) {
            echo "Erreur d'exécution de la requête : " . $e->getMessage(); // Affichage d'une erreur si la requête échoue
            return false; // Renvoi de false en cas d'erreur
        }
    }
    
    // Méthode pour sécuriser une chaîne de caractères avant l'insertion dans une requête SQL
    public function SecurizeString_ForSQL($string) {
        // Nettoyage et sécurisation de la chaîne
        $string = trim($string); // Suppression des espaces en début et fin de chaîne
        $string = stripcslashes($string); // Suppression des backslashes ajoutés par la fonction addslashes
        $string = addslashes($string); // Ajout des backslashes pour échapper les caractères spéciaux
        $string = htmlspecialchars($string); // Conversion des caractères spéciaux en entités HTML
        return $string; // Renvoi de la chaîne sécurisée
    }    

}

?>
