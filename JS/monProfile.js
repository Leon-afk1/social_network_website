// Fonction pour basculer l'affichage du formulaire lié à un post
function toggleForm(postId) {
    var form = document.getElementById("postForm_" + postId);
    var button = document.getElementById("toggleFormButton_" + postId);

    if (form.style.display === "none") {
        $("#postForm_" + postId).slideToggle(); // Animation de glissement pour afficher le formulaire
        button.innerHTML = "Masquer le formulaire"; // Mettre à jour le texte du bouton
    } else {
        $("#postForm_" + postId).slideToggle(); // Animation de glissement pour masquer le formulaire
        button.innerHTML = "Afficher le formulaire"; // Mettre à jour le texte du bouton
    }
}

// Fonction pour rediriger vers un post spécifique
function sendTo(postId) {
    window.location.href = "./post.php?id=" + postId;
}

// Fonction asynchrone pour supprimer un post
async function supprimerPost(postId) {
    var supp = await fetch("./AJAX/supprimerPost.php?id=" + postId); // Effectuer la requête de suppression du post
    var response = await supp.text(); // Récupérer la réponse de la requête

    if (response == "Post supprimé") {
        var post = document.getElementById("post_" + postId);
        post.style.display = "none"; // Masquer le post supprimé
    } else {
        alert(response); // Afficher une alerte en cas d'erreur
    }
}

// Fonction pour basculer entre l'ajout d'une image et d'une vidéo dans un post
function toggleImageVideo(postId) {
    var image = document.getElementById("imageField_" + postId);
    var video = document.getElementById("videoField_" + postId);
    var imageRadio = document.getElementById("imageRadio_" + postId);
    var videoRadio = document.getElementById("videoRadio_" + postId);

    if (image.style.display === "none") {
        // Si l'image est masquée, afficher l'image et masquer la vidéo
        image.style.display = "block";
        video.style.display = "none";
        imageRadio.checked = true;
        image.querySelector('input').value = '';
    } else {
        // Sinon, masquer l'image et afficher la vidéo
        image.style.display = "none";
        video.style.display = "block";
        videoRadio.checked = true;
        video.querySelector('input').value = '';
    }
}

// Fonction pour basculer l'état du bouton "Like" d'un post
function toggleLike(button, postId) {
    $.post('like.php', { postId: postId }, function(data) {
        if (data.success) {
            // Mettre à jour l'image du bouton en fonction de l'état actuel
            if (data.liked) {
                button.src = 'like.png';
                button.alt = 'Liked';
            } else {
                button.src = 'nolike.png';
                button.alt = 'Not Liked';
            }

            // Mettre à jour le nombre de likes affiché en temps réel
            var likesCount = document.getElementById('likesCount-' + postId);
            if (likesCount) {
                likesCount.innerText = data.likesCount + " likes";
            }

            // Afficher un message de confirmation
            alert("Post liked successfully!");
        } else {
            // Gérer les erreurs de requête ici
            console.error('Erreur lors de la requête AJAX');
        }
    });
}

// Fonction pour envoyer un avertissement à l'utilisateur concerné par un post
function sendAvertissement(postId) {
    var avertissement = prompt("Veuillez saisir un avertissement à envoyer à l'utilisateur :", "Vous avez reçu un avertissement pour comportement inapproprié");
    if (avertissement != null) {
        $.post('./AJAX/avertir.php', { postId: postId, avertissement: avertissement }, function(data) {
            if (data  == "Avertissement envoyé") {
                alert("Avertissement envoyé avec succès!");
            } else {
                alert(data); // Afficher l'erreur retournée par la requête
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

// Fonction pour marquer un post comme sensible
function marquerSensible(postId) {
    var message = prompt("Veuillez saisir un message à afficher à l'utilisateur :", "Ce contenu a été marqué comme sensible car offensant");
    if (message != null) {
        $.post('./AJAX/marquerSensible.php', { postId: postId, message: message }, function(data) {
            if (data  == "Post marqué comme sensible") {
                alert("Post marqué comme sensible avec succès!");
            } else {
                alert(data); // Afficher l'erreur retournée par la requête
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

// Fonction pour basculer la visibilité d'un post sensible
function toggleVisibilitePostSensible(postId) {
    var visibilite = document.getElementById("postSensible_" + postId);

    if (visibilite.style.filter === "blur(0px)") {
        visibilite.style.filter = "blur(15px)";
    } else {
        visibilite.style.filter = "blur(0px)";
    }
}

// Fonction pour retirer un post
function retirerPost(postId) {
    var message = prompt("Veuillez saisir un message à afficher à l'utilisateur :", "Ce contenu a été retiré car il ne respecte pas les règles de la communauté");
    if (message != null) {
        $.post('./AJAX/retirerPost.php', { postId: postId, message: message }, function(data) {
            if (data  == "Post retiré") {
                alert("Post retiré avec succès!");
            } else {
                alert(data); // Afficher l'erreur retournée par la requête
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

// Fonction pour marquer un post comme non sensible
function marquerNonSensible(postId) {
    $.post('./AJAX/marquerNonSensible.php', { postId: postId }, function(data) {
        if (data  == "Post marqué comme non sensible") {
            alert("Post marqué comme non sensible avec succès!");
        } else {
            alert(data); // Afficher l'erreur retournée par la requête
            console.error('Erreur lors de la requête AJAX');
        }
    });
}

// Fonction pour marquer un post comme non offensant
function marquerNonOffensant(postId) {
    $.post('./AJAX/marquerNonOffensant.php', { postId: postId }, function(data) {
        if (data  == "Post marqué comme non offensant") {
            alert("Post marqué comme non offensant avec succès!");
        } else {
            alert(data); // Afficher l'erreur retournée par la requête
            console.error('Erreur lors de la requête AJAX');
        }
    });
}
