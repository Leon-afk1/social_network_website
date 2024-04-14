<?php
$following = getFollowed($_COOKIE['user_id']);

?>

<link rel="stylesheet" href="styles.css">
<div class="card outline-secondary rounded-3">
    <div class="text-center card-header">
        <h1 class="card-title"><?php 
        if ($Infos["avatar"] != NULL){
            echo "<img src='".$Infos["avatar"]."' class='avatar avatar-xxl'>";
        }
         ?>
        </h1>
        <?php echo $Infos["username"]?>

        <br>

    </div>
    <div class="card-body">
        <?php 
            foreach ($following as $follow){
                echo "<div class='card outline-secondary rounded-3 item-center'>";
                echo   "<div class='row'>";
                echo    "<div class='col text-start'>";
                echo      "<a class='nav-link active' aria-current='page' href='./profile.php?id=".$follow["id_utilisateur"]."'> 
                            <img src='".$follow["avatar"]."' class='avatar avatar-lg'>
                            <label for='nom'>". $follow["username"]."</label>
                           </a>";
                echo    "</div>";
                echo    "<div class='col text-center'>";
                echo        $follow["description"];
                echo    "</div>";
                echo    "<div class='col text-end'>";
                echo        "<form action='./profile.php?id=".$follow["id_utilisateur"]."' method='post'>";
                echo            "<input type='hidden' name='unfollow' value='true'>";
                echo            "<button type='submit' class='btn btn-danger'>Unfollow</button>";
                echo        "</form>";
                echo    "</div>";
                echo   "</div>";
                echo "</div>";
                echo "<br>";
            }
        ?>
    </div>