<div class="card border-secondary">
    <form action="poster.php" method="post" class="mb-4" enctype="multipart/form-data">
        <div class="text-center card-header">
            <h1 class="card-title">Faire un post</h1>
        </div>
        <div class="card-body">
            <?php if (isset($erreurPost)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $erreurPost; ?>
                </div>
            <?php } ?>
            <div class="form-group form-field">
                <label for="commentaire">Commentaire:</label>
                <textarea name="commentaire" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group form-field">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="typeMedia" id="imageRadio" value="image" checked>
                    <label class="form-check-label" for="imageRadio">
                        Image
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="typeMedia" id="videoRadio" value="video">
                    <label class="form-check-label" for="videoRadio">
                        Vidéo
                    </label>
                </div>
            </div>
            <div id="imageField">
                <div class="form-group form-field">
                    <label for="image">Image:</label>
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
            <div id="videoField" style="display: none;">
                <div class="form-group form-field">
                    <label for="video">Lien de la vidéo (YouTube):</label>
                    <input type="text" name="video" class="form-control">
                </div>
            </div>
            <div class="form-group text-center">
                <input type="hidden" name="submitReponse" value="true">
                <button type="submit" class="btn btn-outline-secondary">Valider</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var imageRadio = document.getElementById('imageRadio');
        var videoRadio = document.getElementById('videoRadio');
        var imageField = document.getElementById('imageField');
        var videoField = document.getElementById('videoField');

        imageRadio.addEventListener('click', function() {
            imageField.style.display = 'block';
            videoField.style.display = 'none';
            videoField.querySelector('input').value = '';
        });

        videoRadio.addEventListener('click', function() {
            imageField.style.display = 'none';
            videoField.style.display = 'block';
            imageField.querySelector('input').value = '';
        });
    });
</script>
