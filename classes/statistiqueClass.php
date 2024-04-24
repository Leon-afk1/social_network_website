<?php

class Statistiques {
    private $SQLconn;

    // Constructeur de la classe Statistiques
    public function __construct(ConnexionBDD $SQLconn) {
        $this->SQLconn = $SQLconn; // Initialisation de la connexion à la base de données
    }

    // Méthode pour obtenir la liste des abonnés d'un utilisateur
    public function getFollowers($userId){

        // Requête SQL pour récupérer les informations des abonnés d'un utilisateur donné
        $query = "SELECT utilisateur.dateNaissance, utilisateur.avatar, utilisateur.username, utilisateur.description, 
        utilisateur.id_utilisateur FROM utilisateur INNER JOIN follower ON utilisateur.id_utilisateur = follower.id_utilisateur WHERE id_utilisateur_suivi = $userId";

        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
    
        // Initialisation du tableau des abonnés
        $followers = [];

        // Parcours des résultats et ajout des abonnés au tableau
        while ($row = $result->fetch_assoc()) {
            $follower = [
                'dateNaissance' => $row['dateNaissance'],
                'avatar' => $row['avatar'],
                'username' => $row['username'],
                'description' => $row['description'],
                'id_utilisateur' => $row['id_utilisateur']
            ];
            $followers[] = $follower;
        }
    
        return $followers; // Retourne la liste des abonnés
    }

    // Méthode pour obtenir la liste des utilisateurs suivis par un utilisateur
    public function getFollowed($userId){

        // Requête SQL pour récupérer les informations des utilisateurs suivis par un utilisateur donné
        $query = "SELECT utilisateur.dateNaissance, utilisateur.avatar, utilisateur.username, utilisateur.description, 
                    utilisateur.id_utilisateur FROM utilisateur INNER JOIN follower ON utilisateur.id_utilisateur = follower.id_utilisateur_suivi WHERE follower.id_utilisateur = $userId";

        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result =  $this->SQLconn->executeRequete($query);
    
        // Initialisation du tableau des utilisateurs suivis
        $following = [];

        // Parcours des résultats et ajout des utilisateurs suivis au tableau
        while ($row = $result->fetch_assoc()) {
            $follow = [
                'dateNaissance' => $row['dateNaissance'],
                'avatar' => $row['avatar'],
                'username' => $row['username'],
                'description' => $row['description'],
                'id_utilisateur' => $row['id_utilisateur']
            ];
            $following[] = $follow;
        }
    
        return $following; // Retourne la liste des utilisateurs suivis
    }

    // Méthode pour obtenir la liste des posts d'un utilisateur
    public function getPosts($userId) {
        // Requête SQL pour récupérer tous les posts d'un utilisateur donné
        $query = "SELECT * FROM post WHERE id_utilisateur = '$userId'";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
        $posts = [];
        // Parcours des résultats et ajout des posts au tableau
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts; // Retourne la liste des posts de l'utilisateur
    }

    // Méthode pour calculer l'âge moyen des utilisateurs
    public function calculateAverageAge($users) {
        $totalAge = 0;
        foreach ($users as $user) {
            $totalAge += $this->getAge($user["dateNaissance"]);
        }
        if (count($users) > 0) {
            return $totalAge / count($users); // Retourne l'âge moyen
        } else {
            return 0; // Retourne 0 si aucun utilisateur n'est trouvé
        }
    }

    // Méthode pour obtenir le nombre de posts d'un utilisateur
    public function getNbPost($userId){
        // Requête SQL pour obtenir le nombre de posts d'un utilisateur donné
        $query = "SELECT COUNT(*) FROM post WHERE id_utilisateur = $userId";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        return $row['COUNT(*)']; // Retourne le nombre de posts
    }

    // Méthode pour obtenir le nombre moyen de posts par jour d'un utilisateur
    public function getNbPostParJour($userId){
        $moyenne = 0;
        // Requête SQL pour obtenir le nombre de posts d'un utilisateur donné
        $query = "SELECT COUNT(*) FROM post WHERE id_utilisateur = $userId";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $nbPost = $row['COUNT(*)'];

        // Requête SQL pour obtenir le nombre de jours depuis le dernier post
        $query = "SELECT DATEDIFF(NOW(), (SELECT date FROM post WHERE id_utilisateur = $userId ORDER BY date DESC LIMIT 1)) As nbJours";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $nbJours = $row['nbJours'];

        if ($nbJours != 0){
            $moyenne = $nbPost / $nbJours;
        }
        return $moyenne; // Retourne la moyenne de posts par jour
    }

    // Méthode pour obtenir le nombre moyen de posts par semaine d'un utilisateur
    public function getNbPostParSemaine($userId){
        $moyenne = 0;
        // Requête SQL pour obtenir le nombre de posts d'un utilisateur donné
        $query = "SELECT COUNT(*) FROM post WHERE id_utilisateur = $userId";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $nbPost = $row['COUNT(*)'];

        // Requête SQL pour obtenir le nombre de jours depuis le dernier post
        $query = "SELECT DATEDIFF(NOW(), (SELECT date FROM post WHERE id_utilisateur = $userId ORDER BY date DESC LIMIT 1)) As nbJours";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $nbJours = $row['nbJours'];

        if ($nbJours != 0){
            $moyenne = $nbPost / ($nbJours / 7);
        }
        return $moyenne; // Retourne la moyenne de posts par semaine
    }

    // Méthode pour obtenir le nombre moyen de posts par mois d'un utilisateur
    public function getNbPostParMois($userId){
        $moyenne = 0;
        // Requête SQL pour obtenir le nombre de posts d'un utilisateur donné
        $query = "SELECT COUNT(*) FROM post WHERE id_utilisateur = $userId";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $nbPost = $row['COUNT(*)'];

        // Requête SQL pour obtenir le nombre de jours depuis le dernier post
        $query = "SELECT DATEDIFF(NOW(), (SELECT date FROM post WHERE id_utilisateur = $userId ORDER BY date DESC LIMIT 1)) As nbJours";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $nbJours = $row['nbJours'];

        if ($nbJours != 0){
            $moyenne = $nbPost / ($nbJours / 30);
        }
        return $moyenne; // Retourne la moyenne de posts par mois
    }

    // Méthode privée pour calculer l'âge à partir de la date de naissance
    private function getAge($dateNaissance) {
        $date = new DateTime($dateNaissance);
        $now = new DateTime();
        $interval = $now->diff($date);
        return $interval->y; // Retourne l'âge en années
    }
}

?>
