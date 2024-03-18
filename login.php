<?php
// Vérifie si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données soumises par le formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connexion à la base de données (à remplacer avec vos informations de connexion)
    require_once 'config.php';
    $mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Vérifie la connexion
    if ($mysqli->connect_error) {
        die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
    }

    // Prépare et exécute la requête pour vérifier les informations de connexion
    $stmt = $mysqli->prepare("SELECT * FROM utilisateur WHERE username = ? AND mdp = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifie s'il y a un utilisateur correspondant dans la base de données
    if ($result->num_rows == 1) {
        // Redirection vers la page d'accueil si la connexion réussit

        session_start();
        $_SESSION['user_id'] = $result;

        header("Location: index.php");
        exit(); // Arrête l'exécution du script après la redirection
    } else {
        // Affiche un message d'erreur si la connexion échoue
        $erreur = "Nom d'utilisateur ou mot de passe incorrect";
    }

    // Ferme la connexion et la déclaration préparée
    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <main>
        <?php require("navbar.php"); ?>
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <form action="login.php" method="post" class="mb-4">
                        <div class="form-group text-center mb-4">
                            <h1>Se connecter</h1>
                        </div>
                        <!-- Affiche un message d'erreur s'il y en a un -->
                        <?php if (isset($erreur)) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $erreur; ?>
                            </div>
                        <?php } ?>
                        <div class="form-group form-field">
                            <label for="username">Nom d'utilisateur:</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group form-field">
                            <label for="password">Mot de passe:</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" value="Se connecter" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <footer>
      <p>© 2024 My Page</p>
      <ul>
        <li><a href="#">Terms of Service</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">Contact Us</a></li>
      </ul>
    </footer>
    <script src="script.js"></script>
  </body>
</html>
