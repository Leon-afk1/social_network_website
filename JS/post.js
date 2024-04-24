var page = 2; // Page actuelle des posts
var loading = false; // Variable pour empêcher le chargement multiple

// Fonction pour charger plus de posts
function loadMorePosts(postId) {
    if (!loading) { // Vérifier si le chargement est déjà en cours
        loading = true; // Mettre la variable de chargement à true pour empêcher les chargements multiples
        var xhr = new XMLHttpRequest(); // Créer un nouvel objet XMLHttpRequest
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) { // Vérifier si la requête est terminée et réussie
                var response = xhr.responseText; // Récupérer la réponse de la requête
                if (response.trim() !== '') { // Vérifier si la réponse n'est pas vide
                    var postsDiv = document.getElementById('reponses-container'); // Sélectionner la div des posts
                    postsDiv.innerHTML += response; // Ajouter les nouveaux posts à la fin de la liste existante
                    page++; // Augmenter le numéro de page pour la prochaine requête
                }
                loading = false; // Remettre la variable de chargement à false pour autoriser les prochains chargements
            }
        };
        // Envoyer une requête GET pour charger plus de posts en utilisant AJAX
        xhr.open('GET', './AJAX/loadMoreReponse.php?page=' + page + '&postId=' + postId, true);
        xhr.send(); // Envoyer la requête
    }
}
