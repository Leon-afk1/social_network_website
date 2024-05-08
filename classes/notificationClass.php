<?php

// Définition de la classe Notification
class Notification {

    private $SQLconn; // Propriété pour stocker l'objet de connexion à la base de données

    // Constructeur de la classe
    public function __construct(ConnexionBDD $SQLconn) {
        $this->SQLconn = $SQLconn; // Initialisation de la propriété SQLconn avec l'objet de connexion passé en paramètre
    }

    // Méthode pour notifier un utilisateur qu'un autre utilisateur l'a unfollow
    function notifyUnfollow($userId, $userIdToUnfollow){
        // Requête SQL pour insérer une notification dans la base de données
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification) VALUES ($userIdToUnfollow, $userId, 'unfollow', NOW())";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }
    
    // Méthode pour notifier un utilisateur qu'un autre utilisateur l'a follow
    function notifyFollow($userId, $userIdToFollow){
        // Requête SQL pour insérer une notification dans la base de données
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification) VALUES ($userIdToFollow, $userId, 'follow', NOW())";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }
    
    // Méthode pour vérifier et supprimer une notification d'unfollow
    function verifNotificationUnfollow($userId, $userIdToUnfollow){
        // Requête SQL pour vérifier si une notification d'unfollow existe
        $query = "SELECT * FROM notification WHERE id_utilisateur = $userIdToUnfollow AND id_utilisateur_cible = $userId AND type = 'unfollow'";
        $result = $this->SQLconn->executeRequete($query);
        // Vérification du nombre de lignes retournées par la requête
        if ($result->num_rows > 0) {
            // Requête SQL pour supprimer la notification d'unfollow
            $query = "DELETE FROM notification WHERE id_utilisateur = $userIdToUnfollow AND id_utilisateur_cible = $userId AND type = 'unfollow'";
            // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
            if ($this->SQLconn->executeRequete($query)){
                return true; // Retourne true si la suppression est réussie
            } else {
                return false; // Retourne false en cas d'échec
            }
        } else {
            return false; // Retourne false si aucune notification d'unfollow n'est trouvée
        }
    }
    
    // Méthode pour vérifier et supprimer une notification de follow
    function verifNotificationFollow($userId, $userIdToFollow){
        // Requête SQL pour vérifier si une notification de follow existe
        $query = "SELECT * FROM notification WHERE id_utilisateur = $userIdToFollow AND id_utilisateur_cible = $userId AND type = 'follow'";
        $result = $this->SQLconn->executeRequete($query);
        // Vérification du nombre de lignes retournées par la requête
        if ($result->num_rows > 0) {
            // Requête SQL pour supprimer la notification de follow
            $query = "DELETE FROM notification WHERE id_utilisateur = $userIdToFollow AND id_utilisateur_cible = $userId AND type = 'follow'";
            // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
            if ($this->SQLconn->executeRequete($query)){
                return true; // Retourne true si la suppression est réussie
            } else {
                return false; // Retourne false en cas d'échec
            }
        } else {
            return false; // Retourne false si aucune notification de follow n'est trouvée
        }
    }
    
