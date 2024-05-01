<?php

class verifLogin{

    // Propriétés de la classe
    public $loginSuccessful = false;
    public $loginAttempted = false;
    public $errorText = "";
    public $userID = 0;
    public $userName = "";

    // Constructeur de la classe
    public function __construct(ConnexionBDD $SQLconn) {
        $error = NULL;
        $this->loginSuccessful = false;
        $this->userID = NULL;

        // Vérification de la tentative de connexion
        if (isset($_POST['usernameLogin']) && isset($_POST['passwordLogin'])){
            $this->userName = $SQLconn->SecurizeString_ForSQL($_POST['usernameLogin']);
            $password = $_POST['passwordLogin'];
            $tryConnect = true;
        } elseif (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
            $this->userName = $_COOKIE['username'];
            $password = $_COOKIE['password'];
            $tryConnect = true;
        } else {
            $error = "Veuillez entrer un nom d'utilisateur et un mot de passe.";
            $tryConnect = false;
        }

        // Si une tentative de connexion est en cours
        if ($tryConnect) {
            // Requête SQL pour récupérer l'utilisateur correspondant au nom d'utilisateur
            $query = "SELECT * FROM utilisateur WHERE username = '$this->userName'";
            $result = $SQLconn->executeRequete($query);

            // Si un utilisateur est trouvé
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $passwordHash = $row['mdp'];
                // Vérification du mot de passe
                if (password_verify($password, $passwordHash)) {
                    // Connexion réussie
                    $this->loginSuccessful = true;
                    $this->userID = $row['id_utilisateur'];
                    // Création d'un cookie de connexion
                    $this->CreateLoginCookie($this->userName, $password, $this->userID);
                } else {
                    $this->errorText = "Nom d'utilisateur ou mot de passe incorrect.";
                }
            } else {
                $this->errorText = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
    }

    // Méthode pour déconnecter l'utilisateur
    public function logout($redirectURL = '') {
        $this->DeleteLoginCookie();
        if (!empty($redirectURL)) {
            header("Location: $redirectURL");
        } else {
            header("Location: index.php");
        }
    }

    // Méthode pour créer un cookie de connexion
    public function CreateLoginCookie($username, $password, $userId) {
        setcookie('username', $username, time() + 3600 * 24 );
        setcookie('password', $password, time() + 3600 * 24 );
        setcookie('user_id', $userId, time() + 3600 * 24 );
    }
    
    // Méthode pour supprimer le cookie de connexion
    public function DeleteLoginCookie() {
        setcookie('username', '', -1);
        setcookie('password', '', -1);
        setcookie('user_id', '', -1);
    }

    // Méthode pour enregistrer un nouvel utilisateur
    public function register(ConnexionBDD $SQLconn) {
        $creationAttempted = false;
        $creationSuccessful = false;
        $error = NULL;
        $loginSuccessful = false;

        // Vérifiez si les champs requis sont définis dans la requête POST
        if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["username"]) && isset($_POST["date_naissance"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password_confirm"]) && isset($_POST["adresse"])){
            $creationAttempted = true;

            // Vérifiez la validité des données
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
            else {
                // Vérifiez si le nom d'utilisateur et l'email sont déjà utilisés
                $query1 = "SELECT id_utilisateur FROM utilisateur WHERE username = '" . $SQLconn->SecurizeString_ForSQL($_POST["username"]) . "'";
                $result1 = $SQLconn->executeRequete($query1);
                $query2 = "SELECT id_utilisateur FROM utilisateur WHERE email = '" . $SQLconn->SecurizeString_ForSQL($_POST["email"]) . "'";
                $result2 = $SQLconn->executeRequete($query2);
                
                if ($result1->num_rows > 0) {
                    $error = "Nom d'utilisateur déjà utilisé";
                }
                else if ($result2->num_rows > 0) {
                    $error = "Email déjà utilisé";
                } else {
                    // Créez un nouvel utilisateur dans la base de données
                    $nom = $SQLconn->SecurizeString_ForSQL($_POST["nom"]);
                    $prenom = $SQLconn->SecurizeString_ForSQL($_POST["prenom"]);
                    $username = $SQLconn->SecurizeString_ForSQL($_POST["username"]);
                    $dateNaissance = $_POST["date_naissance"];
                    $email = $_POST["email"];
                    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                    $adresse = $SQLconn->SecurizeString_ForSQL($_POST["adresse"]);
    
                    $query = "INSERT INTO utilisateur (nom, prenom, username, dateNaissance, email, mdp, adresse) VALUES ('$nom', '$prenom', '$username', '$dateNaissance', '$email', '$password', '$adresse')";
                    $result = $SQLconn->executeRequete($query);
                    
                    if ($result === TRUE) {
                        $creationSuccessful = true;
    
                        // Créez un cookie de connexion pour l'utilisateur nouvellement créé
                        $query = "SELECT * FROM utilisateur WHERE username = '$username'";
                        $result = $SQLconn->executeRequete($query);
                        $row = $result->fetch_assoc();
                        $this->CreateLoginCookie($username, $_POST["password"], $row['id_utilisateur']);
                        $loginSuccessful = true;
                    } else {
                        $error = "Erreur lors de l'insertion SQL: ";
                    }
                }
            }
        }
        
        // Retournez le résultat de la tentative de création de compte
        $resultArray = [
            'Attempted' => $creationAttempted, 
            'Successful' => $creationSuccessful, 
            'ErrorMessage' => $error,
            'LoginSuccessful' => $loginSuccessful
        ];
    
        return $resultArray;
    }

}

?>
