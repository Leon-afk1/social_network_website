<?php 
include ("../loc.php");
$postId=45;

$postInfos = getPostInfos($postId);
$lienVideo = $postInfos["lien_video"];
echo "Auteur : " . $postInfos["auteur"];
echo "<br>";
echo "Date de publication : " . $postInfos["date"];
echo "<br>";

echo"Contenu : " . $postInfos["contenu"];
echo "<br>";

echo "<br>";

echo "Lien de la vidéo rattachée : " . $lienVideo;
echo "<br>";

echo "Number of likes :";
echo "<br>";
$numberLikes = getNumberLikes($postId);
echo $numberLikes;
echo "<br>";

echo "Affichage de la vidéo :";
//from stackoverflow, convert youtube link to embed link
$convertedURL = str_replace("watch?v=","embed/", $lienVideo);
//display the video
$videoEmbedDisplay = '<iframe width="560" height="315" src="'.$convertedURL.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
echo $videoEmbedDisplay;
echo "<br>";




// SELECT COUNT(*) FROM likes WHERE id_utilisateur=32;
// INSERT INTO `likes` (`id_like`, `id_post`, `id_utilisateur`) VALUES (NULL, '45', '32');

?>