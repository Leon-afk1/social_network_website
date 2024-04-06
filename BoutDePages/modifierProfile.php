<div class="card outline-secondary">
    <form action="#" method="post" class="mb-4" enctype="multipart/form-data">
        <div class="text-center card-header">
            <h1 class="card-title">Modifier mes informations</h1>
        </div>
        <div class="card-body">
            <div class="form-group form-field">
                <label for="nom">Nom:</label>
                <input type="text" name="nom" id="nom" class="form-control" value=<?php echo $Infos["nom"] ?> required>
            </div>
            <div class="form-group form-field">
                <label for="prenom">Prénom:</label>
                <input type="text" name="prenom" id="prenom" class="form-control" value=<?php echo $Infos["prenom"] ?> required>
            </div>
            <div class="form-group form-field">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" name="username" id="username" class="form-control" value=<?php echo $Infos["username"] ?> required>
            </div>
            <div class="form-group form-field">
                <label for="date_naissance">Date de naissance:</label>
                <input type="date" name="date_naissance" id="date_naissance" class="form-control" value=<?php echo $Infos["dateNaissance"] ?> required>
            </div>
            <div class="form-group form-field">
                <label for="adresse">Adresse:</label>
                <input type="text" name="adresse" id="adresse" class="form-control" value=<?php echo $Infos["adresse"] ?> required>
            </div>
            <div class="form-group form-field">
                <label for="email">Adresse e-mail:</label>
                <input type="email" name="email" id="email" class="form-control" value=<?php echo $Infos["email"] ?> required>
            </div>
            <div class="form-group form-field">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="3"><?php echo $Infos["description"] ?></textarea>
            </div>
            <div class="form-group form-field">
                <label for="avatar">Avatar:</label>
                <input type="file" name="avatar" class="form-control">
            </div>
            <br>
            <div class="form-group text-center">
                <input type="hidden" name="submitModification" value="true">
                <button type="submit" class="btn btn-outline-secondary">Valider</button>
            </div>
        </div>
    </form>
</div>