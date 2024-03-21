<?php

$rootpath = "localhost/WE4A/test2";

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
    if(!$resultat) // 
    {
        die("Erreur sur la requete sql.<br>Message : " . $conn->error . "<br>Code: " . $req);
    }
    return $resultat; // 
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
        echo "register2";
        echo "register";
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
        else {
            $nom = SecurizeString_ForSQL($_POST["nom"]);
            $prenom = SecurizeString_ForSQL($_POST["prenom"]);
            $username = SecurizeString_ForSQL($_POST["username"]);
            $dateNaissance = $_POST["date_naissance"];
            $email = $_POST["email"];
		    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            // $password = SecurizeString_ForSQL($_POST["password"]);

            $query = "INSERT INTO utilisateur (nom, prenom, username, dateNaissance, email, mdp) VALUES ('$nom', '$prenom', '$username', '$dateNaissance', '$email', '$password')";
            $result = executeRequete($query);
            if ($result === TRUE) {
                $creationSuccessful = true;
            } else {
                $error = "Erreur lors de l'insertion SQL: " . $conn->error;
            }
        }

	}
	
	$resultArray = ['Attempted' => $creationAttempted, 
					'Successful' => $creationSuccessful, 
					'ErrorMessage' => $error];

    return $resultArray;
}

function CheckLogin() {
    global $conn;

    $error = NULL;
    $loginSuccessful = false;
    $userId = NULL;
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
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
                CreateLoginCookie($username, $password);
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


function CreateLoginCookie($username, $password) {
    setcookie('username', $username, time() + 3600 * 24 );
    setcookie('password', $password, time() + 3600 * 24 );
}

function DeleteLoginCookie() {
    setcookie('username', '', -1);
    setcookie('password', '', -1);
}

