function supprimerFollowUser(id) {
    var data = new FormData();
    data.append('idToUnfollow', id);
    fetch('./AJAX/supprimerFollow.php', {
        method: 'POST',
        body: data
    }).then(response => response.text())
        .then(data => {
            if (data === "success") {
                document.getElementById(id).remove();
            } else {
                alert(data);
            }
        });
}