    // Méthode pour notifier les followers d'un utilisateur lorsqu'il publie un post
    function notifyPost($userId, $postId){
        // Requête SQL pour sélectionner les id_utilisateur des followers de l'utilisateur
        $query = "SELECT id_utilisateur FROM follower WHERE id_utilisateur_suivi = $userId";
        $result = $this->SQLconn->executeRequete($query);
        // Parcours des résultats pour notifier chaque follower
        while ($row = $result->fetch_assoc()) {
            // Requête SQL pour insérer une notification dans la base de données
            $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, id_post_cible, type, date_notification) VALUES ({$row['id_utilisateur']}, $userId, $postId, 'post', NOW())";
            // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
            $this->SQLconn->executeRequete($query);
        }
    }
    
    // Méthode pour obtenir le nombre de notifications non lues pour un utilisateur
    function getNbNotifications($userId){
        // Requête SQL pour compter le nombre de notifications non lues
        $query = "SELECT COUNT(*) FROM notification WHERE id_utilisateur = $userId AND bool_lue = 0";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        return $row['COUNT(*)']; // Retourne le nombre de notifications non lues
    }

    // Méthode pour supprimer toutes les notifications d'un utilisateur
    function supprimerAllNotifications($userId){
        // Requête SQL pour supprimer toutes les notifications d'un utilisateur
        $query = "DELETE FROM notification WHERE id_utilisateur = $userId";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si la suppression est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }
    
    // Méthode pour supprimer une notification spécifique
    function supprimerNotification($idNotification){
        // Requête SQL pour supprimer une notification spécifique
        $query = "DELETE FROM notification WHERE id_notification = $idNotification";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si la suppression est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    // Méthode pour obtenir toutes les notifications d'un utilisateur
    function getNotifications($userId){
        // Requête SQL pour sélectionner toutes les notifications d'un utilisateur
        $query = "SELECT notification.id_notification, notification.type, notification.id_utilisateur, notification.date_notification, notification.bool_lue, 
                    notification.id_utilisateur_cible, notification.id_post_cible, notification.message_notification, utilisateur.username, utilisateur.avatar 
                    FROM notification INNER JOIN utilisateur ON notification.id_utilisateur_cible = utilisateur.id_utilisateur
                    WHERE notification.id_utilisateur = '$userId' ORDER BY notification.date_notification DESC";      
        $result = $this->SQLconn->executeRequete($query);
        $notifications = array();
        // Parcours des résultats pour récupérer les notifications
        while ($row = $result->fetch_assoc()) {
            array_push($notifications, $row);
        }
        return $notifications; // Retourne un tableau contenant les notifications
    }

    // Méthode pour marquer toutes les notifications d'un utilisateur comme lues
    public function markNotificationsAsRead($userId){
        // Requête SQL pour mettre à jour le champ bool_lue à 1 (lu) pour toutes les notifications de l'utilisateur
        $query = "UPDATE notification SET bool_lue = 1 WHERE id_utilisateur = $userId";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si la mise à jour est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    // Méthode pour notifier un utilisateur qu'un autre utilisateur a été banni
    public function notifyBan($userId, $userIdToBan){
        // Requête SQL pour insérer une notification dans la base de données
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification) VALUES ($userIdToBan, $userId, 'ban', NOW())";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    // Méthode pour notifier un utilisateur qu'un autre utilisateur a été débanni
    public function notifyUnban($userId, $userIdToUnban){
        // Requête SQL pour insérer une notification dans la base de données
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification) VALUES ($userIdToUnban, $userId, 'unban', NOW())";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    // Méthode pour notifier un utilisateur d'un avertissement concernant un post
    public function avertissement($postId, $raison){
        // Requête SQL pour obtenir l'id_utilisateur du propriétaire du post
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];
        $userId = $_COOKIE['user_id'];

        // Requête SQL pour insérer une notification dans la base de données
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification, id_post_cible, message_notification) VALUES ($userIdToAvertir, $userId, 'avertissement', NOW(), $postId, '$raison')";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    // Méthode pour notifier un utilisateur qu'un de ses posts a été marqué comme sensible
    public function notifMarquerSensible($postId, $message){
        // Requête SQL pour obtenir l'id_utilisateur du propriétaire du post
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];
        $userId = $_COOKIE['user_id'];

        // Requête SQL pour insérer une notification dans la base de données
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification, id_post_cible, message_notification) VALUES ($userIdToAvertir, $userId, 'sensible', NOW(), $postId, '$message')";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    // Méthode pour notifier un utilisateur qu'un de ses posts a été retiré
    public function notificationRetirerPost($postId, $message){
        // Requête SQL pour obtenir l'id_utilisateur du propriétaire du post
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];
        $userId = $_COOKIE['user_id'];

        // Requête SQL pour insérer une notification dans la base de données
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification, id_post_cible, message_notification) VALUES ($userIdToAvertir, $userId, 'retirerPost', NOW(), $postId, '$message')";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    // Méthode pour notifier un utilisateur qu'un de ses posts a été marqué comme non sensible
    public function notificationNonSensible($postId){
        // Requête SQL pour obtenir l'id_utilisateur du propriétaire du post
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];
        $userId = $_COOKIE['user_id'];

        // Requête SQL pour insérer une notification dans la base de données
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification, id_post_cible) VALUES ($userIdToAvertir, $userId, 'nonSensible', NOW(), $postId)";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    // Méthode pour notifier un utilisateur qu'un de ses posts a été remis en ligne
    public function notificationRemettrePost($postId){
        // Requête SQL pour obtenir l'id_utilisateur du propriétaire du post
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];
        $userId = $_COOKIE['user_id'];

        // Requête SQL pour insérer une notification dans la base de données
        $query = "INSERT into notification (id_utilisateur, id_utilisateur_cible, type, date_notification, id_post_cible) VALUES ($userIdToAvertir, $userId, 'remettrePost', NOW(), $postId)";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

    public function signaler($id,$message,$idUser){
        // Requête pour vérifier l'existence du post à signaler
        $query = "SELECT * FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
        
        //Recuperer l'id des admins
        $query = "SELECT id_utilisateur FROM utilisateur WHERE admin = 1";
        $result = $this->SQLconn->executeRequete($query);
        $resultat = true;

        foreach ($result as $row) {
            $idAdmin = $row['id_utilisateur'];

            // Requête pour signaler le post
            $query = "INSERT into notification (id_post_cible, id_utilisateur, message_notification, id_utilisateur_cible, type, date_notification) VALUES ('$id', '$idAdmin', '$message', '$idUser', 'signalement', NOW())";
            $result1 = $this->SQLconn->executeRequete($query);
            if ($result1 && $resultat){
                $resultat = true;
            }else{
                $resultat = false;
            }
        }

        // Retourne true si le signalement est réussi, sinon false
        return $resultat;
            
    }

    public function notifyLike($userId, $postId){
        // Requête SQL pour sélectionner les id_utilisateur des followers de l'utilisateur
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];

        // Requête SQL pour insérer une notification dans la base de données
        $query = "INSERT into notification (id_utilisateur, id_utilisateur_cible, id_post_cible, type, date_notification) VALUES ($userIdToAvertir, $userId, $postId, 'like', NOW())";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si l'insertion est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }
    

    public function enleverNotifLike($postId,$userId){
        // Requête SQL pour supprimer la notification de like
        $query = "DELETE FROM notification WHERE id_utilisateur_cible = $userId AND id_post_cible = $postId AND type = 'like'";
        // Exécution de la requête SQL à l'aide de la méthode executeRequete de l'objet de connexion
        if ($this->SQLconn->executeRequete($query)){
            return true; // Retourne true si la suppression est réussie
        } else {
            return false; // Retourne false en cas d'échec
        }
    }

}

?>
