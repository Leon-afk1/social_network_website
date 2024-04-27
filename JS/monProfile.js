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
    response = response.trim();
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

function toggleLike(isLiked, userID, postID) {
    console.log("toggleLike function called with isLiked = " + isLiked + ", userID = " + userID + ", postID = " + postID);
    console.log("test1");
    alert("test2");
    // Récupérer l'élément image
    var image = document.getElementById("like-image_" + postID);
    
    // Utiliser $.post() pour envoyer une requête AJAX POST à like.php
    $.post("./AJAX/liker.php", {isLiked : isLiked, userID : userID, postID : postID}, function(data) {
        alert("test3");
        if(data=="Like added"){
            alert("Like ajouté");
            image.src = "./images/like.png";
        } else if(data=="Like removed"){
            alert("Like retiré");
            image.src = "./images/nolike.png";
        } else {
            alert("ca marche pas");
            console.error('Erreur lors de la requête AJAX');
        }
    });

    }

// Fonction pour envoyer un avertissement à l'utilisateur concerné par un post
function sendAvertissement(postId) {
    var avertissement = prompt("Veuillez saisir un avertissement à envoyer à l'utilisateur :", "Vous avez reçu un avertissement pour comportement inapproprié");
    var result ="";
    if (avertissement != null) {
        $.post('./AJAX/avertir.php', { postId: postId, avertissement: avertissement }, function(data) {
            result = data.trim();
            if (result  == "Avertissement envoyé") {
                alert("Avertissement envoyé avec succès!");
            } else {
                alert(result); // Afficher l'erreur retournée par la requête
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

// Fonction pour marquer un post comme sensible
function marquerSensible(postId) {
    var message = prompt("Veuillez saisir un message à afficher à l'utilisateur :", "Ce contenu a été marqué comme sensible car offensant");
    var result ="";
    if (message != null) {
        $.post('./AJAX/marquerSensible.php', { postId: postId, message: message }, function(data) {
            result = data.trim();
            if (result  == "Post marqué comme sensible") {
                alert("Post marqué comme sensible avec succès!");
            } else {
                alert(result); // Afficher l'erreur retournée par la requête
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
    var result ="";
    if (message != null) {
        $.post('./AJAX/retirerPost.php', { postId: postId, message: message }, function(data) {
            result = data.trim();
            if (result  == "Post retiré") {
                alert("Post retiré avec succès!");
            } else {
                alert(result); // Afficher l'erreur retournée par la requête
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

// Fonction pour marquer un post comme non sensible
function marquerNonSensible(postId) {
    var result ="";
    $.post('./AJAX/marquerNonSensible.php', { postId: postId }, function(data) {
        result = data.trim();
        if (result  == "Post marqué comme non sensible") {
            alert("Post marqué comme non sensible avec succès!");
        } else {
            alert(result); // Afficher l'erreur retournée par la requête
            console.error('Erreur lors de la requête AJAX');
        }
    });
}

// Fonction pour marquer un post comme non offensant
function marquerNonOffensant(postId) {
    var result ="";
    $.post('./AJAX/marquerNonOffensant.php', { postId: postId }, function(data) {
        result = data.trim(); 
        if (result  == "Post marqué comme non offensant") {
            alert("Post marqué comme non offensant avec succès!");
        } else {
            alert(result); // Afficher l'erreur retournée par la requête
            console.error('Erreur lors de la requête AJAX');
        }
    });
}

// Fonction pour signaler un post
function signalerPost(idPost, idUser) {
    var message = prompt("Veuillez saisir un message à envoyer à l'administrateur :", "Ce contenu a été signalé par un utilisateur");
    var result ="";
    if (message != null) {
        $.post('./AJAX/signalerPost.php', { idPost: idPost, message: message, idUser: idUser }, function(data) {
            result = data.trim(); 
            if (result  == "true") {
                alert("Post signalé avec succès!");
            } else {
                alert(result); // Afficher l'erreur retournée par la requête
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

