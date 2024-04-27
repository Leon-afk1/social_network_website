<?php

class profile {
    private $SQLconn;

    // Constructeur de la classe
    public function __construct(ConnexionBDD $SQLconn) {
        $this->SQLconn = $SQLconn;
    }

    // Méthode pour obtenir les informations de profil
    public function GetInfoProfile($userId) {
        $query = "SELECT * FROM utilisateur WHERE id_utilisateur = $userId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
    
        return $row;
    }
    
    // Méthode pour mettre à jour les informations de profil
    public function UpdateInfosProfile($userId){
        global $conn;
    
        $updateAttempted = false;
        $updateSuccessful = false;
        $error = NULL;
        
        if ($_POST["submitModification"]){
            $updateAttempted = true;
    
            // Validation des champs de formulaire
            if (strlen($_POST["nom"]) < 2){
                $error = "Nom trop court";
            }
            // Vérification de la longueur du prénom
            else if (strlen($_POST["prenom"]) < 2){
                $error = "Prénom trop court";
            }
            // Vérification de la longueur du nom d'utilisateur
            else if (strlen($_POST["username"]) < 2){
                $error = "Nom d'utilisateur trop court";
            }
            // Vérification de la longueur de l'e-mail
            else if (strlen($_POST["email"]) < 2){
                $error = "Email trop court";
            }
            // Validation de l'e-mail
            else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                $error = "Email invalide";
            }
            // Vérification de la longueur de l'adresse
            else if (strlen($_POST["adresse"]) < 5){
                $error = "Adresse trop courte";
            }
            else {
                // Vérification si le nom d'utilisateur et l'e-mail sont déjà utilisés
                $query1 = "SELECT * FROM utilisateur WHERE username = '" . $this->SQLconn->SecurizeString_ForSQL($_POST["username"]) . "'";
                $result1 = $this->SQLconn->executeRequete($query1);
                $query2 = "SELECT * FROM utilisateur WHERE email = '" . $this->SQLconn->SecurizeString_ForSQL($_POST["email"]) . "'";
                $result2 = $this->SQLconn->executeRequete($query2);
                if ($result1->num_rows > 0) {
                    if ($result1->num_rows == 1){
                        $row = $result1->fetch_assoc();
                        if ($row['id_utilisateur'] != $userId){
                            $error = "Nom d'utilisateur déjà utilisé";
                        }
                    }
                    else {
                        $error = "Nom d'utilisateur déjà utilisé";
                    }
                }
                if ($result2->num_rows > 0) {
                    if ($result2->num_rows == 1){
                        $row = $result2->fetch_assoc();
                        if ($row['id_utilisateur'] != $userId){
                            $error = "Email déjà utilisé";
                        }
                    }
                    else {
                        $error = "Email déjà utilisé";
                    }
                }
                if ($error == NULL){
                    $nom = $this->SQLconn->SecurizeString_ForSQL($_POST["nom"]);
                    $prenom = $this->SQLconn->SecurizeString_ForSQL($_POST["prenom"]);
                    $username = $this->SQLconn->SecurizeString_ForSQL($_POST["username"]);
                    $dateNaissance = $_POST["date_naissance"];
                    $email = $_POST["email"];
                    $description = $this->SQLconn->SecurizeString_ForSQL($_POST["description"]);
                    $adresse = $this->SQLconn->SecurizeString_ForSQL($_POST["adresse"]);
    
                    // Mise à jour des informations de l'utilisateur dans la base de données
                    $query = "UPDATE utilisateur SET nom = '$nom', prenom = '$prenom', username = '$username', dateNaissance = '$dateNaissance', email = '$email' , description= '$description', adresse='$adresse' WHERE id_utilisateur = $userId";
                    $result = $this->SQLconn->executeRequete($query);
                    if ($result === TRUE) {
                        $updateSuccessful = true;
                        $_COOKIE['username'] = $_POST["username"];
                    } else {
                        $error = "Erreur lors de l'insertion SQL: " . $conn->error;
                    }
                }
                
            }
            // Retourner le résultat de la tentative de mise à jour du profil
            $resultArray = ['Attempted' => $updateAttempted, 
                            'Successful' => $updateSuccessful, 
                            'ErrorMessage' => $error];
    
            return $resultArray;
        }
    }

    // Méthode pour mettre à jour l'avatar de l'utilisateur
    public function UpdateAvatar($userId){
        global $conn;
    
        $updateAttempted = false;
        $updateSuccessful = false;
        $error = NULL;
        
        if ($_POST["submitModification"]){
            $updateAttempted = true;
    
            // Vérification si un fichier est téléchargé
            if ($_FILES['avatar']["size"] == 0){
                $error = "Veuillez choisir une image";
            }
            else {
                $avatar = $_FILES["avatar"];
                $avatarPath = "./avatar/" . $userId . ".jpg";
                $avatarPath = $this->SQLconn->SecurizeString_ForSQL($avatarPath);
    
                // Mise à jour du chemin de l'avatar dans la base de données
                $query = "UPDATE utilisateur SET avatar = '$avatarPath' WHERE id_utilisateur = $userId";
                $result = $this->SQLconn->executeRequete($query);
                if ($result === TRUE) {
                    $uploadOk = 1;
                    $avatarFileType = strtolower(pathinfo($avatarPath,PATHINFO_EXTENSION));
                    $check = getimagesize($avatar["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $error = "Le fichier n'est pas une image.";
                        $uploadOk = 0;
                    }
                    if ($avatar["size"] > 500000) {
                        $error = "L'image est trop grande.";
                        $uploadOk = 0;
                    }
                    if($avatarFileType != "jpg" && $avatarFileType != "png" && $avatarFileType != "jpeg") {
                        $error = "Seuls les fichiers JPG, JPEG, PNG sont autorisés.";
                        $uploadOk = 0;
                    }
                    if ($uploadOk != 0) {
                        if (move_uploaded_file($avatar["tmp_name"], $avatarPath)) {
                            $updateSuccessful = true;
                        } else {
                            $error = "Erreur lors de l'upload de l'image.";
                        }
                    }else {
                        $query = "UPDATE utilisateur SET avatar = NULL WHERE id_utilisateur = $userId";
                        $result = $this->SQLconn->executeRequete($query);
                    }
                } else {
                    $error = "Erreur lors de l'insertion SQL: " . $conn->error;
                }
            }
        }
    
        // Retourner le résultat de la tentative de mise à jour de l'avatar
        $resultArray = ['Attempted' => $updateAttempted, 
                        'Successful' => $updateSuccessful,
                        'ErrorMessage' => $error];
    
        return $resultArray;
    
    }

    // Méthode pour changer le mot de passe de l'utilisateur
    public function changermdp($userId){
        global $conn;
    
        $updateAttempted = false;
        $updateSuccessful = false;
        $error = NULL;
        
        if ($_POST["submitModificationMdp"]){
            $updateAttempted = true;
    
            // Récupération du mot de passe hashé de l'utilisateur
            $query = "SELECT * FROM utilisateur WHERE id_utilisateur = $userId";
            $result = $this->SQLconn->executeRequete($query);
            $row = $result->fetch_assoc();
            $passwordHash = $row['mdp'];
    
            // Vérification si le mot de passe actuel correspond
            if (!password_verify($_POST["mdp"], $passwordHash)){
                $error = "Mot de passe incorrect";
            }
            // Vérification si les nouveaux mots de passe correspondent
            else if ($_POST["newmdp1"] != $_POST["newmdp2"]){
                $error = "Les nouveaux mots de passe ne correspondent pas";
            }
            // Vérification de la longueur du nouveau mot de passe
            else if (strlen($_POST["newmdp1"]) < 2){
                $error = "Mot de passe trop court";
            }
            else {
                // Hashage du nouveau mot de passe et mise à jour dans la base de données
                $newPassword = password_hash($_POST["newmdp1"], PASSWORD_DEFAULT);
                $query = "UPDATE utilisateur SET mdp = '$newPassword' WHERE id_utilisateur = $userId";
                $result = $this->SQLconn->executeRequete($query);
                if ($result === TRUE) {
                    $updateSuccessful = true;
                    $_COOKIE['password'] = $_POST["newmdp1"];
                } else {
                    $error = "Erreur lors de l'insertion SQL: " . $conn->error;
                }
            }
        }
    
        // Retourner le résultat de la tentative de changement de mot de passe
        $resultArray = ['Attempted' => $updateAttempted, 
                        'Successful' => $updateSuccessful, 
                        'ErrorMessage' => $error];
    
        return $resultArray;
    }

    // Méthode pour suivre un utilisateur
    public function follow($userId, $userIdToFollow){
    
        $query = "INSERT INTO follower (id_utilisateur, id_utilisateur_suivi) VALUES ($userId, $userIdToFollow)";
        if ($this->SQLconn->executeRequete($query)) {
            return true;
        } else {
            return false;
        }
    }
    
    // Méthode pour ne plus suivre un utilisateur
    public function unfollow($userId, $userIdToUnfollow){
    
        $query = "DELETE FROM follower WHERE id_utilisateur = $userId AND id_utilisateur_suivi = $userIdToUnfollow";
        if ($this->SQLconn->executeRequete($query)) {
            return true;
        } else {
            return false;
        }
    }

    // Méthode pour obtenir le nombre total d'abonnés
    public function totalFollowers($userId){
        global $conn;
    
        $query = "SELECT * FROM follower WHERE id_utilisateur_suivi = $userId";
        $result = $this->SQLconn->executeRequete($query);
        return $result->num_rows;
    }

    // Méthode pour obtenir le nombre total d'abonnements
    public function totalFollowing($userId){
        global $conn;
    
        $query = "SELECT * FROM follower WHERE id_utilisateur = $userId";
        $result = $this->SQLconn->executeRequete($query);
        return $result->num_rows;
    }

    // Méthode pour obtenir les prochains post de l'utilisateur
    public function GetNextPosts($userId, $start, $combien){

        $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path,post.video_lien, post.date, post.visibilite, utilisateur.nom, utilisateur.prenom FROM post
                  INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur AND post.id_utilisateur = $userId
                  ORDER BY post.date DESC
                  LIMIT  $combien OFFSET $start";
    
        $result = $this->SQLconn->executeRequete($query);
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $post = [
                'id' => $row['id_post'],
                'id_utilisateur' => $row['id_utilisateur'],
                'contenu' => $row['contenu'],
                'image' => $row['image_path'],
                'video_lien' => $row['video_lien'],
                'date' => $row['date'],
                'nom_utilisateur' => $row['nom'],
                'prenom_utilisateur' => $row['prenom'],
                'visibilite' => $row['visibilite']
            ];
            $posts[] = $post;
        }
    
        return $posts;
    }

    // Récupère les prochaines réponses d'un post spécifique
    public function GetNextReponse($postId, $start, $combien){
        // Requête SQL pour récupérer les informations des réponses
        $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path, post.video_lien, post.date, post.visibilite, utilisateur.nom, utilisateur.prenom 
                  FROM post
                  INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur AND post.id_parent = $postId
                  ORDER BY post.date DESC
                  LIMIT  $combien OFFSET $start";
        
        // Exécution de la requête SQL
        $result = $this->SQLconn->executeRequete($query);
        
        // Initialisation du tableau pour stocker les réponses
        $posts = [];
        
        // Parcours des résultats de la requête et construction du tableau de réponses
        while ($row = $result->fetch_assoc()) {
            $post = [
                'id' => $row['id_post'],
                'id_utilisateur' => $row['id_utilisateur'],
                'contenu' => $row['contenu'],
                'image' => $row['image_path'],
                'video_lien' => $row['video_lien'],
                'date' => $row['date'],
                'nom_utilisateur' => $row['nom'],
                'prenom_utilisateur' => $row['prenom'],
                'visibilite' => $row['visibilite']
            ];
            // Ajout de chaque réponse au tableau
            $posts[] = $post;
        }
        
        // Retourne le tableau contenant les réponses
        return $posts;
    }

    // Convertit une URL YouTube en URL d'intégration pour afficher la vidéo
    public function getYoutubeEmbedUrl($url){
        // Expression régulière pour correspondre aux URL courtes et longues de YouTube
        $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
    
        // Recherche de l'ID de la vidéo dans l'URL
        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
    
        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        
        // Construction de l'URL d'intégration avec l'ID de la vidéo
        return 'https://www.youtube.com/embed/' . $youtube_id ;
    }

    // Vérifie si un post est aimé par un utilisateur donné
    public function isLiked($userId, $postId){
        // Requête SQL pour vérifier si un post est aimé par un utilisateur
        $query = "SELECT * FROM likes WHERE id_utilisateur = $userId AND id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        
        // Vérification du nombre de lignes retournées
        if ($result->num_rows > 0) {
            return 1; // Le post est aimé par l'utilisateur
        } else {
            return 0; // Le post n'est pas aimé par l'utilisateur
        }
    }

    // Retourne le nombre de likes d'un post spécifique
    public function getNumberLikes($postId){
        // Requête SQL pour compter le nombre de likes d'un post
        $query= "SELECT COUNT(*) FROM likes WHERE id_post=$postId";
        $result = $this->SQLconn->executeRequete($query);
        
        // Extraction du résultat
        $row = $result->fetch_assoc();
        
        // Retourne le nombre de likes
        return $row['COUNT(*)'];
    }

    public function addLike($postId, $userId){ //fonction pour ajouter un like à un post
        $query = "INSERT INTO likes (id_likes, id_utilisateur, id_post) VALUES (NULL, $userId, $postId)";
        if ($this->SQLconn->executeRequete($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function removeLike($postId, $userId){ //fonction pour retirer un like à un post
        $query = "DELETE FROM likes WHERE id_utilisateur = $userId AND id_post = $postId";
        if ($this->SQLconn->executeRequete($query)) {
            return true;
        } else {
            return false;
        }
    }


    public function afficherPosts($post, $infos){
        $idPost = $post['id'];
        // Vérifie si l'utilisateur est connecté et récupère ses informations
        if (isset($_COOKIE['user_id'])){
            $infoUser1 = $this->GetInfoProfile($_COOKIE['user_id']);
        }
        // Vérifie la visibilité du post et les permissions d'affichage pour l'utilisateur
        if ($post['visibilite']!="offensant" || (isset($_COOKIE['user_id']) && $infos['id_utilisateur'] == $_COOKIE['user_id']) || (isset($infoUser1) && $infoUser1['admin'] == 1)){
            
            echo "<div class='card outline-secondary rounded-3' id='post_".$idPost."'>";
            echo    "<div class='card-header outline-secondary'>";
            echo        "<div class='row'>";
            echo            "<div class='col text-start'>";
            // Lien vers le profil de l'utilisateur qui a publié le post
            echo                "<a class='nav-link active' aria-current='page' href='./profile.php?id=".$infos["id_utilisateur"]."'> 
                                    <img src='".$infos["avatar"]."' class='avatar avatar-lg'>
                                    <label for='nom'>". $infos["username"]."</label>";
                                    // Affiche une icône admin si l'utilisateur est un administrateur
                                    if ($infos['admin'] == 1){
                                        echo "<img src='./images/admin.jpg' class='avatar avatar-xs'>";
                                    }
                                    // Affiche une icône de bannissement si l'utilisateur est banni
                                    if ($infos['ban'] == 1){
                                        echo "<img src='./images/ban.png' class='avatar avatar-xs'>";
                                    }
    
            echo                  " </a>";
            echo            "</div>";
            echo            "<div class='col text-end'>";
            if (isset($_COOKIE['user_id'])){
                $infoUser = $this->GetInfoProfile($_COOKIE['user_id']);
                // Affiche un menu d'administration pour les administrateurs
                if ($infoUser['admin'] == 1){
                    echo "<div class='dropdown'>
                            <button class='btn btn-secondary dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                Menu admin
                            </button>
                            <ul class='dropdown-menu'>
                                <li><button class='dropdown-item' id='sendAvertissement_".$idPost."' onclick='sendAvertissement($idPost)'>Envoyer un avertissement</button></li>";
                    // Affiche les options de modération en fonction de la visibilité du post
                    if ($post['visibilite']=="public"){
                        echo       "<li><button class='dropdown-item' id='retirerPost_".$idPost."' onclick='retirerPost($idPost)'>Retirer Post</button></li>
                                    <li><button class='dropdown-item' id='marquerSensible".$idPost."' onclick='marquerSensible($idPost)'>Marquer comme sensible</button></li>
                            </ul>
                        </div>";
                    } else if ($post['visibilite']=="sensible"){
                        echo       "<li><button class='dropdown-item' id='retirerPost_".$idPost."' onclick='retirerPost($idPost)'>Retirer Post</button></li>
                                    <li><button class='dropdown-item' id='marquerNonSensible".$idPost."' onclick='marquerNonSensible($idPost)'>Marquer comme non sensible</button></li>
                            </ul>
                        </div>";
                    } else if ($post['visibilite']=="offensant"){
                        echo       "<li><button class='dropdown-item' id='marquerNonOffensant".$idPost."' onclick='marquerNonOffensant($idPost)'>Marquer comme non offensant</button></li>
                            </ul>
                        </div>";
                    }
                }else{
                    echo "<div class='dropdown'>
                            <button class='btn btn-secondary dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                <img src='./icon/dots.png' alt='Menu' class='dropdown-image' width='20' height='20'>
                            </button>
                            <ul class='dropdown-menu'>
                                <li><button class='dropdown-item' id='signaler_".$idPost."' onclick='signalerPost($idPost,".$infos["id_utilisateur"].")'>Signaler le post</button></li>
                            </ul>
                        </div>";

                }
                // Affiche le bouton de suppression pour l'utilisateur qui a publié le post
                if ($infos['id_utilisateur'] == $_COOKIE['user_id']){
                    echo           "<button class='btn btn-outline-secondary' id='supprimerPost_".$idPost."' data-bs-toggle='modal' data-bs-target='#supprimerPostModal_".$idPost."'>Supprimer</button>";
                }
            }
            
            echo            "</div>";
            // Modal de confirmation de suppression du post
            echo            "<div class='modal fade' id='supprimerPostModal_".$idPost."' tabindex='-1' aria-labelledby='supprimerPostModalLabel_".$idPost."' aria-hidden='true'>";
            echo                "<div class='modal-dialog modal-dialog-centered'>";
            echo                    "<div class='modal-content'>";
            echo                        "<div class='modal-header'>";
            echo                            "<h5 class='modal-title' id='supprimerPostModalLabel_".$idPost."'>Supprimer le post</h5>";
            echo                            "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo                        "</div>";
            echo                        "<div class='modal-body text-center'>";
            echo                            "<p>Êtes-vous sûr de vouloir supprimer ce post?</p>";
            echo                        "</div>";
            echo                        "<div class='modal-footer'>";
            echo                            "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Annuler</button>";
            echo                            "<button type='button' class='btn btn-danger' data-bs-dismiss='modal' onclick='supprimerPost($idPost)' id='supprimerButton_".$idPost."'>Supprimer</button>";
            echo                        "</div>";
            echo                    "</div>";
            echo                "</div>";
            echo            "</div>";
            echo        "</div>";
            echo    "</div>";
    
            // Affichage du contenu du post
            if ($post['visibilite']=="sensible"){
                // Affiche un bouton pour afficher un post sensible
                echo "<div class='text-center'>";
                echo "<p>Ce post a été classé comme sensible, voulez vous le voir malgré tout?</p>";
                echo "</div>";
                echo "<button class='btn btn-outline-secondary' id='voirSensible".$idPost."' onclick='toggleVisibilitePostSensible($idPost)'>Voir</button>";
                echo    "<div class='card-body text-center' onclick='sendTo($idPost)' id='postSensible_".$idPost."' style = 'filter: blur(15px);'>";
            } else {
                echo    "<div class='card-body text-center' onclick='sendTo($idPost)'>";
            }
            if ($post['visibilite']=="offensant"){
                // Affiche un message pour un post classé comme offensant et supprimé
                echo "<div class='alert alert-danger' role='alert'>";
                echo "<p>Post classé comme offensant et a été supprimer</p>";
                echo "</div>";
            }
    
            // Affiche l'image du post s'il en existe une
            if (!empty($post['image'])) {
                echo "<img src='{$post['image']}' class='img-fluid'>";
            }
            echo        "<p class='card-text'>".$post["contenu"]."</p>";
            // Affiche la vidéo du post si elle existe
            if (!empty($post['video_lien'])) {
                $videoEmbed = $this->getYoutubeEmbedUrl($post['video_lien']);
                
                $videoEmbedDisplay = '<iframe src="'.$videoEmbed.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                echo "<p class='card-text'>".$videoEmbedDisplay."</p>";
                echo "<br>";
            }
            echo "<br>";

            

            if (isset($_COOKIE['user_id'])){
                $currentUser = $_COOKIE['user_id'];
                echo    "</div>";
                echo    "<div class='card-footer'>";
                echo        "<div class='row'>";

                echo            "<div class='col' onclick='toggleForm($idPost)'>";
                
                // Bouton pour masquer/afficher le formulaire avec ID de post
                echo                "<img src='./images/comment.png' alt='Comment' class='like-button'  style='max-width: 1em; max-height: 6em;'>";
                $nbCommentaires = $this->getNombreCommentaires($idPost);
                echo               "<label for='commentaire'>". $nbCommentaires."</label>";
                echo            "</div>";
                //partie des likes
                $likesAmount = $this->getNumberLikes($post["id"]); //récupère le nb de likes du post
                $estLike = 0;
                if (isset($_COOKIE['user_id'])){
                    $estLike = $this->isLiked($_COOKIE['user_id'], $post["id"]); //vérifie si l'utilisateur a liké le post
                }
                echo            "<div class='col text-start' onclick=\"toggleLike($currentUser, $idPost)\" data-liked='$estLike' id='like-button_".$idPost."'>";
                if ($estLike) {
                    echo "<img id='like-image_".$idPost."' src='./icon/heart_red.png' alt='Like' width='20' height='20'>";
                } else {
                    echo "<img id='like-image_".$idPost."' src='./icon/heart_empty.png' alt='Like' width='20' height='20'>";
                }
                echo        "<label for='like' id='like-count_".$idPost."'>$likesAmount</label>";

                echo            "</div>";
                echo            "<div class='col text-end'>";
                echo                "<label for='date'>". $post["date"]."</label>";
                echo            "</div>";
                echo        "</div>";
                echo        "<br>";
                echo        "<br>";
            }
            
        
            // Formulaire avec ID de post
            echo        "<form id='postForm_{$idPost}' action='#' method='post' class='mb-4' enctype='multipart/form-data' style='display: none;'>";
            echo            "<div class='form-group form-field'>";
            if (isset($erreurPost)) {
                echo            "<div class='alert alert-danger' role='alert'>";
                echo $erreurPost;
                echo            "</div>";
            }
            echo                "<label for='commentaire'>Commentaire:</label>";
            echo                "<textarea name='commentaire' class='form-control' rows='3'  required></textarea>";
            echo            "</div>";
            echo            "<div class='form-group form-field'>";
            echo               "<div class='form-check'>";
            echo                    "<input class='form-check-input' type='radio' name='typeMedia' value='image' id='imageRadio_".$idPost."' onclick='toggleImageVideo($idPost)' checked>";
            echo                    "<label class='form-check-label' for='imageRadio_".$idPost."'>Image</label>";
            echo                "</div>";
            echo                "<div class='form-check'>";
            echo                    "<input class='form-check-input' type='radio' name='typeMedia' value='video' id='videoRadio_".$idPost."' onclick='toggleImageVideo($idPost)'>";
            echo                    "<label class='form-check-label' for='videoRadio_".$idPost."'>Vidéo</label>";
            echo                "</div>";
            echo            "</div>";
            echo            "<div class='imageField' id='imageField_".$idPost."'>";
            echo                "<div class='form-group form-field'>";
            echo                    "<label for='image'>Image:</label>";
            echo                    "<input type='file' name='image' class='form-control' accept='image/*' >";
            echo                "</div>";
            echo            "</div>";
            echo            "<div class='videoField' id='videoField_".$idPost."' style='display: none;'>";
            echo                "<div class='form-group form-field'>";
            echo                    "<label for='video'>Lien de la vidéo:</label>";
            echo                    "<input type='text' name='video' class='form-control'>";
            echo                "</div>";
            echo            "</div>";
            echo            "<div class='form-group text-center'>";
            echo                "<input type='hidden' name='idPost' value='$idPost'>";
            echo                "<input type='hidden' name='submitReponse' value='true'>";
            echo                "<br>";
            echo                "<button type='submit' class='btn btn-outline-secondary'>Valider</button>";
            echo            "</div>";
            echo        "</form>";  
            echo    "</div>";
            echo "</div>";
            echo "<br>";
        }
    }

    // Récupère le nombre de commentaires pour un post donné.
    public function getNombreCommentaires($postId){
        $query = "SELECT COUNT(*) FROM post WHERE id_parent = $postId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'];
    }
    
    // Ajoute un nouveau post dans la base de données, y compris le traitement des données envoyées par le formulaire
    public function ajouterNewPost($userId, $parentId = null){
        global $conn;
    
        $ajouterPosttry = false;
        $ajouterPost = false;
        $error = NULL;
        $imagePathForDB = "";
    
        // Vérifie si le formulaire de réponse a été soumis
        if ($_POST["submitReponse"]){
            $ajouterPosttry = true;
            // Récupère et sécurise les données du formulaire
            $commentaire = $this->SQLconn->SecurizeString_ForSQL($_POST["commentaire"]);
            $video = $this->SQLconn->SecurizeString_ForSQL($_POST["video"]);
    
            // Construit la requête d'insertion en fonction de la présence d'un post parent et d'une vidéo
            if ($parentId === null) {
                if ($video != "") {
                    $query = "INSERT INTO post (id_utilisateur, contenu, video_lien) VALUES ($userId, '$commentaire', '$video')";
                } else {
                    $query = "INSERT INTO post (id_utilisateur, contenu) VALUES ($userId, '$commentaire')";
                }
            } else {
                if ($video != "") {
                    $query = "INSERT INTO post (id_utilisateur, contenu, id_parent, video_lien) VALUES ($userId, '$commentaire', $parentId, '$video')";
                } else {
                    $query = "INSERT INTO post (id_utilisateur, contenu, id_parent) VALUES ($userId, '$commentaire', $parentId)";
                }
            }
            // Exécute la requête d'insertion dans la base de données
            if (mysqli_query($conn, $query)) {
                $postId = mysqli_insert_id($conn);
    
                // Gère le téléchargement et le traitement des images
                if ($_FILES['image']["size"] > 0){
                    $image = $_FILES["image"];
    
                    $file = $_FILES['image']['name'];
                    $path = pathinfo($file); 
                    $ext = $path['extension'];
    
                    $path_filename_ext = "./images/".$postId.".".$ext;
                    $imagePathForDB = $this->SQLconn->SecurizeString_ForSQL($path_filename_ext);
                    $uploadOk = 1;
                    $check = getimagesize($image["tmp_name"]);
    
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $error = "Le fichier n'est pas une image.";
                        $uploadOk = 0;
                    }
                    if ($image["size"] > 5500000) {
                        $error = "L'image est trop grande.";
                        $uploadOk = 0;
                    }
                    if($_FILES['image']['type'] != "image/jpeg" && $_FILES['image']['type'] != "image/png" && $_FILES['image']['type'] != "image/jpg" && $_FILES['image']['type'] != "image/gif"){
                        $error = "Seuls les fichiers JPG, JPEG, PNG sont autorisés.";
                        $uploadOk = 0;
                    }
                    if ($uploadOk != 0) {
                        if ($ext==='gif'){
                            if (move_uploaded_file($image["tmp_name"], $path_filename_ext)) {
                                $ajouterPost = true;
                                $query = "UPDATE post SET image_path = '$imagePathForDB' WHERE id_post = $postId";
                                if (!mysqli_query($conn, $query)) {
                                    $error = "Erreur lors de la mise à jour du chemin de l'image dans la base de données.";
                                }
                            } else {
                                $error = "Erreur lors de l'upload de l'image.";
                            }
                        }else{
                            list($width, $height) = getimagesize($image["tmp_name"]);
                            $goalwidth = 400;
                            $ratio = $goalwidth / $width;
                            $newheight = $height * $ratio;
    
                            $src = imagecreatefromstring(file_get_contents($image["tmp_name"]));
                            $dst = imagecreatetruecolor($goalwidth, $newheight);
                            imagecopyresampled($dst, $src, 0, 0, 0, 0, $goalwidth, $newheight, $width, $height);
                            imagedestroy($src);
    
                            if (imagejpeg($dst, $path_filename_ext, 150)) {
                                imagedestroy($dst);
                                $query = "UPDATE post SET image_path = '$imagePathForDB' WHERE id_post = $postId";
                                if (!mysqli_query($conn, $query)) {
                                    $error = "Erreur lors de la mise à jour du chemin de l'image dans la base de données.";
                                }else {
                                    $ajouterPost = true;
                                }
                            } else {
                                $error = "Erreur lors de l'upload de l'image.";
                            }
                        }
                    }else{
                        $query = "DELETE FROM post WHERE id_post = $postId";
                        if (!mysqli_query($conn, $query)) {
                            $error = "Erreur lors de la suppression du post dans la base de données.";
                        }
                    }
                }else{
                    $ajouterPost = true;
                }
            } else {
                $error = "Erreur lors de l'insertion dans la base de données: " . mysqli_error($conn);
            }
        }
    
        // Renvoie un  tableau de valeur
        $resultArray = ['Attempted' => $ajouterPosttry, 
                        'Successful' => $ajouterPost,
                        'ErrorMessage' => $error,
                        'idPost' => $postId,];
    
        return $resultArray;
    }

    // Récupère les meilleurs posts (posts publiés par d'autres utilisateurs) pour un utilisateur donné.
    public function getBestPosts($userId){
        global $conn;
    
        // Requête pour récupérer les meilleurs posts publiés par d'autres utilisateurs, triés par date décroissante
        $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path, post.date, post.visibilite, utilisateur.nom, utilisateur.prenom FROM post
                  INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur AND post.id_utilisateur != $userId
                  ORDER BY post.date DESC";
        $result = $this->SQLconn->executeRequete($query);
    
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            // Crée un tableau pour chaque post avec ses informations
            $post = [
                'id' => $row['id_post'],
                'id_utilisateur' => $row['id_utilisateur'],
                'contenu' => $row['contenu'],
                'image' => $row['image_path'],
                'date' => $row['date'],
                'nom_utilisateur' => $row['nom'],
                'prenom_utilisateur' => $row['prenom'],
                'visibilite' => $row['visibilite']
            ];
            // Ajoute le post au tableau des meilleurs posts
            $posts[] = $post;
        }
    
        return $posts;
    }

    // Récupère les posts récents des utilisateurs suivis par un utilisateur donné.
    public function getRecentPostsFollowed($userId){
        global $conn;
    
        // Requête pour récupérer les posts récents des utilisateurs suivis par l'utilisateur spécifié, triés par date décroissante
        $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path, post.date, post.visibilite, utilisateur.nom, utilisateur.prenom FROM post
                  INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur
                  WHERE post.id_utilisateur IN (SELECT id_utilisateur_suivi FROM follower WHERE id_utilisateur = $userId)
                  ORDER BY post.date DESC";
        $result = $this->SQLconn->executeRequete($query);
    
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            // Crée un tableau pour chaque post avec ses informations
            $post = [
                'id' => $row['id_post'],
                'id_utilisateur' => $row['id_utilisateur'],
                'contenu' => $row['contenu'],
                'image' => $row['image_path'],
                'date' => $row['date'],
                'nom_utilisateur' => $row['nom'],
                'prenom_utilisateur' => $row['prenom'],
                'visibilite' => $row['visibilite']
            ];
            // Ajoute le post au tableau des posts récents des utilisateurs suivis
            $posts[] = $post;
        }
    
        return $posts;
    }

    // Vérifie si un utilisateur suit un autre utilisateur
    public function verifFollow($userId, $userIdToFollow){
        global $conn;
    
        // Requête pour vérifier si l'utilisateur suit l'autre utilisateur
        $query = "SELECT * FROM follower WHERE id_utilisateur = $userId AND id_utilisateur_suivi = $userIdToFollow";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Récupère les followers d'un utilisateur donné
    public function getFollowers($userId){
        // Requête pour récupérer les followers d'un utilisateur
        $query = "SELECT utilisateur.dateNaissance, utilisateur.avatar, utilisateur.username, utilisateur.description, 
        utilisateur.id_utilisateur FROM utilisateur INNER JOIN follower ON utilisateur.id_utilisateur = follower.id_utilisateur WHERE id_utilisateur_suivi = $userId";
        $result = $this->SQLconn->executeRequete($query);
    
        $followers = [];
        while ($row = $result->fetch_assoc()) {
            // Crée un tableau pour chaque follower avec ses informations
            $follower = [
                'dateNaissance' => $row['dateNaissance'],
                'avatar' => $row['avatar'],
                'username' => $row['username'],
                'description' => $row['description'],
                'id_utilisateur' => $row['id_utilisateur']
            ];
            // Ajoute le follower au tableau des followers
            $followers[] = $follower;
        }
    
        return $followers;
    }
    
    // Récupère les utilisateurs suivis par un utilisateur donné
    public function getFollowed($userId){
        // Requête pour récupérer les utilisateurs suivis par un utilisateur
        $query = "SELECT utilisateur.dateNaissance, utilisateur.avatar, utilisateur.username, utilisateur.description, 
                    utilisateur.id_utilisateur FROM utilisateur INNER JOIN follower ON utilisateur.id_utilisateur = follower.id_utilisateur_suivi WHERE follower.id_utilisateur = $userId";
        $result = $this->SQLconn->executeRequete($query);
    
        $following = [];
        while ($row = $result->fetch_assoc()) {
            // Crée un tableau pour chaque utilisateur suivi avec ses informations
            $follow = [
                'dateNaissance' => $row['dateNaissance'],
                'avatar' => $row['avatar'],
                'username' => $row['username'],
                'description' => $row['description'],
                'id_utilisateur' => $row['id_utilisateur']
            ];
            // Ajoute l'utilisateur suivi au tableau des utilisateurs suivis
            $following[] = $follow;
        }
    
        return $following;
    }

    // Récupère un post à partir de son identifiant
    public function GetPostById($id){
    
        // Requête pour récupérer un post à partir de son identifiant
        $query = "SELECT * FROM post WHERE id_post = $id";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $reponse = [
            'id' => $row['id_post'],
            'contenu' => $row['contenu'],
            'image' => $row['image_path'],
            'date' => $row['date'],
            'id_utilisateur' => $row['id_utilisateur'],
            'video_lien' => $row['video_lien'],
            'visibilite' => $row['visibilite']
        ];
    
        return $reponse;
    }

    // Récupère les réponses à un commentaire donné
    public function getReponsesCommentaire($idPost){
        global $conn;
    
        // Requête pour récupérer les réponses à un commentaire donné
        $query = "SELECT * FROM post WHERE id_parent = $idPost";
        $result = $this->SQLconn->executeRequete($query);
    
        $reponses = [];
        while ($row = $result->fetch_assoc()) {
            // Crée un tableau pour chaque réponse avec ses informations
            $reponse = [
                'id' => $row['id_post'],
                'contenu' => $row['contenu'],
                'image' => $row['image_path'],
                'date' => $row['date'],
                'id_utilisateur' => $row['id_utilisateur'],
                'video_lien' => $row['video_lien'],
                'visibilite' => $row['visibilite']
            ];
            // Ajoute la réponse au tableau des réponses au commentaire
            $reponses[] = $reponse;
        }
    
        return $reponses;
    }

    // Supprime un post donné
    public function deletePost($id){

        // Requête pour vérifier l'existence du post à supprimer
        $query = "SELECT * FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Vérification des autorisations de suppression du post
        $infoUser = $this->GetInfoProfile($_COOKIE['user_id']);
        if ($infoUser['admin'] == 0){
            if ($result["id_utilisateur"] != $_COOKIE["user_id"]){
                return false;
            }
        }  
    
        // Suppression de l'image associée au post s'il en existe une
        if ($result["image_path"] != ""){
            unlink("../".$result["image_path"]);
        }
    
        // Requête pour supprimer le post
        $query = "DELETE FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        // Retourne true si la suppression est réussie, sinon false
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    // Bannit un utilisateur donné
    public function banDef($id, $raison){   
        // Requête pour vérifier l'existence de l'utilisateur à bannir
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Vérification des autorisations de bannissement de l'utilisateur
        if ($result["admin"] == 1){
            return false;
        }
    
        // Requête pour bannir l'utilisateur
        $query = "UPDATE `utilisateur` SET `ban` = 1, `date_fin_ban` = NULL , `justification_ban` = '$raison' WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        // Retourne true si le bannissement est réussi, sinon false
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    // Vérifie si l'utilisateur est banni et enlève le ban si la date de fin est passée
    public function checkBan($id){ 
        // Requête pour vérifier si l'utilisateur est banni
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Vérifie si l'utilisateur est banni en fonction de la valeur de la colonne "ban"
        if ($result["ban"] == 1){
            // Si l'utilisateur est banni de façon temporaire, vérifie si la date de fin du bannissement est dépassée
            if ($result["date_fin_ban"] == NULL){
                return true;
            }
            if ($result["date_fin_ban"] < date("Y-m-d H:i:s")){
                // Si la date de fin est dépassée, lève le bannissement
                $query = "UPDATE `utilisateur` SET `ban` = 0, `date_fin_ban` = NULL , `justification_ban` = NULL WHERE `id_utilisateur` = $id";
                $this->SQLconn->executeRequete($query);
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    // Vérifie si un utilisateur est administrateur
    public function checkAdmin($id){
        // Requête pour vérifier si l'utilisateur est administrateur
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Vérifie si l'utilisateur est administrateur en fonction de la valeur de la colonne "admin"
        if ($result["admin"] == 1){
            return true;
        }else{
            return false;
        }
    }

    // Bannit temporairement un utilisateur
    public function banTemp($id, $time, $raison){
        // Requête pour vérifier l'existence de l'utilisateur à bannir
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Vérification des autorisations de bannissement temporaire de l'utilisateur
        if ($result["admin"] == 1){
            return false;
        }
    
        // Requête pour bannir temporairement l'utilisateur
        $query = "UPDATE `utilisateur` SET `ban` = 1, `date_fin_ban` = '$time', `justification_ban` = '$raison' WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        // Retourne true si le bannissement temporaire est réussi, sinon false
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    // Obtient la date de fin du bannissement d'un utilisateur
    public function getDateBan($id){
        // Requête pour obtenir les informations de l'utilisateur
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Vérifie si l'utilisateur est banni et retourne la date de fin du bannissement le cas échéant
        if ($result["ban"] == 1){
            return $result["date_fin_ban"];
        }else{
            return false;
        }
    }

    // Débannit un utilisateur
    public function unban($id){
        // Requête pour obtenir les informations de l'utilisateur
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Vérifie si l'utilisateur est administrateur avant de le débannir
        if ($result["admin"] == 1){
            return false;
        }
    
        // Requête pour débannir l'utilisateur
        $query = "UPDATE `utilisateur` SET `ban` = 0, `date_fin_ban` = NULL, `justification_ban` = NULL WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        // Retourne true si l'utilisateur est débanni avec succès, sinon false
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    // Obtient la justification du bannissement d'un utilisateur
    public function getJustificationBan($id){
        // Requête pour obtenir les informations de l'utilisateur
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
        return $result;
    }

    // Marque un post comme sensible
    public function marquerSensible($id){
        // Requête pour obtenir les informations du post
        $query = "SELECT * FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Requête pour marquer le post comme sensible
        $query = "UPDATE `post` SET `visibilite` = 'sensible' WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        // Retourne true si le post est marqué comme sensible avec succès, sinon false
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    // Marque un post offensant
    public function retirerPost($id){
        // Requête pour obtenir les informations du post
        $query = "SELECT * FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Requete pour marquer le post offensant
        $query = "UPDATE `post` SET `visibilite` = 'offensant' WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        // Retourne true si le post est marqué offensant, sinon false
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    // Remet un post dans le statut public après avoir été marqué comme offensant
    public function remettrePost($id){
        // Requête pour obtenir les informations du post
        $query = "SELECT * FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Requête pour remettre le post dans le statut public
        $query = "UPDATE `post` SET `visibilite` = 'public' WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        // Retourne true si le post est remis dans le statut public avec succès, sinon false
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    // Enlève le statut de sensible d'un post
    public function enleverMarqueSensible($id){
        // Requête pour obtenir les informations du post
        $query = "SELECT * FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        // Requête pour enlever le statut de sensible du post
        $query = "UPDATE `post` SET `visibilite` = 'public' WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        // Retourne true si le statut de sensible est enlevé avec succès, sinon false
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    

}

?>



