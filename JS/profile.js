
var page = 2; // Page actuelle des posts
var loading = false; // Variable pour empêcher le chargement multiple

window.addEventListener('scroll', function() {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
        loadMorePosts();
    }
});

function loadMorePosts() {
    if (!loading) {
        loading = true;
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                if (response.trim() !== '') {
                    var postsDiv = document.getElementById('posts');
                    postsDiv.innerHTML += response;
                    page++; // Augmente le numéro de page
                }
                loading = false;
            }
        };
        xhr.open('GET', './BoutDePages/loadMorePosts.php?page=' + page, true);
        xhr.send();
    }
}
