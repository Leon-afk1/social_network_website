<?php

class profile {
    private $SQLconn;

    public function __construct(ConnexionBDD $SQLconn) {
        $this->SQLconn = $SQLconn;
    }

    public function GetInfoProfile($userId) {
        $query = "SELECT * FROM utilisateur WHERE id_utilisateur = $userId";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
    
        return $row;
    }
    
    public function UpdateInfosProfile($userId){
        global $conn;
    
        $updateAttempted = false;
        $updateSuccessful = false;
        $error = NULL;
        
        if ($_POST["submitModification"]){
            $updateAttempted = true;
    
            if (strlen($_POST["nom"]) < 2){
                $error = "Nom trop court";
            }
            else if (strlen($_POST["prenom"]) < 2){
                $error = "Prénom trop court";
            }
            else if (strlen($_POST["username"]) < 2){
                $error = "Nom d'utilisateur trop court";
            }
            else if (strlen($_POST["email"]) < 2){
                $error = "Email trop court";
            }
            else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                $error = "Email invalide";
            }
            else if (strlen($_POST["adresse"]) < 5){
                $error = "Adresse trop courte";
            }
    
            // else if (!checkAge($_POST["date_naissance"])){
            //     $error = "Vous devez avoir au moins 18 ans pour vous inscrire";
            // }       
            else {
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
            $resultArray = ['Attempted' => $updateAttempted, 
                            'Successful' => $updateSuccessful, 
                            'ErrorMessage' => $error];
    
            return $resultArray;
        }
    }

