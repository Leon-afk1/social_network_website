<div class="card text-bg-dark border-secondary">
    <form action="#" method="post" class="mb-4">
        <div class="text-center card-header">
            <h1 class="card-title">Modifier mon mot de passe</h1>
        </div>
        <div class="card-body">
            <div class="form-group form-field">
                <label for="nom">Ancien mot de passe:</label>
                <input type="password" name="mdp" id="mdp" class="form-control" required>
            </div>
            <div class="form-group form-field">
                <label for="prenom">Nouveau mot de passe:</label>
                <input type="password" name="newmdp1" id="newmdp1" class="form-control" required>
            </div>
            <div class="form-group form-field">
                <label for="username">Vérification du nouveau mot de passe:</label>
                <input type="password" name="newmdp2" id="newmdp2" class="form-control" required>
            </div>
            <br>
            <div class="form-group text-center">
                <input type="hidden" name="submitModificationMdp" value="true">
                <button type="submit" class="btn btn-outline-secondary">Valider</button>
            </div>
        </div>
    </form>
</div>