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

async function unfollowUser(id) {
    var unfollow = await fetch('./AJAX/unfollow.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'idToUnfollow=' + id,
    });
    var response = await unfollow.text();
    if (response === "success") {
        var user = document.getElementById(id);
        user.remove();
    } else {
        alert(response);
    }
    return response;
}