<?php
class Notification {

    private $SQLconn;

    public function __construct(ConnexionBDD $SQLconn) {
        $this->SQLconn = $SQLconn;
    }

    function notifyUnfollow($userId, $userIdToUnfollow){
    
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification) VALUES ($userIdToUnfollow, $userId, 'unfollow', NOW())";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }
    
    function notifyFollow($userId, $userIdToFollow){
    
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification) VALUES ($userIdToFollow, $userId, 'follow', NOW())";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }
    
    function verifNotificationUnfollow($userId, $userIdToUnfollow){
    
        $query = "SELECT * FROM notification WHERE id_utilisateur = $userIdToUnfollow AND id_utilisateur_cible = $userId AND type = 'unfollow'";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows > 0) {
            $query = "DELETE FROM notification WHERE id_utilisateur = $userIdToUnfollow AND id_utilisateur_cible = $userId AND type = 'unfollow'";
            if ($this->SQLconn->executeRequete($query)){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    function verifNotificationFollow($userId, $userIdToFollow){
    
        $query = "SELECT * FROM notification WHERE id_utilisateur = $userIdToFollow AND id_utilisateur_cible = $userId AND type = 'follow'";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows > 0) {
            $query = "DELETE FROM notification WHERE id_utilisateur = $userIdToFollow AND id_utilisateur_cible = $userId AND type = 'follow'";
            if ($this->SQLconn->executeRequete($query)){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    function notifyPost($userId, $postId){
    
        $query = "SELECT id_utilisateur FROM follower WHERE id_utilisateur_suivi = $userId";
        $result = $this->SQLconn->executeRequete($query);
        while ($row = $result->fetch_assoc()) {
            $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, id_post_cible, type, date_notification) VALUES ({$row['id_utilisateur']}, $userId, $postId, 'post', NOW())";
            $this->SQLconn->executeRequete($query);
        }
    }
    
    function getNbNotifications($userId){
        $query = "SELECT COUNT(*) FROM notification WHERE id_utilisateur = $userId AND bool_lue = 0";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'];
    }

    function supprimerAllNotifications($userId){
        $query = "DELETE FROM notification WHERE id_utilisateur = $userId";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }
    
    function supprimerNotification( $idNotification){
        $query = "DELETE FROM notification WHERE id_notification = $idNotification";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }

    function getNotifications($userId){
        $query = "SELECT notification.id_notification, notification.type, notification.id_utilisateur, notification.date_notification, notification.bool_lue, 
                    notification.id_utilisateur_cible, notification.id_post_cible, notification.message_notification, utilisateur.username, utilisateur.avatar 
                    FROM notification INNER JOIN utilisateur ON notification.id_utilisateur_cible = utilisateur.id_utilisateur
                    WHERE notification.id_utilisateur = '$userId'";        
        $result = $this->SQLconn->executeRequete($query);
        $notifications = array();
        while ($row = $result->fetch_assoc()) {
            array_push($notifications, $row);
        }
        return $notifications;
    }

    public function markNotificationsAsRead($userId){
        $query = "UPDATE notification SET bool_lue = 1 WHERE id_utilisateur = $userId";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }

    public function notifyBan($userId, $userIdToBan){
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification) VALUES ($userIdToBan, $userId, 'ban', NOW())";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }

    public function notifyUnban($userId, $userIdToUnban){
        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification) VALUES ($userIdToUnban, $userId, 'unban', NOW())";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }

    public function avertissement( $postId, $raison){
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];
        $userId = $_COOKIE['user_id'];

        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification, id_post_cible, message_notification) VALUES ($userIdToAvertir, $userId, 'avertissement', NOW(), $postId, '$raison')";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }

    public function notifMarquerSensible($postId, $message){
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];
        $userId = $_COOKIE['user_id'];

        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification, id_post_cible, message_notification) VALUES ($userIdToAvertir, $userId, 'sensible', NOW(), $postId, '$message')";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }

    public function notificationRetirerPost($postId, $message){
        $query = "SELECT id_utilisateur FROM post WHERE id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $userIdToAvertir = $row['id_utilisateur'];
        $userId = $_COOKIE['user_id'];

        $query = "Insert into notification (id_utilisateur, id_utilisateur_cible, type, date_notification, id_post_cible, message_notification) VALUES ($userIdToAvertir, $userId, 'retirerPost', NOW(), $postId, '$message')";
        if ($this->SQLconn->executeRequete($query)){
            return true;
        } else {
            return false;
        }
    }

}

?>
