<div class="card text-bg-dark border-secondary">
    <form action="./sign_in.php" method="post" class="mb-4">
        <div class="text-center card-header">
            <h1 class="card-title">Créer un compte</h1>
        </div>
        <div class="card-body">
            <?php if ($newAccountStatus["Attempted"]) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $newAccountStatus["ErrorMessage"]; ?>
                    </div>
            <?php } ?>
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
                <label for="adresse">Adresse:</label>
                <input type="text" name="adresse" id="adresse" class="form-control" required>
            </div>
            <div class="form-group form-field">
                <label for="email">Adresse e-mail:</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group form-field">
                <label for="password">Mot de passe:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="form-group form-field">
                <label for="password_confirm">Confirmer le mot de passe:</label>
                <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
            </div>
            <br>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-outline-secondary">S'inscrire</button>
            </div>
        </div>
    </form>
</div>