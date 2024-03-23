<form action="./sign_in.php" method="post" class="newlogin">
    <div class="form-group text-center mb-4">
        <h1>Créer un compte</h1>
    </div>
    <?php if (!$newAccountStatus["Successful"]) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $newAccountStatus["ErrorMessage"]; ?>
            </div>
    <?php } ?>
    <div class=new-login>
        
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
            <button type="submit">S'inscrire</button>
        </div>
    </div>
</form>