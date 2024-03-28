<?php
include ("loc.php");
$AccountStatus = CheckLogin();

if ($AccountStatus["loginSuccessful"]){
    echo "connected";
	header("Location:./index.php");
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
  <body class="text-bg-dark">
    <main class="p-3 d-flex w-100 h-75 mx-auto flex-column">
        <div class="row justify-content-center">
            <div class="card w-50 text-bg-dark border-secondary">
                <form action="login.php" method="post" class="mb-4">
                    <div class="card-header">
                        <h1 class="card-title">Se connecter</h1>
                    </div>
                    <div class="card-body">
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
                    </div>
                </form> 
            </div>
        </div>
    </main>
    <?php include ("BoutDePages/footer.php"); ?>
    <script src="script.js"></script>
  </body>
</html>
