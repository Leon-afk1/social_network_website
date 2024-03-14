<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>

</head>

<body>
<div id="MainContainer">

    <div class="header">
        <hr>
        <h1>Create an account</h1>
        <hr>
    </div>

<?php
    //Verify data
    $display = false;
    if(isset($_POST["mail"]) && isset($_POST["pass"]) && isset($_POST["pass_confirmation"])){
        if(($_POST["pass"]==$_POST["pass_confirmation"])){
            $display = true;
        }
        $display = true;
    }

    if($display==false){
?>

    <form action="#" method="#post">
        <div>
            <label for="mail">Email :</label>
            <input type="email" name="mail">
        </div>
        <div>
            <label for="password">Password : </label>
            <input type="password" name="pass"></textarea>
        </div>
        <div>
            <label for="password">Confirm password : </label>
            <input type="password" name="pass_confirmation"></textarea>
        </div>

        <!-- rajouter date de naissance-->

        <div class="formbutton">
            <button type="submit">Next</button>
        </div>

        <!-- amener sur une page pour choisir son pseudo-->
    </form>

<?php

    } //end of if
    else{

?>
    <h3>Inscription réussie</h3>
    <p>blabla</p>
    
<?php
    } //end of else
?>
    <div class="footer">
        <hr>
        <p> ©Y Social Media. All rights reserved</p>
        <hr>
    </div>
</div>
</body>

</html>