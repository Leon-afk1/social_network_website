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
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
  </head>
  <body class="text-bg-dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <main class="p-3 d-flex col-md-8 col-lg-8 mx-auto flex-column">
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
