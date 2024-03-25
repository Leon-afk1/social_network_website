<?php
    include ("functions.php");
    //on récupère les données du formulaire
    $message = $_POST["postContent"];
    $image = $_POST["postImage"];
    $video = $_POST["postVideoLink"];
    $videoEmbed = __getYouTubeEmbeddedURL($video);
    $embed_video = '<iframe width="560" height="315" src="'.$videoEmbed.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Valider votre message </title>
        <link rel="stylesheet" href="styles.css">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /> <!-- from stackoverflow -->
    </head>
    <body>
        Votre message :
        <br>
        <?php echo $message;
        echo "<br>";
        echo $embed_video;
        ?>
    </body>
    <br>
    <button type = "button" class="btn btn-primary" onclick = "history.back()">Modifier</button>
    <button type = "submit" class="btn btn-primary">Valider et poster</button>
    <!-- IMPORTANT -->
    <!-- ajouter le post dans la db puis rediriger vers la page d'accueil-->


</html>