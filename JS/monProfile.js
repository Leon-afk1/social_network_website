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

function toggleLike(userID, postID){
    // Récupérer l'élément image
    var image = document.getElementById("like-image_" + postID);

    var isLiked = false;
    // Vérifier si le post est déjà liké
    // Récupérer l'attribut data-liked de l'élément du bouton de like
    var button = document.getElementById("like-button_" + postID);
    var dataLiked = button.getAttribute("data-liked");

    // Récupérer le nombre de likes
    var likeCount = document.getElementById("like-count_" + postID);
    var currentLikes = parseInt(likeCount.innerHTML);

    // Si le post est déjà liké, on envoie une requête pour retirer le like
    if(dataLiked == "1"){
        isLiked = 1;
    } else {
        isLiked = 0;
    }

    console.log(isLiked);

    var result = "";
    // Utiliser $.post() pour envoyer une requête AJAX POST à like.php
    $.post("./AJAX/liker.php", {isLiked : isLiked, userID : userID, postID : postID}, function(data) {
        result = data.trim();
        if(result=="Like added"){
            button.setAttribute("data-liked", "1");

            currentLikes++;
            likeCount.innerHTML = currentLikes;
            
            image.src = "./icon/heart_red.png";
        } else if(result=="Like removed"){
            button.setAttribute("data-liked", "0");

            currentLikes--;
            likeCount.innerHTML = currentLikes;

            image.src = "./icon/heart_empty.png";
        } else {
            alert(result); // Afficher l'erreur retournée par la requête
            // console.error('Erreur lors de la requête AJAX');
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
            if (result  == "Avertissement envoyé.") {
                alert("Avertissement envoyé avec succès!");
            } else {
                alert(result); // Afficher l'erreur retournée par la requête
                console.error(result);
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
            if (result  == "Post marqué sensible") {
                var message = document.getElementById("postSensibleMessage_" + postId);
                message.style.display = "block";
                var post = document.getElementById("postSensible_" + postId);
                post.style.filter = "blur(15px)";
            } else {
                alert(result); // Afficher l'erreur retournée par la requête
                console.error(result);
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
            var message = document.getElementById("postSensibleMessage_" + postId);
            message.style.display = "none";
            var post = document.getElementById("postSensible_" + postId);
            post.style.filter = "blur(0px)";
        } else {
            alert(result); // Afficher l'erreur retournée par la requête
            console.error('Erreur lors de la requête AJAX');
        }
    });
}

// Fonction pour basculer l'état sensible d'un post
function toggleSensible(postId){
    var message = document.getElementById("postSensibleMessage_" + postId);
    var button = document.getElementById("marquerSensible" + postId);
    if (message.style.display === "none") {
        button.innerHTML = "Marquer comme non sensible";
        marquerSensible(postId);
    } else {
        marquerNonSensible(postId);
        button.innerHTML = "Marquer comme sensible";
    }

}

function toggleVisibilitePostSensible(postId){
    var post = document.getElementById("postSensible_" + postId);
    var button = document.getElementById("voirSensible" + postId);
    if (post.style.filter === "blur(15px)") {
        post.style.filter = "blur(0px)";
        button.innerHTML = "Masquer le contenu sensible";
    } else {
        post.style.filter = "blur(15px)";
        button.innerHTML = "Voir";
    }
}

// Fonction pour retirer un post
function retirerPost(postId) {
    var message = prompt("Veuillez saisir un message à afficher à l'utilisateur :", "Ce contenu a été retiré car il ne respecte pas les règles de la communauté");
    var result ="";
    if (message != null) {
        $.post('./AJAX/retirerPost.php', { postId: postId, message: message }, function(data) {
            result = data.trim();
            if (result  == "Post retiré.") {
                var post = document.getElementById("postAlert_" + postId);
                post.style.display = "block";
                var message = document.getElementById("postSensibleMessage_" + postId);
                message.style.display = "none";
                var post = document.getElementById("postSensible_" + postId);
                post.style.filter = "blur(0px)";
            } else {
                alert(result); // Afficher l'erreur retournée par la requête
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

// Fonction pour marquer un post comme non offensant
function marquerNonOffensant(postId) {
    var result ="";
    $.post('./AJAX/marquerNonOffensant.php', { postId: postId }, function(data) {
        result = data.trim(); 
        if (result  == "Post marqué comme non offensant") {
            var post = document.getElementById("postAlert_" + postId);
            post.style.display = "none";
        } else {
            alert(result); // Afficher l'erreur retournée par la requête
            console.error('Erreur lors de la requête AJAX');
        }
    });
}

function toggleOffense(postId){
    var message = document.getElementById("postAlert_" + postId);
    var button = document.getElementById("retirerPost" + postId);
    if (message.style.display === "none") {
        button.innerHTML = "Marquer comme non offensant";
        retirerPost(postId);
    } else {
        marquerNonOffensant(postId);
        button.innerHTML = "Marquer comme offensant";
    }

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

function likeUnlike(postId, userId) {
    alert("likeUnlike function called with postId = " + postId + ", userId = " + userId);

}
