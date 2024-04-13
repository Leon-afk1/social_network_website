<?php
// Inclure les fonctions et les configurations nécessaires
require_once 'dataBaseFunctions.php';

// Vérifier si l'action est définie dans la requête POST
if (isset($_POST['postId'])) {
    $postId = $_POST['postId'];
    $userId = $_COOKIE['user_id']; // Supposons que vous stockez l'ID de l'utilisateur dans un cookie

    // Appeler la fonction likePost
    likePost($userId, $postId);
    
    // Récupérer le nombre de likes pour le post actuel
    $likesCount = getNumberLikes($postId);
    
    // Répondre à la requête AJAX avec un succès et le nombre de likes
    echo json_encode(['success' => true, 'likesCount' => $likesCount]);
    exit; // Arrêter l'exécution du script après la réponse
}

// Si postId n'est pas défini, répondre avec une erreur
echo json_encode(['success' => false, 'error' => 'Post ID not provided']);
