<?php

function ConnectToDataBase() {
    $serveur = 'localhost';
    $utilisateur = 'root';
    $motDePasse = '';
    $nomBaseDeDonnees = 'twitterlike';

    global $conn;

    $conn = new mysqli($serveur, $utilisateur, $motDePasse, $nomBaseDeDonnees);
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }
}

function executeRequete($req){
    global $conn;
    $resultat = $conn->query($req);
    if(!$resultat) 
    {
        die("Erreur sur la requete sql.<br>Message : " . $conn->error . "<br>Code: " . $req);
    }
    return $resultat; 
}

function SecurizeString_ForSQL($string) {
    $string = trim($string);
    $string = stripcslashes($string);
    $string = addslashes($string);
    $string = htmlspecialchars($string);
    return $string;
}

function register(){
    global $conn;

    $creationAttempted = false;
    $creationSuccessful = false;
    $error = NULL;
    $loginSuccessful = false;
    if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["username"]) && isset($_POST["date_naissance"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password_confirm"]) && isset($_POST["adresse"])){
        $creationAttempted = true;

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
        else if (strlen($_POST["password"]) < 2){
            $error = "Mot de passe trop court";
        }
        else if ($_POST["password"] != $_POST["password_confirm"]){
            $error = "Les mots de passe ne correspondent pas";
        }
        else if (strlen($_POST["adresse"]) < 5){
            $error = "Adresse trop courte";
        }
        // else if (!checkAge($_POST["date_naissance"])){
        //     $error = "Vous devez avoir au moins 18 ans pour vous inscrire";
        // }       
        else {
            $query1 = "SELECT id_utilisateur FROM utilisateur WHERE username = '" . SecurizeString_ForSQL($_POST["username"]) . "'";
            $result1 = executeRequete($query1);
            $query2 = "SELECT id_utilisateur FROM utilisateur WHERE email = '" . SecurizeString_ForSQL($_POST["email"]) . "'";
            $result2 = executeRequete($query2);
            if ($result1->num_rows > 0) {
                $result1 = $result1->fetch_assoc();
                foreach ($result1 as $row){
                    echo $row;
                }


                $error = "Nom d'utilisateur déjà utilisé";
            }
            else if ($result2->num_rows > 0) {
                $error = "Email déjà utilisé";
            }else {
                $nom = SecurizeString_ForSQL($_POST["nom"]);
                $prenom = SecurizeString_ForSQL($_POST["prenom"]);
                $username = SecurizeString_ForSQL($_POST["username"]);
                $dateNaissance = $_POST["date_naissance"];
                $email = $_POST["email"];
                $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $adresse = SecurizeString_ForSQL($_POST["adresse"]);

                $query = "INSERT INTO utilisateur (nom, prenom, username, dateNaissance, email, mdp, adresse) VALUES ('$nom', '$prenom', '$username', '$dateNaissance', '$email', '$password', '$adresse')";
                $result = executeRequete($query);
                if ($result === TRUE) {
                    $creationSuccessful = true;
                    $query = "SELECT * FROM utilisateur WHERE username = '$username'";
                    $result = executeRequete($query);
                    $row = $result->fetch_assoc();
                    CreateLoginCookie($username, $_POST["password"], $row['id_utilisateur']);
                    $loginSuccessful = true;
                } else {
                    $error = "Erreur lors de l'insertion SQL: " . $conn->error;
                }
            }
        }

	}
	
	$resultArray = ['Attempted' => $creationAttempted, 
					'Successful' => $creationSuccessful, 
					'ErrorMessage' => $error,
                    'LoginSuccessful' => $loginSuccessful];

    return $resultArray;
}

function checkAge($date_naissance){
    $date_naissance = new DateTime($date_naissance);
    $date_actuelle = new DateTime();
    $age = $date_naissance->diff($date_actuelle)->y;
    if ($age < 18){
        return false;
    }
    return true;
}

