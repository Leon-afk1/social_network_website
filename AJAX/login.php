<div class="row justify-content-center ">
    <div class="card login-card w-50 outline-secondary position-absolute top-50 start-50 translate-middle" id="loginForm" style="display: none;">
        <form action="index.php" method="post" class="mb-4">
            <div class="card-header">
                <h1 class="card-title">Se connecter</h1>
            </div>
            <div class="card-body">
                <?php if ($erreur !== "") { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $erreur; ?>
                    </div>
                <?php } ?>
                <div class="form-group form-field">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" name="usernameLogin" id="usernameLogin" class="form-control" required>
                </div>
                <div class="form-group form-field">
                    <label for="password">Mot de passe:</label>
                    <input type="password" name="passwordLogin" id="passwordLogin" class="form-control" required>
                </div>
                <div class="form-group text-center">
                    <input type="submit" value="Se connecter" class="btn btn-primary">
                </div>
            </div>
        </form> 
    </div>
</div>