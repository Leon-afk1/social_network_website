function toggleForm(postId) {
    var form = document.getElementById("postForm_" + postId);
    var button = document.getElementById("toggleFormButton_" + postId);

    if (form.style.display === "none") {
        form.style.display = "block";
        button.innerHTML = "Masquer le formulaire";
    } else {
        form.style.display = "none";
        button.innerHTML = "Afficher le formulaire";
    }
}

function sendTo(postId) {
    window.location.href = "./post.php?id=" + postId;
}

async function supprimerPost(postId) {
    var supp = await fetch("./AJAX/supprimerPost.php?id=" + postId);
    var response = await supp.text();
    if (response == "Post supprimé") {
        var post = document.getElementById("post_" + postId);
        post.style.display = "none";
    } else {
        alert(response);
    }
}

function toggleImageVideo(postId) {
    var image = document.getElementById("imageField_" + postId);
    var video = document.getElementById("videoField_" + postId);
    var imageRadio = document.getElementById("imageRadio_" + postId);
    var videoRadio = document.getElementById("videoRadio_" + postId);

    if (image.style.display === "none") {
        image.style.display = "block";
        video.style.display = "none";
        imageRadio.checked = true;
        image.querySelector('input').value = '';
    } else {
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

function sendAvertissement(postId) {
    var avertissement = prompt("Veuillez saisir un avertissement à envoyer à l'utilisateur :", "Vous avez reçu un avertissement pour comportement inapproprié");
    if (avertissement != null) {
        $.post('./AJAX/avertir.php', { postId: postId, avertissement: avertissement }, function(data) {
            if (data  == "Avertissement envoyé") {
                alert("Avertissement envoyé avec succès!");
            } else {
                alert(data);
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

function marquerSensible(postId){
    var message = prompt("Veuillez saisir un message à afficher à l'utilisateur :", "Ce contenu a été marqué comme sensible car offensant");
    if (message != null) {
        $.post('./AJAX/marquerSensible.php', { postId: postId, message: message }, function(data) {
            if (data  == "Post marqué comme sensible") {
                alert("Post marqué comme sensible avec succès!");
            } else {
                alert(data);
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }
}

function toggleVisibilitePostSensible(postId) {
    var visibilite = document.getElementById("postSensible_" + postId);

    if (visibilite.style.filter === "blur(0px)") {
        visibilite.style.filter = "blur(15px)";
    } else {
        visibilite.style.filter = "blur(0px)";
    }
}

function retirerPost(postId) {
    var message = prompt("Veuillez saisir un message à afficher à l'utilisateur :", "Ce contenu a été retiré car il ne respecte pas les règles de la communauté");
    if (message != null) {
        $.post('./AJAX/retirerPost.php', { postId: postId, message: message }, function(data) {
            if (data  == "Post retiré") {
                alert("Post retiré avec succès!");
            } else {
                alert(data);
                console.error('Erreur lors de la requête AJAX');
            }
        });
    }

}

function marquerNonSensible(postId) {
    $.post('./AJAX/marquerNonSensible.php', { postId: postId }, function(data) {
        if (data  == "Post marqué comme non sensible") {
            alert("Post marqué comme non sensible avec succès!");
        } else {
            alert(data);
            console.error('Erreur lors de la requête AJAX');
        }
    });
}

function marquerNonOffensant(postId) {
    $.post('./AJAX/marquerNonOffensant.php', { postId: postId }, function(data) {
        if (data  == "Post marqué comme non offensant") {
            alert("Post marqué comme non offensant avec succès!");
        } else {
            alert(data);
            console.error('Erreur lors de la requête AJAX');
        }
    });
}