function CheckLogin() {
    global $conn;

    $error = NULL;
    $loginSuccessful = false;
    $userId = NULL;
    if (isset($_POST['usernameLogin']) && isset($_POST['passwordLogin'])){
        $username = SecurizeString_ForSQL($_POST['usernameLogin']);
        $password = $_POST['passwordLogin'];
        $tryConnect = true;
    }

    elseif (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
        $username = $_COOKIE['username'];
        $password = $_COOKIE['password'];
        $tryConnect = true;
    }

    else {
        $error = "Veuillez entrer un nom d'utilisateur et un mot de passe.";
        $tryConnect = false;
    }

    if ($tryConnect) {
        $query = "SELECT * FROM utilisateur WHERE username = '$username'";
        $result = executeRequete($query);

        if ($result->num_rows ==1) {
            $row = $result->fetch_assoc();
            $passwordHash = $row['mdp'];
            if (password_verify($password, $passwordHash)) {
                $loginSuccessful = true;
                $userId = $row['id_utilisateur'];
                CreateLoginCookie($username, $password,$userId);
            }
            else {
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
        else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }

    $result = [
        'loginSuccessful' => $loginSuccessful,
        'error' => $error,
        'userID' => $userId
    ];

    return $result;
}

function CreateLoginCookie($username, $password, $userId) {
    setcookie('username', $username, time() + 3600 * 24 );
    setcookie('password', $password, time() + 3600 * 24 );
    setcookie('user_id', $userId, time() + 3600 * 24 );
}

function DeleteLoginCookie() {
    setcookie('username', '', -1);
    setcookie('password', '', -1);
    setcookie('user_id', '', -1);
}

function GetInfoProfile($userId) {
    $query = "SELECT * FROM utilisateur WHERE id_utilisateur = $userId";
    $result = executeRequete($query);
    $row = $result->fetch_assoc();

    return $row;
}

function UpdateInfosProfile($userId){
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
            $query1 = "SELECT * FROM utilisateur WHERE username = '" . SecurizeString_ForSQL($_POST["username"]) . "'";
            $result1 = executeRequete($query1);
            $query2 = "SELECT * FROM utilisateur WHERE email = '" . SecurizeString_ForSQL($_POST["email"]) . "'";
            $result2 = executeRequete($query2);
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
                $nom = SecurizeString_ForSQL($_POST["nom"]);
                $prenom = SecurizeString_ForSQL($_POST["prenom"]);
                $username = SecurizeString_ForSQL($_POST["username"]);
                $dateNaissance = $_POST["date_naissance"];
                $email = $_POST["email"];
                $description = SecurizeString_ForSQL($_POST["description"]);
                $adresse = SecurizeString_ForSQL($_POST["adresse"]);

                $query = "UPDATE utilisateur SET nom = '$nom', prenom = '$prenom', username = '$username', dateNaissance = '$dateNaissance', email = '$email' , description= '$description', adresse='$adresse' WHERE id_utilisateur = $userId";
                $result = executeRequete($query);
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

function UpdateAvatar($userId){
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
            $avatarPath = SecurizeString_ForSQL($avatarPath);

            $query = "UPDATE utilisateur SET avatar = '$avatarPath' WHERE id_utilisateur = $userId";
            $result = executeRequete($query);
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
                    $result = executeRequete($query);
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

function changermdp($userId){
    global $conn;

    $updateAttempted = false;
    $updateSuccessful = false;
    $error = NULL;
    
    if ($_POST["submitModificationMdp"]){
        $updateAttempted = true;

        $query = "SELECT * FROM utilisateur WHERE id_utilisateur = $userId";
        $result = executeRequete($query);
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
            $result = executeRequete($query);
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

function ajouterNewPost($userId, $parentId = null){
    global $conn;

    $ajouterPosttry = false;
    $ajouterPost = false;
    $error = NULL;
    $imagePathForDB = "";

    if ($_POST["submitPost"]){
        $ajouterPosttry = true;
        $commentaire = SecurizeString_ForSQL($_POST["commentaire"]);
        $video = SecurizeString_ForSQL($_POST["video"]);

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
                $imagePathForDB = SecurizeString_ForSQL($path_filename_ext); 
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
                    'ErrorMessage' => $error];

    return $resultArray;
}


function getAllPosts($userId) {
    global $conn;

    $query = "SELECT post.id_post, post.contenu, post.image_path, post.date, utilisateur.nom, utilisateur.prenom, post.video_lien FROM post
              INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur
              WHERE post.id_utilisateur = $userId AND post.id_parent IS NULL
              ORDER BY post.date DESC";
    $result = executeRequete($query);

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $post = [
            'id' => $row['id_post'],
            'contenu' => $row['contenu'],
            'image' => $row['image_path'],
            'date' => $row['date'],
            'nom_utilisateur' => $row['nom'],
            'prenom_utilisateur' => $row['prenom'],
            'video_lien' => $row['video_lien']
        ];
        $posts[] = $post;
    }

    return $posts;
}

function getPostInfos($postId){ //récupérer les infos d'un post sauf les likes
    global $conn;

    $query = "SELECT post.id_post, post.contenu, post.image_path, post.date, post.video_link, utilisateur.username FROM post
              INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur
              WHERE post.id_post = $postId";
    $result = executeRequete($query);
    $row = $result->fetch_assoc();
    $post = [
        'id' => $row['id_post'],
        'contenu' => $row['contenu'],
        'image' => $row['image_path'],
        'date' => $row['date'],
        'auteur' => $row['username'],
        'lien_video' => $row['video_link']
    ];
    //var_dump($post); //pour visualiser le contenu de la variable
    return $post;
}

function getNumberLikes($postId){ //fonction qui retourne le nombre de likes d'un post
    $query= "SELECT COUNT(*) FROM likes WHERE id_post=$postId";
    $result = executeRequete($query);
    //var_dump($result); //our visualiser le contenu de la variable
    $row = [];
    $row = $result->fetch_assoc();
    return $row['COUNT(*)'];
}

function isLiked($userId, $postId){ //fonction pour vérifier si un post est liké par un utilisateur
    $query = "SELECT * FROM likes WHERE id_utilisateur = $userId AND id_post = $postId";
    $result = executeRequete($query);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function likePost($userId, $postId){ //fonction pour liker un post
    //Dans un premier temps on vérifie le like n'existe pas déjà
    $verification = isLiked($userId, $postId);
    if ($verification == false) {
        $query = "INSERT INTO likes (id_post, id_utilisateur) VALUES ($postId, $userId)";
        executeRequete($query);
    } else {
        $query = "DELETE FROM likes WHERE id_post = $postId AND id_utilisateur = $userId";
        executeRequete($query);
    }
}

function follow($userId, $userIdToFollow){
    global $conn;

    $query = "INSERT INTO follower (id_utilisateur, id_utilisateur_suivi) VALUES ($userId, $userIdToFollow)";
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function unfollow($userId, $userIdToUnfollow){
    global $conn;

    $query = "DELETE FROM follower WHERE id_utilisateur = $userId AND id_utilisateur_suivi = $userIdToUnfollow";
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function verifFollow($userId, $userIdToFollow){
    global $conn;

    $query = "SELECT * FROM follower WHERE id_utilisateur = $userId AND id_utilisateur_suivi = $userIdToFollow";
    $result = executeRequete($query);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function totalFollowers($userId){
    global $conn;

    $query = "SELECT * FROM follower WHERE id_utilisateur_suivi = $userId";
    $result = executeRequete($query);
    return $result->num_rows;
}

function totalFollowing($userId){
    global $conn;

    $query = "SELECT * FROM follower WHERE id_utilisateur = $userId";
    $result = executeRequete($query);
    return $result->num_rows;
}

function getYoutubeEmbedUrl($url) // https://stackoverflow.com/questions/19050890/find-youtube-link-in-php-string-and-convert-it-into-embed-code
{
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

?>
<script>
function toggleForm(postId) {
    var form = document.getElementById("postForm_" + postId);
    var button = document.getElementById("toggleFormButton_" + postId);

    if (form.style.display === "none") {
        form.style.display = "block";
        button.innerHTML = "Masquer le formulaire";
    } else {
        form.style.display = "none";
        button.innerHTML = "Afficher le formulaire";
    }
}

function sendTo(postId) {
    window.location.href = "./post.php?id=" + postId;
}

async function supprimerPost(postId) {
    var supp = await fetch("./BoutDePages/supprimerPost.php?id=" + postId);
    var response = await supp.text();
    if (response == "Post supprimé") {
        var post = document.getElementById("post_" + postId);
        post.style.display = "none";
    } else {
        alert(response);
    }
}


</script>
<?php

function afficherPosts($post, $infos){
    $idPost = $post['id'];
    echo "<div class='card outline-secondary rounded-3' id='post_".$idPost."'>";
    echo    "<div class='card-header outline-secondary'>";
    echo        "<div class='row'>";
    echo            "<div class='col'>";
    echo                "<a class='nav-link active' aria-current='page' href='./profile.php?id=".$infos["id_utilisateur"]."'> 
                            <img src='".$infos["avatar"]."' class='avatar avatar-lg'>
                            <label for='nom'>". $infos["nom"]." ".$infos["prenom"]."</label>
                        </a>";
    echo            "</div>";
    echo            "<div class='col text-end'>";
    if (isset($_COOKIE['user_id']) && $infos['id_utilisateur'] == $_COOKIE['user_id']){
        echo               "<button class='btn btn-outline-secondary' id='supprimerPost_".$idPost."' data-bs-toggle='modal' data-bs-target='#supprimerPostModal_".$idPost."'>Supprimer</button>";
        echo               "<button class='btn btn-outline-secondary' id='modifierPost_".$idPost."'>Modifier</button>";
    }

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

    echo            "</div>";
    echo        "</div>";
    echo    "</div>";
    echo    "<div class='card-body text-center' onclick='sendTo($idPost)'>";
    if (!empty($post['image'])) {
        echo "<img src='{$post['image']}' class='img-fluid'>";
    }
    echo        "<p class='card-text'>".$post["contenu"]."</p>";
    if (!empty($post['video_lien'])) {
        $videoEmbed = getYoutubeEmbedUrl($post['video_lien']);
        
        $videoEmbedDisplay = '<iframe src="'.$videoEmbed.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        echo "<p class='card-text'>".$videoEmbedDisplay."</p>";
        //echo $videoEmbedDisplay;
        echo "<br>";
    }
    echo "<br>";
    $likesAmount = getNumberLikes($post["id"]); //récupère le nb de likes du post
    echo "<p class='card-text'>".$likesAmount." likes</p>";
    echo "<br>";
    echo "<br>";
    $estLike = false;
    if (isset($_COOKIE['user_id'])){
        $estLike = isLiked($_COOKIE['user_id'], $post["id"]); //vérifie si l'utilisateur a liké le post
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
    
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    function toggleLike(button, postId) {
        $.post('like.php', { postId: postId }, function(data) {
            if (data.success) {
                // Mettre à jour l'image du bouton en fonction de l'état actuel
                if (data.liked) {
                    button.src = 'like.png';
                    button.alt = 'Liked';
                } else {
                    button.src = 'nolike.png';
                    button.alt = 'Not Liked';
                }
                
                // Mettre à jour le nombre de likes affiché en temps réel
                var likesCount = document.getElementById('likesCount-' + postId);
                if (likesCount) {
                    likesCount.innerText = data.likesCount + " likes";
                }
                
                // Afficher un message de confirmation
                alert("Post liked successfully!");
            } else {
                // Gérez les erreurs de requête ici
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
    </script>
    <?php
    if (isset($_COOKIE['user_id'])){
        echo "<p class='card-text'>".$post["date"]."</p>";
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
    echo                "<label for='image'>Image:</label>";
    echo                "<input type='file' name='image' class='form-control'>";
    echo             "</div>";
    echo            "<div class='form-group text-center'>";
    echo                "<input type='hidden' name='idPost' value='$idPost'>";
    echo                "<input type='hidden' name='submitPost' value='true'>";
    echo                "<br>";
    echo                "<button type='submit' class='btn btn-outline-secondary'>Valider</button>";
    echo            "</div>";
    echo        "</form>";    
    
    echo    "</div>";
    echo "</div>";
    echo "<br>";
}

function getReponsesCommentaire($idPost){
    global $conn;

    $query = "SELECT * FROM post WHERE id_parent = $idPost";
    $result = executeRequete($query);

    $reponses = [];
    while ($row = $result->fetch_assoc()) {
        $reponse = [
            'id' => $row['id_post'],
            'contenu' => $row['contenu'],
            'image' => $row['image_path'],
            'date' => $row['date'],
            'id_utilisateur' => $row['id_utilisateur']
        ];
        $reponses[] = $reponse;
    }

    return $reponses;
}



function afficherBestPosts($userId){
    global $conn;

    $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path, post.date, utilisateur.nom, utilisateur.prenom FROM post
              INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur AND post.id_utilisateur != $userId
              ORDER BY post.date DESC";
    $result = executeRequete($query);

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

function afficherRecentPostsFollowed($userId){
    global $conn;

    $query = "SELECT post.id_post, post.id_utilisateur, post.contenu, post.image_path, post.date, utilisateur.nom, utilisateur.prenom FROM post
              INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur
              WHERE post.id_utilisateur IN (SELECT id_utilisateur_suivi FROM follower WHERE id_utilisateur = $userId)
              ORDER BY post.date DESC";
    $result = executeRequete($query);

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
    
function GetInfos($id){
    global $conn;

    $query = "SELECT * FROM utilisateur WHERE id_utilisateur = $id";
    $result = executeRequete($query);
    $row = $result->fetch_assoc();

    return $row;
}

function GetPostById($id){
    global $conn;

    $query = "SELECT * FROM post WHERE id_post = $id";
    $result = executeRequete($query);
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

?>