<?php

define('__ROOT__', dirname(__FILE__) );

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
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
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
        // else if (!checkAge($_POST["date_naissance"])){
        //     $error = "Vous devez avoir au moins 18 ans pour vous inscrire";
        // }       
        else {
            $query1 = "SELECT * FROM utilisateur WHERE username = '" . SecurizeString_ForSQL($_POST["username"]) . "'";
            $result1 = executeRequete($query1);
            $query2 = "SELECT * FROM utilisateur WHERE email = '" . SecurizeString_ForSQL($_POST["email"]) . "'";
            $result2 = executeRequete($query2);
            if ($result1->num_rows > 0) {
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

                $query = "INSERT INTO utilisateur (nom, prenom, username, dateNaissance, email, mdp) VALUES ('$nom', '$prenom', '$username', '$dateNaissance', '$email', '$password')";
                $result = executeRequete($query);
                if ($result === TRUE) {
                    $creationSuccessful = true;
                } else {
                    $error = "Erreur lors de l'insertion SQL: " . $conn->error;
                }
            }
        }

	}
	
	$resultArray = ['Attempted' => $creationAttempted, 
					'Successful' => $creationSuccessful, 
					'ErrorMessage' => $error];

    return $resultArray;
}

function  checkAge($date_naissance){
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
    if (isset($_POST['username']) && isset($_POST['password'])){
        $username = SecurizeString_ForSQL($_POST['username']);
        $password = $_POST['password'];
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
    global $conn;

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

                $query = "UPDATE utilisateur SET nom = '$nom', prenom = '$prenom', username = '$username', dateNaissance = '$dateNaissance', email = '$email' , description= '$description' WHERE id_utilisateur = $userId";
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
    if ($_POST["submitPost"]){
        $ajouterPost = true;

        if ($_FILES['image']["size"] == 0){
            $error = "Veuillez choisir une image";
        }
        else {
            $commentaire = SecurizeString_ForSQL($_POST["commentaire"]);
            $image = $_FILES["image"];
            $imagePath = "./images/" . $image["name"];
            $imagePath = SecurizeString_ForSQL($imagePath);

            $query = "INSERT INTO post (id_utilisateur, contenu, image) VALUES ($userId, '$commentaire', '$imagePath')";
            $result = executeRequete($query);
            if ($result === TRUE) {
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
                        $ajouterPost = false;
                    } else {
                        $error = "Erreur lors de l'upload de l'image.";
                    }
                }else {
                    $query = "DELETE FROM post WHERE id_utilisateur = $userId AND contenu = '$commentaire' AND image = '$imagePath'";
                    $result = executeRequete($query);
                }
            } else {
                $error = "Erreur lors de l'insertion SQL: " . $conn->error;
            }
        }
    }

    $resultArray = ['Attempted' => $ajouterPost, 
                    'Successful' => !$ajouterPost,
                    'ErrorMessage' => $error];

    return $resultArray;
}

function GetPosts($userId){
    global $conn;

    $query = "SELECT * FROM post WHERE id_utilisateur = $userId";
    $result = executeRequete($query);

    $posts = [];
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    return $posts;
}