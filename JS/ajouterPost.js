
//Lorsque le DOM est entièrement chargé, exécuter la fonction 

document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les éléments du formulaire
    var imageRadio = document.getElementById('imageRadio');
    var videoRadio = document.getElementById('videoRadio');
    var imageField = document.getElementById('imageField');
    var videoField = document.getElementById('videoField');

    // Ajouter un écouteur d'événements pour le clic sur le bouton radio "Image"
    imageRadio.addEventListener('click', function() {
        // Afficher le champ de saisie pour l'image et masquer le champ de saisie pour la vidéo
        imageField.style.display = 'block';
        videoField.style.display = 'none';
        // Réinitialiser la valeur du champ de saisie pour la vidéo
        videoField.querySelector('input').value = '';
    });

    // Ajouter un écouteur d'événements pour le clic sur le bouton radio "Vidéo"
    videoRadio.addEventListener('click', function() {
        // Masquer le champ de saisie pour l'image et afficher le champ de saisie pour la vidéo
        imageField.style.display = 'none';
        videoField.style.display = 'block';
        // Réinitialiser la valeur du champ de saisie pour l'image
        imageField.querySelector('input').value = '';
    });
});
