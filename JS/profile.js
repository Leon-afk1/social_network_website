var page = 2; // Page actuelle des posts
var loading = false; // Variable pour empêcher le chargement multiple

// Écouter l'événement de défilement de la fenêtre
window.addEventListener('scroll', function() {
    // Vérifier si le bas de la fenêtre atteint le bas de la page
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
        loadMorePosts(); // Appeler la fonction pour charger plus de posts
    }
});

// Fonction pour charger plus de posts
function loadMorePosts() {
    if (!loading) { // Vérifier si le chargement est déjà en cours
        loading = true; // Mettre la variable de chargement à true pour empêcher les chargements multiples
        var xhr = new XMLHttpRequest(); // Créer un nouvel objet XMLHttpRequest
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) { // Vérifier si la requête est terminée et réussie
                var response = xhr.responseText; // Récupérer la réponse de la requête
                if (response.trim() !== '') { // Vérifier si la réponse n'est pas vide
                    var postsDiv = document.getElementById('posts'); // Sélectionner la div des posts
                    postsDiv.innerHTML += response; // Ajouter les nouveaux posts à la fin de la liste existante
                    page++; // Augmenter le numéro de page pour la prochaine requête
                }
                loading = false; // Remettre la variable de chargement à false pour autoriser les prochains chargements
            }
        };
        // Envoyer une requête GET pour charger plus de posts en utilisant AJAX
        xhr.open('GET', './AJAX/loadMorePosts.php?page=' + page, true);
        xhr.send(); // Envoyer la requête
    }
}
