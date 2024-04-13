<?php
  
  $AccountStatus = CheckLogin();

  $currentURL = $_SERVER['REQUEST_URI'];

  $tryLogin = false;
  if (isset($_POST["usernameLogin"]) && isset($_POST["passwordLogin"])) {
    $tryLogin = true;
    $AccountStatus = CheckLogin();
    if ($AccountStatus["loginSuccessful"]){
      header("Location:./index.php");
      exit();
    }else{
      $erreur = $AccountStatus["error"];
      echo "<script>toggleLoginFormIfNeeded();</script>";
    }
  }
  if (!$AccountStatus["loginSuccessful"]){
    $newAccountStatus = array("Attempted" => false);

    $tryNewAccount = false;
    if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["username"]) && isset($_POST["date_naissance"]) && isset($_POST["adresse"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password_confirm"])) {
      $tryNewAccount = true;
      $newAccountStatus = register();
      if ($newAccountStatus["Successful"]){
        if (isset($_COOKIE['user_id'])){
          header("Location:./profile.php");
          exit();
        }else{
          header("Location:./index.php");
          exit();
        }
      }else{
        echo $newAccountStatus["ErrorMessage"];
      }
    }
  }
  

  if(isset($_COOKIE['user_id'])){
    $Infos = GetInfoProfile($_COOKIE['user_id']);
  }
?>

 <head>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
  </head>
  <body class="text-body bg-body" data-bs-theme="dark">


  <nav class="navbar navbar-expand-lg px-3 mb-3 mt-3 mx-3 sticky-top rounded-3 shadow outline-secondary text-bg-secondary">
    <div class="container-fluid ">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 mb-l-0 mb-m-0">
          <?php
            if(isset($_COOKIE['user_id'])){
              if ($Infos["avatar"] != NULL){
                echo "
                <li class='nav-item'>
                  <a class='nav-link active' aria-current='page' href='./profile.php?id=".$_COOKIE['user_id']."'> 
                    <img src='".$Infos["avatar"]."' class='avatar avatar-lg'>
                  </a>
                </li>";
                }
            }
          ?>
          <li class="nav-item"><button class="nav-link btn btn-link" onclick="window.location.href='./index.php'" aria-current="page">Home</button></li>
          <?php
            if(isset($_COOKIE['user_id'])){
              echo "<li class='nav-item'><button class='nav-link btn btn-link ' onclick='window.location.href=`./logout.php?redirect=$currentURL`' aria-current='page'>Logout</button></li>";
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='window.location.href=`./profile.php?id=".$_COOKIE['user_id']."`' aria-current='page'>Profile</button></li>";
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='window.location.href=`./poster.php`' aria-current='page'>Poster</button></li>";
              echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='#'>Statistique</a></li>";
            } else {
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='toggleLoginForm()' aria-current='page'>Login</button></li>";
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='toggleNewLoginForm()' aria-current='page'>Sign in</button></li>";
            }
          ?>
        </ul>
      </div>
      <div class="d-flex navbar-nav">
        <div id="suggestions2" class></div>     
        <form class="d-flex nav-item" role="search">
          <input id="suggestField2" class="form-control me-2 shadow" type="search" placeholder="Search" onkeyup="suggestFromInput_fetch(this.value)">
        </form>
      </div>
      <div class="nav-item ">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="recherche" id="rechercheUser" checked>
            <label class="form-check-label" for="rechercheUser">
              Utilisateur
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="recherche" id="recherchePost" >
            <label class="form-check-label" for="recherchePost">
              Post
            </label>
          </div>
        </div>
  </nav>  

  <main class="p-3 d-flex col-md-8 col-lg-8 mx-auto flex-column ">
    <div class="overlay" id="overlay" onclick="hideLoginForm(event)">
        <?php include ("BoutDePages/login.php"); ?>
    </div>
    <div class="overlay" id="overlay1" onclick="hideNewLoginForm(event)">
        <?php include ("BoutDePages/newlogin.php"); ?>
    </div>
  </main>
</body>

<script>

