// Fonction pour supprimer le suivi d'un utilisateur
function supprimerFollowUser(id) {
    // Créer un nouvel objet FormData pour envoyer les données
    var data = new FormData();
    var response = "";
    data.append('idToUnfollow', id);
    // Effectuer une requête fetch vers le script PHP de suppression du suivi
    fetch('./AJAX/supprimerFollow.php', {
        method: 'POST',
        body: data
    }).then(response => response.text()) // Récupérer la réponse de la requête sous forme de texte
        .then(data => {
            response = data.trim();
            // Vérifier si la réponse est "success"
            if (response === "success") {
                // Supprimer l'élément HTML correspondant à l'utilisateur suivi
                document.getElementById(id).remove();
            } else {
                // Afficher une alerte avec le message d'erreur
                alert(response);
            }
        });
}

// Fonction asynchrone pour arrêter de suivre un utilisateur
async function unfollowUser(id) {
    var response = "";
    // Effectuer une requête fetch asynchrone vers le script PHP d'arrêt du suivi
    var unfollow = await fetch('./AJAX/unfollow.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'idToUnfollow=' + id,
    });
    // Attendre la réponse de la requête et la récupérer sous forme de texte
    var response = await unfollow.text();
    // Vérifier si la réponse est "success"
    response = response.trim();
    if (response === "success") {
        // Supprimer l'élément HTML correspondant à l'utilisateur suivi
        var user = document.getElementById(id);
        user.remove();
    } else {
        // Afficher une alerte avec le message d'erreur
        alert(response);
    }
    // Retourner la réponse de la requête
    return response;
}
