<?php
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données soumises par le formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $username = $_POST["username"];
    $dateNaissance = $_POST["date_naissance"];
    $email = $_POST["email"];
    $mdp = $_POST["password"];

    // Connexion à la base de données (à remplacer avec vos informations de connexion)
    require_once 'config.php';
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Vérifie la connexion
    if ($mysqli->connect_error) {
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    // Prépare et exécute la requête d'insertion
    $stmt = $mysqli->prepare("INSERT INTO utilisateur (nom, prenom, username, dateNaissance, email, mdp) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nom, $prenom, $username, $dateNaissance, $email, $mdp);
    if ($stmt->execute()) {
        // Redirection vers la page de connexion si l'inscription réussit
        header("Location: login.php");
        exit(); // Arrête l'exécution du script après la redirection
    } else {
        // Affiche un message d'erreur si l'inscription échoue
        $erreur = "Erreur lors de l'inscription: " . $stmt->error;
    }

    // Ferme la connexion et la déclaration préparée
    $stmt->close();
    $mysqli->close();
}
?>
