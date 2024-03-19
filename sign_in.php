<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  </head>
  <body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <main>
        <?php require("navbar.php"); ?>
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <form action="register.php" method="post" class="mb-4">
                        <div class="form-group text-center mb-4">
                            <h1>Créer un compte</h1>
                        </div>
                        <div class="form-group form-field">
                            <label for="nom">Nom:</label>
                            <input type="text" name="nom" id="nom" class="form-control" required>
                        </div>
                        <div class="form-group form-field">
                            <label for="prenom">Prénom:</label>
                            <input type="text" name="prenom" id="prenom" class="form-control" required>
                        </div>
                        <div class="form-group form-field">
                            <label for="username">Nom d'utilisateur:</label>
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                        <div class="form-group form-field">
                            <label for="date_naissance">Date de naissance:</label>
                            <input type="date" name="date_naissance" id="date_naissance" class="form-control" required>
                        </div>
                        <div class="form-group form-field">
                            <label for="email">Adresse e-mail:</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group form-field">
                            <label for="password">Mot de passe:</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" value="S'inscrire" class="btn btn-primary">
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