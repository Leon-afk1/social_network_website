<div class="card text-bg-dark border-secondary">
    <form action="#" method="post" class="mb-4" enctype="multipart/form-data">
        <div class="text-center card-header">
            <h1 class="card-title">Faire un post</h1>
        </div>
        <div class="card-body">
            <div class="form-group form-field">
                <label for="Commentaire">Commentaire:</label>
                <textarea name="commentaire" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group form-field">
                <label for="image">Image:</label>
                <input type="file" name="image" class="form-control">
            </div>
            <br>
            <div class="form-group text-center">
                <input type="hidden" name="submitPost" value="true">
                <button type="submit" class="btn btn-outline-secondary">Valider</button>
            </div>
        </div>
    </form>
</div>