    public function UpdateAvatar($userId){
        global $conn;
    
        $updateAttempted = false;
        $updateSuccessful = false;
        $error = NULL;
        
        if ($_POST["submitModification"]){
            $updateAttempted = true;
    
            if ($_FILES['avatar']["size"] == 0){
                $error = "Veuillez choisir une image";
            }
            else {
                $avatar = $_FILES["avatar"];
                $avatarPath = "./avatar/" . $userId . ".jpg";
                $avatarPath = $this->SQLconn->SecurizeString_ForSQL($avatarPath);
    
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
    
        $resultArray = ['Attempted' => $updateAttempted, 
                        'Successful' => $updateSuccessful,
                        'ErrorMessage' => $error];
    
        return $resultArray;
    
    }

    public function changermdp($userId){
        global $conn;
    
        $updateAttempted = false;
        $updateSuccessful = false;
        $error = NULL;
        
        if ($_POST["submitModificationMdp"]){
            $updateAttempted = true;
    
            $query = "SELECT * FROM utilisateur WHERE id_utilisateur = $userId";
            $result = $this->SQLconn->executeRequete($query);
            $row = $result->fetch_assoc();
            $passwordHash = $row['mdp'];
    
            if (!password_verify($_POST["mdp"], $passwordHash)){
                $error = "Mot de passe incorrect";
            }
            else if ($_POST["newmdp1"] != $_POST["newmdp2"]){
                $error = "Les nouveaux mots de passe ne correspondent pas";
            }
            else if (strlen($_POST["newmdp1"]) < 2){
                $error = "Mot de passe trop court";
            }
            else {
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
    
        $resultArray = ['Attempted' => $updateAttempted, 
                        'Successful' => $updateSuccessful, 
                        'ErrorMessage' => $error];
    
        return $resultArray;
    }

    public function follow($userId, $userIdToFollow){
    
        $query = "INSERT INTO follower (id_utilisateur, id_utilisateur_suivi) VALUES ($userId, $userIdToFollow)";
        if ($this->SQLconn->executeRequete($query)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function unfollow($userId, $userIdToUnfollow){
    
        $query = "DELETE FROM follower WHERE id_utilisateur = $userId AND id_utilisateur_suivi = $userIdToUnfollow";
        if ($this->SQLconn->executeRequete($query)) {
            return true;
        } else {
            return false;
        }
    }

    public function totalFollowers($userId){
        global $conn;
    
        $query = "SELECT * FROM follower WHERE id_utilisateur_suivi = $userId";
        $result = $this->SQLconn->executeRequete($query);
        return $result->num_rows;
    }

    public function totalFollowing($userId){
        global $conn;
    
        $query = "SELECT * FROM follower WHERE id_utilisateur = $userId";
        $result = $this->SQLconn->executeRequete($query);
        return $result->num_rows;
    }

    public function GetNextPosts($userId, $start, $combien){

        $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path,post.video_lien, post.date, utilisateur.nom, utilisateur.prenom FROM post
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
                'prenom_utilisateur' => $row['prenom']
            ];
            $posts[] = $post;
        }
    
        return $posts;
    }

    public function getYoutubeEmbedUrl($url){ // https://stackoverflow.com/questions/19050890/find-youtube-link-in-php-string-and-convert-it-into-embed-code
         $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
         $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
    
        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
    
        if (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        }
        return 'https://www.youtube.com/embed/' . $youtube_id ;
    }

    public function isLiked($userId, $postId){ //fonction pour vérifier si un post est liké par un utilisateur
        $query = "SELECT * FROM likes WHERE id_utilisateur = $userId AND id_post = $postId";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getNumberLikes($postId){ //fonction qui retourne le nombre de likes d'un post
        $query= "SELECT COUNT(*) FROM likes WHERE id_post=$postId";
        $result = $this->SQLconn->executeRequete($query);
        //var_dump($result); //pour visualiser le contenu de la variable
        $row = [];
        $row = $result->fetch_assoc();
        return $row['COUNT(*)'];
    }

    public function afficherPosts($post, $infos){
        $idPost = $post['id'];
        echo "<div class='card outline-secondary rounded-3' id='post_".$idPost."'>";
        echo    "<div class='card-header outline-secondary'>";
        echo        "<div class='row'>";
        echo            "<div class='col text-start'>";
        echo                "<a class='nav-link active' aria-current='page' href='./profile.php?id=".$infos["id_utilisateur"]."'> 
                                <img src='".$infos["avatar"]."' class='avatar avatar-lg'>
                                <label for='nom'>". $infos["username"]."</label>";
                                if ($infos['admin'] == 1){
                                    echo "<img src='./images/admin.jpg' class='avatar avatar-xs'>";
                                }
                                if ($infos['ban'] == 1){
                                    echo "<img src='./images/ban.png' class='avatar avatar-xs'>";
                                }

        echo                  " </a>";
        echo            "</div>";
        echo            "<div class='col text-end'>";
        if (isset($_COOKIE['user_id'])){
            $infoUser = $this->GetInfoProfile($_COOKIE['user_id']);
            if ($infoUser['admin'] == 1){
                echo           "<button class='btn btn-outline-secondary' id='supprimerPost_".$idPost."' data-bs-toggle='modal' data-bs-target='#supprimerPostModal_".$idPost."'>Supprimer</button>";
            }else if ($infos['id_utilisateur'] == $_COOKIE['user_id']){
                echo           "<button class='btn btn-outline-secondary' id='supprimerPost_".$idPost."' data-bs-toggle='modal' data-bs-target='#supprimerPostModal_".$idPost."'>Supprimer</button>";
            }
        }
        echo            "</div>";
        // modal pour supprimer post
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
        echo    "<div class='card-body text-center' onclick='sendTo($idPost)'>";
        if (!empty($post['image'])) {
            echo "<img src='{$post['image']}' class='img-fluid'>";
        }
        echo        "<p class='card-text'>".$post["contenu"]."</p>";
        if (!empty($post['video_lien'])) {
            $videoEmbed = $this->getYoutubeEmbedUrl($post['video_lien']);
            
            $videoEmbedDisplay = '<iframe src="'.$videoEmbed.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            echo "<p class='card-text'>".$videoEmbedDisplay."</p>";
            //echo $videoEmbedDisplay;
            echo "<br>";
        }
        echo "<br>";
        $likesAmount = $this->getNumberLikes($post["id"]); //récupère le nb de likes du post
        echo "<p class='card-text'>".$likesAmount." likes</p>";
        echo "<br>";
        echo "<br>";
        $estLike = false;
        if (isset($_COOKIE['user_id'])){
            $estLike = $this->isLiked($_COOKIE['user_id'], $post["id"]); //vérifie si l'utilisateur a liké le post
        }
        if($estLike == true){
            echo "post liké";
        }else{
            echo "post non liké";
        }
        // Intégration du bouton "like" dynamique en JavaScript avec des images
        if ($estLike) {
            echo "<img id='likeButton' onclick='toggleLike(this, {$post['id']})' src='./images/like.png' alt='Liked' class='like-button'>";
        } else {
            echo "<img id='likeButton' onclick='toggleLike(this, {$post['id']})' src='./images/nolike.png' alt='Not Liked' class='like-button'>";
        }
        
        if (isset($_COOKIE['user_id'])){
            echo    "</div>";
            echo    "<div class='card-footer'>";
    
            echo        "<div class='row'>";
            echo            "<div class='col'>";
            
            // // Bouton pour masquer/afficher le formulaire avec ID de post
            echo                "<button onclick='toggleForm($idPost)' class='btn btn-outline-secondary'>Réagir</button>";
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

    public function ajouterNewPost($userId, $parentId = null){
        global $conn;
    
        $ajouterPosttry = false;
        $ajouterPost = false;
        $error = NULL;
        $imagePathForDB = "";
    
        if ($_POST["submitReponse"]){
            $ajouterPosttry = true;
            $commentaire = $this->SQLconn->SecurizeString_ForSQL($_POST["commentaire"]);
            $video = $this->SQLconn->SecurizeString_ForSQL($_POST["video"]);
    
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
            if (mysqli_query($conn, $query)) {
                $postId = mysqli_insert_id($conn);
    
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
    
        $resultArray = ['Attempted' => $ajouterPosttry, 
                        'Successful' => $ajouterPost,
                        'ErrorMessage' => $error,
                        'idPost' => $postId,];
    
        return $resultArray;
    }

    public function getBestPosts($userId){
        global $conn;
    
        $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path, post.date, utilisateur.nom, utilisateur.prenom FROM post
                  INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur AND post.id_utilisateur != $userId
                  ORDER BY post.date DESC";
        $result = $this->SQLconn->executeRequete($query);
    
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $post = [
                'id' => $row['id_post'],
                'id_utilisateur' => $row['id_utilisateur'],
                'contenu' => $row['contenu'],
                'image' => $row['image_path'],
                'date' => $row['date'],
                'nom_utilisateur' => $row['nom'],
                'prenom_utilisateur' => $row['prenom']
            ];
            $posts[] = $post;
        }
    
        return $posts;
    }

    public function getRecentPostsFollowed($userId){
        global $conn;
    
        $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path, post.date, utilisateur.nom, utilisateur.prenom FROM post
                  INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur
                  WHERE post.id_utilisateur IN (SELECT id_utilisateur_suivi FROM follower WHERE id_utilisateur = $userId)
                  ORDER BY post.date DESC";
        $result = $this->SQLconn->executeRequete($query);
    
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $post = [
                'id' => $row['id_post'],
                'id_utilisateur' => $row['id_utilisateur'],
                'contenu' => $row['contenu'],
                'image' => $row['image_path'],
                'date' => $row['date'],
                'nom_utilisateur' => $row['nom'],
                'prenom_utilisateur' => $row['prenom']
            ];
            $posts[] = $post;
        }
    
        return $posts;
    }

    public function verifFollow($userId, $userIdToFollow){
        global $conn;
    
        $query = "SELECT * FROM follower WHERE id_utilisateur = $userId AND id_utilisateur_suivi = $userIdToFollow";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
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
        $result = $this->SQLconn->executeRequete($query);
    
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

    public function GetPostById($id){
        global $conn;
    
        $query = "SELECT * FROM post WHERE id_post = $id";
        $result = $this->SQLconn->executeRequete($query);
        $row = $result->fetch_assoc();
        $reponse = [
            'id' => $row['id_post'],
            'contenu' => $row['contenu'],
            'image' => $row['image_path'],
            'date' => $row['date'],
            'id_utilisateur' => $row['id_utilisateur']
        ];
    
        return $reponse;
    }

    public function getReponsesCommentaire($idPost){
        global $conn;
    
        $query = "SELECT * FROM post WHERE id_parent = $idPost";
        $result = $this->SQLconn->executeRequete($query);
    
        $reponses = [];
        while ($row = $result->fetch_assoc()) {
            $reponse = [
                'id' => $row['id_post'],
                'contenu' => $row['contenu'],
                'image' => $row['image_path'],
                'date' => $row['date'],
                'id_utilisateur' => $row['id_utilisateur'],
                'video_lien' => $row['video_lien']
            ];
            $reponses[] = $reponse;
        }
    
        return $reponses;
    }

    public function deletePost($id){

        $query = "SELECT * FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
    
        $infoUser = $this->GetInfoProfile($_COOKIE['user_id']);
        if ($infoUser['admin'] == 0){
            if ($result["id_utilisateur"] != $_COOKIE["user_id"]){
                return false;
            }
        }  
        
    
        if ($result["image_path"] != ""){
            unlink("../".$result["image_path"]);
        }
    
        $query = "DELETE FROM `post` WHERE `id_post` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    public function banDef($id, $raison){   
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        if ($result["admin"] == 1){
            return false;
        }
    
        $query = "UPDATE `utilisateur` SET `ban` = 1, `date_fin_ban` = NULL , `justification_ban` = '$raison' WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    public function checkBan($id){ //Vérifie si l'utilisateur est banni et enlève le ban si la date de fin est passée
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        if ($result["ban"] == 1){
            if ($result["date_fin_ban"] == NULL){
                return true;
            }
            if ($result["date_fin_ban"] < date("Y-m-d H:i:s")){
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

    public function checkAdmin($id){
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        if ($result["admin"] == 1){
            return true;
        }else{
            return false;
        }
    }

    public function banTemp($id, $time, $raison){
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        if ($result["admin"] == 1){
            return false;
        }
    
        $query = "UPDATE `utilisateur` SET `ban` = 1, `date_fin_ban` = '$time', `justification_ban` = '$raison' WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    public function getDateBan($id){
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        if ($result["ban"] == 1){
            return $result["date_fin_ban"];
        }else{
            return false;
        }
    }

    public function unban($id){
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
    
        if ($result["admin"] == 1){
            return false;
        }
    
        $query = "UPDATE `utilisateur` SET `ban` = 0, `date_fin_ban` = NULL, `justification_ban` = NULL WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
    
        if ($result){
            return true;
        }else{
            return false;
        }
    }

    public function getJustificationBan($id){
        $query = "SELECT * FROM `utilisateur` WHERE `id_utilisateur` = $id";
        $result = $this->SQLconn->executeRequete($query);
        if ($result->num_rows == 0){
            exit();
        }
        $result = $result->fetch_assoc();
        return $result;
    }

}

?>