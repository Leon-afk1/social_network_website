<?php

class Statistiques {
    private $SQLconn;

    public function __construct(ConnexionBDD $SQLconn) {
        $this->SQLconn = $SQLconn;
    }

    public function getFollowers($userId){

        $query = "SELECT utilisateur.dateNaissance, utilisateur.avatar, utilisateur.username, utilisateur.description, 
        utilisateur.id_utilisateur FROM utilisateur INNER JOIN follower ON utilisateur.id_utilisateur = follower.id_utilisateur WHERE id_utilisateur_suivi = $userId";
        $result = $this->SQLconn->executeRequete($query);
    
        $followers = [];
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
    
        return $followers;
    }

    public function getFollowed($userId){

        $query = "SELECT utilisateur.dateNaissance, utilisateur.avatar, utilisateur.username, utilisateur.description, 
                    utilisateur.id_utilisateur FROM utilisateur INNER JOIN follower ON utilisateur.id_utilisateur = follower.id_utilisateur_suivi WHERE follower.id_utilisateur = $userId";
        $result =  $this->SQLconn->executeRequete($query);
    
        $following = [];
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
    
        return $following;
    }

    public function getPosts($userId) {
        $query = "SELECT * FROM post WHERE id_utilisateur = '$userId'";
        $result = $this->SQLconn->executeRequete($query);
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
        return $posts;
    }

    public function calculateAverageAge($users) {
        $totalAge = 0;
        foreach ($users as $user) {
            $totalAge += $this->getAge($user["dateNaissance"]);
        }
        if (count($users) > 0) {
            return $totalAge / count($users);
        } else {
            return 0;
        }
    }

    public function getNbPostParJour($userId){
        $moyenne = 0;
        $query = "SELECT COUNT(*) FROM post WHERE id_utilisateur = $userId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $nbPost = $row['COUNT(*)'];
        $query = "SELECT DATEDIFF(NOW(), (SELECT date FROM post WHERE id_utilisateur = $userId ORDER BY date DESC LIMIT 1)) As nbJours";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $nbJours = $row['nbJours'];
        if ($nbJours != 0){
            $moyenne = $nbPost / $nbJours;
        }
        return $moyenne;
    }

    private function getAge($dateNaissance) {
        $date = new DateTime($dateNaissance);
        $now = new DateTime();
        $interval = $now->diff($date);
        return $interval->y;
    }
}

?>
