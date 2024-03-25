<?php
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Poster un message </title>
        <link rel="stylesheet" href="styles.css">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /> <!-- from stackoverflow -->
    </head>
    <body>
        <main>
            <div class="container mt-5" id="ecrirePost">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <form action="post_preview.php" method="post">
                            <!-- contenu du post -->
                            <div class="mb-3">
                                <label for="postContent" class="form-label">Message</label>
                                <textarea class="form-control" id="postContent" name="postContent" rows="3"></textarea>
                            </div>
                            <!-- ajouter une image -->
                            <div class="mb-3">
                                <label for="postImage" class="form-label">Image</label>
                                <input type="file" class="form-control" id="postImage" name="postImage">
                            </div>
                            <!-- ajouter une vidéo -->
                            <div class="mb-3">
                                <label for="postVideoLink" class="form-label">Attacher une vidéo via Youtube</label>
                                <input type="text" class="form-control" id="postVideoLink" name="postVideoLink">
                            </div>
                            <button type="submit" class="btn btn-primary">Poster</button>
                        </form>
                    </div>
                </div>
            </div>
            
        </main>
    </body>
</html>