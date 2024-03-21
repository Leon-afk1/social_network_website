<?php
include ("BoutDePages/dataBaseFunctions.php");
ConnectToDataBase();
$AccountStatus = CheckLogin();

if ($AccountStatus["loginSuccessful"]){
    echo "connected";
	header("Location:http://".$rootpath."/index.php");
    exit();
}

include ("BoutDePages/header.php");

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
