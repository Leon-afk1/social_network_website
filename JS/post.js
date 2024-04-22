
var page = 2; // Page actuelle des posts
var loading = false; // Variable pour empêcher le chargement multiple




function loadMorePosts(postId) {
    if (!loading) {
        loading = true;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                if (response.trim() !== '') {
                    var postsDiv = document.getElementById('reponses-container');
                    postsDiv.innerHTML += response;
                    page++; // Augmente le numéro de page
                }
                loading = false;
            }
        };
        xhr.open('GET', './AJAX/loadMoreReponse.php?page=' + page + '&postId=' + postId, true);
        xhr.send();
    }
}