previousText2 = "";
timer2 = 0;

function TimerIncrease_fetch() {
  timer2+=200;
  setTimeout('TimerIncrease_fetch()',200);
}
TimerIncrease_fetch();

async function suggestFromInput_fetch(currentText) {
  if (document.getElementById("rechercheUser").checked){
    suggestNamesFromInput_fetch(currentText);
  }else{
    suggestPostsFromInput_fetch(currentText);
  }
}

async function suggestPostsFromInput_fetch(currentText) {
  autocomplete = document.getElementById('autocomplete');
  if (currentText != previousText2 && timer2 >= 200 ){
    var AJAXresult = await fetch("./BoutDePages/recherchePost.php?var=" + currentText);
    document.getElementById("suggestions2").innerHTML = await AJAXresult.text();

      previousText2 = currentText;
      timer2 = 0;
  }else {
    document.getElementById("suggestions2").innerHTML = ''; 
  }
}

async function suggestNamesFromInput_fetch(currentText) {

  if (currentText != previousText2 && timer2 >= 200 ){

    var AJAXresult = await fetch("./BoutDePages/rechercheCompte.php?var=" + currentText);
    document.getElementById("suggestions2").innerHTML = await AJAXresult.text();

      previousText2 = currentText;
      timer2 = 0;
  }else {
    document.getElementById("suggestions2").innerHTML = ''; 
  }
}

function toggleLoginForm() {
    var loginForm = document.getElementById("loginForm");
    var overlay = document.getElementById("overlay");
    var newLoginForm = document.getElementById("newLoginForm");
    var overlay1 = document.getElementById("overlay1");
    var mainContent = document.getElementById("mainContent"); 
    if (loginForm.style.display === "none") {
        loginForm.style.display = "block";
        overlay.style.display = "block";
        newLoginForm.style.display = "none";
        overlay1.style.display = "none";
        mainContent.classList.add("blur");
    } else {
        loginForm.style.display = "none";
        overlay.style.display = "none";
        mainContent.classList.remove("blur"); 
    }
}

function hideLoginForm(event) {
    var loginForm = document.getElementById("loginForm");
    var overlay = document.getElementById("overlay");
    var mainContent = document.getElementById("mainContent");
    if (!loginForm.contains(event.target)) {
        loginForm.style.display = "none";
        overlay.style.display = "none";
        mainContent.classList.remove("blur"); 
    }
}

document.addEventListener("DOMContentLoaded", function() {
    toggleLoginFormIfNeeded();
});

function toggleLoginFormIfNeeded() {
    <?php
    if (isset($erreur)) {
        echo "toggleLoginForm();";
    }
    ?>
}

function toggleNewLoginForm() {
    var newLoginForm = document.getElementById("newLoginForm");
    var overlay = document.getElementById("overlay1");
    var loginForm = document.getElementById("loginForm");
    var overlay1 = document.getElementById("overlay");
    var mainContent = document.getElementById("mainContent"); 
    if (newLoginForm.style.display === "none") {
        newLoginForm.style.display = "block";
        overlay.style.display = "block";
        loginForm.style.display = "none";
        overlay1.style.display = "none";
        mainContent.classList.add("blur"); 
    } else {
        newLoginForm.style.display = "none";
        overlay.style.display = "none";
        mainContent.classList.remove("blur"); 
    }
}

function hideNewLoginForm(event) {
    var newLoginForm = document.getElementById("newLoginForm");
    var overlay = document.getElementById("overlay1");
    var mainContent = document.getElementById("mainContent");
    if (!newLoginForm.contains(event.target)) {
        newLoginForm.style.display = "none";
        overlay.style.display = "none";
        mainContent.classList.remove("blur"); 
    }
}

document.addEventListener("DOMContentLoaded", function() {
    toggleNewLoginFormIfNeeded();
});

function toggleNewLoginFormIfNeeded() {
    <?php
    if (isset($newAccountStatus["Attempted"]) && $newAccountStatus["Attempted"]==true) {
        echo "toggleNewLoginForm();";
    }
    ?>
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
