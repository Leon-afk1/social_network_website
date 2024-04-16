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
    var supp = await fetch("./BoutDePages/supprimerPost.php?id=" + postId);
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
            // Gérez les erreurs de requête ici
            console.error('Erreur lors de la requête AJAX');
        }
    });
}