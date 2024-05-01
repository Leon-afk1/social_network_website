$(document).ready(function() {
    // Chargement du contenu du feed par défaut au chargement de la page
    $.ajax({
        url: "./AJAX/loadRecentPosts.php",
        type: "GET",
        success: function(response) {
            $("#postsContainer").html(response);
            // alert(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert("Error: " + error);
        }
    });

    $("#bestPostsButton").click(function() {
        $.ajax({
            url: "./AJAX/loadBestPosts.php",
            type: "GET",
            success: function(response) {
                $("#postsContainer").html(response);
                $('#bestPostsButton').addClass('active');
                $('#recentPostsButton').removeClass('active');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert("Error: " + error);
            }
        });
    });

    $("#recentPostsButton").click(function() {
        $.ajax({
            url: "./AJAX/loadRecentPosts.php",
            type: "GET",
            success: function(response) {
                $("#postsContainer").html(response);
                $('#bestPostsButton').removeClass('active');
                $('#recentPostsButton').addClass('active');
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert("Error: " + error);
            }
        });
    });
});
