<?php

$currentURL = $_SERVER['REQUEST_URI'];

$erreur = "";
$newAccountStatus = array("Attempted" => false);

$tryLogin = false;
if (isset($_POST["usernameLogin"]) && isset($_POST["passwordLogin"])) {
    $tryLogin = true;
    $AccountStatus = $SQLconn->loginStatus; 
    if ($AccountStatus->loginSuccessful) {
        header("Location: $currentURL");
        exit();
    }else{
        $erreur = $AccountStatus->errorText;
    }
}

if (!$SQLconn->loginStatus->loginSuccessful) { 
    $tryNewAccount = false;
    if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["username"]) && isset($_POST["date_naissance"]) && isset($_POST["adresse"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password_confirm"])) {
        $tryNewAccount = true;
        $newAccountStatus = $SQLconn->loginStatus->register($SQLconn);
        if ($newAccountStatus["Successful"]) {
            header("Location: $currentURL");
            exit();
        } else {
            echo $newAccountStatus["ErrorMessage"];
        }
    }
}

$nbNotifications = 0;
if (isset($_COOKIE['user_id'])) {
    $Infos = $SQLconn->profile->GetInfoProfile($_COOKIE['user_id']);
    $nbNotifications = $SQLconn->notification->getNbNotifications($_COOKIE['user_id']);
}

$executeToggleLoginFormIfNeeded = $erreur !== "";
$executeToggleNewLoginFormIfNeeded = $newAccountStatus["Attempted"] && !$newAccountStatus["Successful"];
?>



<head>
    <link rel="stylesheet" href="./CSS/header.css">
    <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
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
          <?php
            if(isset($_COOKIE['user_id'])){
              $ban = $SQLconn->profile->checkBan($_COOKIE['user_id']);
              if ($ban){
                echo "<li class='nav-item'><button class='nav-link btn btn-link ' onclick='window.location.href=`./logout.php?redirect=$currentURL`' aria-current='page'>Logout</button></li>";
                echo "<li class='nav-item'><form class='nav-item' action='./profile.php?id=".$_COOKIE['user_id']."' method='post'>
                          <input type='hidden' name='statistiques' value'true'>
                          <button type='submit' class='nav-link btn btn-link'>Statistiques</button>
                      </form>
                    </li>";
                echo  "<li class='nav-item position-relative'><button class='nav-link btn btn-link' onclick='window.location.href=`./notification.php?id=".$_COOKIE['user_id']."`' aria-current='page'>Notifications
                        <span class='custom-badge position-absolute top-50 start-100 translate-middle badge rounded-pill bg-danger' id='notif'>
                        $nbNotifications
                        <span class='visually-hidden'>unread messages</span>
                      </span>
                    </button></li>";
              } else {
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='window.location.href=`./index.php`' aria-current='page'>Home</button></li>";
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='window.location.href=`./poster.php`' aria-current='page'>Poster</button></li>";
              echo "<li class='nav-item'><form class='nav-item' action='./profile.php?id=".$_COOKIE['user_id']."' method='post'>
                          <input type='hidden' name='statistiques' value'true'>
                          <button type='submit' class='nav-link btn btn-link'>Statistiques</button>
                      </form>
                    </li>";
              echo  "<li class='nav-item position-relative' ><button class='nav-link btn btn-link' onclick='window.location.href=`./notification.php?id=".$_COOKIE['user_id']."`' aria-current='page'>Notifications
                    <span class='custom-badge position-absolute top-50 start-100 translate-middle badge rounded-pill bg-danger' id='notif'>
                    $nbNotifications
                    <span class='visually-hidden'>unread messages</span>
                  </span>
              </button></li>";
              }
            } else {
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='window.location.href=`./index.php`' aria-current='page'>Home</button></li>";
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='toggleLoginForm()' aria-current='page'>Login</button></li>";
              echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='toggleNewLoginForm()' aria-current='page'>Sign in</button></li>";
            }
          ?>
        </ul>
      </div>
      <?php 

        if ((isset($_COOKIE['user_id']) && !$ban) || !isset($_COOKIE['user_id'])){         

      ?>
      <div class="d-flex navbar-nav">
        <?php if(isset($_COOKIE['user_id'])){ ?>
        <div id="logOut" class="nav-item">
          <li class='nav-item'><button class='nav-link btn btn-link ' onclick='window.location.href=`./logout.php?redirect=<?php echo $currentURL; ?>`' aria-current='page'>Logout</button></li>
        </div>
        <?php } ?>
        <form class="d-flex nav-item position-relative" role="search">
          <input id="suggestField2" class="form-control me-2 shadow" type="search" placeholder="Search" onkeyup="suggestFromInput_fetch(this.value)">
          <div class="position-absolute top-100 start-50 translate-middle-x overflow-auto" style="max-height: 200px; background-color: black; left: 50%; transform: translateX(-50%); top: calc(100%); width: 100%;" id="suggestions2"></div>      </form>
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
      <?php } ?>
      
</nav>

<main class="p-3 d-flex col-md-8 col-lg-8 mx-auto flex-column ">
    <div class="overlay" id="overlay" onclick="hideLoginForm(event)">
        <?php include ("./AJAX/login.php"); ?>
    </div>
    <div class="overlay" id="overlay1" onclick="hideNewLoginForm(event)">
        <?php include ("./AJAX/newlogin.php"); ?>
    </div>
</main>


<script src="./JS/header.js"></script>
<script>
var executeToggleLoginFormIfNeeded = <?php echo $executeToggleLoginFormIfNeeded ? 'true' : 'false'; ?>;
var executeToggleNewLoginFormIfNeeded = <?php echo $executeToggleNewLoginFormIfNeeded ? 'true' : 'false'; ?>;
var nbNotifications = <?php echo $nbNotifications; ?>;
updateNotificationDisplay(nbNotifications);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
