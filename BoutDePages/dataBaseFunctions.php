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

function executeRequete($req)
{
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


function ajouterNewPost($userId){
    global $conn;

    $ajouterPost = false;
    $error = NULL;
    $imagePathForDB = "";

    if ($_POST["submitPost"]){
        $ajouterPost = true;
        $commentaire = SecurizeString_ForSQL($_POST["commentaire"]);

        $query = "INSERT INTO post (id_utilisateur, contenu) VALUES ($userId, '$commentaire')";
        if (mysqli_query($conn, $query)) {
            $postId = mysqli_insert_id($conn);

            if ($_FILES['image']["size"] > 0){
                $image = $_FILES["image"];
                $imagePath = "./images/" . $postId . ".jpg"; 
                $imagePathForDB = SecurizeString_ForSQL($imagePath); 
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($imagePath,PATHINFO_EXTENSION));
                $check = getimagesize($image["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $error = "Le fichier n'est pas une image.";
                    $uploadOk = 0;
                }
                if ($image["size"] > 500000) {
                    $error = "L'image est trop grande.";
                    $uploadOk = 0;
                }
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $error = "Seuls les fichiers JPG, JPEG, PNG sont autorisés.";
                    $uploadOk = 0;
                }
                if ($uploadOk != 0) {
                    if (move_uploaded_file($image["tmp_name"], $imagePath)) {
                        $query = "UPDATE post SET image_path = '$imagePathForDB' WHERE id_post = $postId";
                        if (!mysqli_query($conn, $query)) {
                            $error = "Erreur lors de la mise à jour du chemin de l'image dans la base de données.";
                        }
                    } else {
                        $error = "Erreur lors de l'upload de l'image.";
                    }
                }
            }
        } else {
            $error = "Erreur lors de l'insertion dans la base de données: " . mysqli_error($conn);
        }
    }

    $resultArray = ['Attempted' => $ajouterPost, 
                    'Successful' => $ajouterPost,
                    'ErrorMessage' => $error];

    return $resultArray;
}

function getAllPosts($userId) {
    global $conn;

    $query = "SELECT post.id_post, post.contenu, post.image_path, post.date, utilisateur.nom, utilisateur.prenom FROM post
              INNER JOIN utilisateur ON post.id_utilisateur = utilisateur.id_utilisateur
              WHERE post.id_utilisateur = $userId
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
            'prenom_utilisateur' => $row['prenom']
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

function likePost($userId, $postId){ //fonction pour liker un post
    //Dans un premier temps on vérifie le like n'existe pas déjà
    $query = "SELECT * FROM likes WHERE id_utilisateur = $userId AND id_post = $postId";
    $result = executeRequete($query);

    if ($result->num_rows == 0) {
        $query = "INSERT INTO likes (id_post, id_utilisateur) VALUES ($postId, $userId)";
        $result = executeRequete($query);
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

function afficherPosts($post, $infos){
    echo "<div class='card outline-secondary'>";
    echo "<div class='card-header outline-secondary'>";
    echo "<a class='nav-link active' aria-current='page' href='./profile.php?id=".$infos["id_utilisateur"]."'> 
            <img src='".$infos["avatar"]."' class='avatar avatar-lg'>
            <label for='nom'>". $infos["nom"]." ".$infos["prenom"]."</label>
            </a>";
    echo "</div>";
    echo "<div class='card-body'>";
    if (!empty($post['image'])) {
        echo "<img src='{$post['image']}' class='img-fluid'>";
    }
    echo "<p class='card-text'>".$post["contenu"]."</p>";
    echo "</div>";
    echo "</div>";
    echo "<br>";
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