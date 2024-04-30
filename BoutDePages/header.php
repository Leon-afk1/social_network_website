<?php
// Récupération de l'URL actuelle
$currentURL = $_SERVER['REQUEST_URI'];

// Initialisation des variables
$erreur = ""; // Variable pour stocker les messages d'erreur
$newAccountStatus = array("Attempted" => false); // Statut de la tentative de création de compte

// Vérification de la tentative de connexion
$tryLogin = false;
if (isset($_POST["usernameLogin"]) && isset($_POST["passwordLogin"])) {
    $tryLogin = true;
    $AccountStatus = $SQLconn->loginStatus; // Statut de connexion de l'utilisateur
    if ($AccountStatus->loginSuccessful) {
        // Redirection si la connexion est réussie
        header("Location: $currentURL");
    } else {
        $erreur = $AccountStatus->errorText; // Stockage du message d'erreur en cas d'échec de connexion
    }
}

// Vérification de la tentative de création de compte
if (!$SQLconn->loginStatus->loginSuccessful) { 
    $tryNewAccount = false;
    if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["username"]) && isset($_POST["date_naissance"]) && isset($_POST["adresse"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password_confirm"])) {
        $tryNewAccount = true;
        // Tentative de création de compte et récupération du statut de la tentative
        $newAccountStatus = $SQLconn->loginStatus->register($SQLconn);
        if ($newAccountStatus["Successful"]) {
            // Redirection si la création de compte est réussie
            header("Location: $currentURL");
            exit();
        } else {
            echo $newAccountStatus["ErrorMessage"]; // Affichage du message d'erreur en cas d'échec de création de compte
        }
    }
}

// Récupération du nombre de notifications si l'utilisateur est connecté
$nbNotifications = 0;
if (isset($_COOKIE['user_id'])) {
    // Récupération des informations de profil de l'utilisateur
    $Infos = $SQLconn->profile->GetInfoProfile($_COOKIE['user_id']);
    // Récupération du nombre de notifications de l'utilisateur
    $nbNotifications = $SQLconn->notification->getNbNotifications($_COOKIE['user_id']);
}

// Déclenchement du formulaire de connexion en cas d'erreur
$executeToggleLoginFormIfNeeded = $erreur !== "";
// Déclenchement du formulaire de création de compte en cas de tentative infructueuse
$executeToggleNewLoginFormIfNeeded = $newAccountStatus["Attempted"] && !$newAccountStatus["Successful"];
?>



<head>
    <!-- Inclusion du fichier CSS pour le style du header -->
    <link rel="stylesheet" href="./CSS/header.css">
    <!-- Définition de l'icône du site -->
    <link rel="shortcut icon" type="image/x-icon" href="./images/favicon.ico" />
    <!-- Inclusion du framework FastBootstrap pour les styles -->
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
</head>

<body class="text-body bg-body" data-bs-theme="dark">
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg px-3 mb-3 mt-3 mx-3 sticky-top rounded-3 shadow outline-secondary text-bg-secondary">
        <div class="container-fluid ">
            <!-- Bouton de navigation pour les écrans mobiles -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 mb-l-0 mb-m-0">
                    <!-- Affichage du profil de l'utilisateur s'il est connecté -->
                    <?php
                    if (isset($_COOKIE['user_id'])) {
                        if ($Infos["avatar"] != NULL) {
                            echo "
                            <li class='nav-item'>
                                <a class='nav-link active' aria-current='page' href='./profile.php?id=" . $_COOKIE['user_id'] . "'> 
                                    <img src='" . $Infos["avatar"] . "' class='avatar avatar-lg'>
                                </a>
                            </li>";
                        }
                    }
                    ?>
                    <!-- Affichage des liens de navigation en fonction de la connexion de l'utilisateur -->
                    <?php
                    if (isset($_COOKIE['user_id'])) {
                        $ban = $SQLconn->profile->checkBan($_COOKIE['user_id']);
                        if ($ban) {
                            // Si l'utilisateur est banni, afficher les liens de déconnexion et de notification
                            echo "<li class='nav-item'>
                                        <form class='nav-item' action='./profile.php?id=" . $_COOKIE['user_id'] . "' method='post' id='statistiquesForm'>
                                            <input type='hidden' name='statistiques' value='true'>
                                            <a onclick='submitForm()'>
                                                <img src='./icon/statistics.png' alt='Statistiques' width='30' height='30' title='Statistiques'>
                                            </a>
                                        </form>
                                    </li>";
                            echo "<li class='nav-item position-relative'><a class='nav-link' href='./notification.php?id=" . $_COOKIE['user_id'] . "' aria-current='page'>
                                    <img src='./icon/bell.png' alt='Notification' width='auto' height='30' class='ms-2'> 
                                    <span class='custom-badge position-absolute top-50 start-100 translate-middle badge rounded-pill bg-danger' id='notif'>
                                        $nbNotifications
                                        <span class='visually-hidden'>unread messages</span>
                                    </span>
                                </a>
                            </li>";
                        } else {
                            // Si l'utilisateur n'est pas banni, afficher les liens de navigation normaux
                            echo "<li class='nav-item'><a class='nav-link' href='./index.php' aria-current='page'><img src='./icon/home.png' alt='Home' width='30' height='30'></a></li>";
                            echo "<li class='nav-item'><a class='nav-link' href='./poster.php' aria-current='page'><img src='./icon/poster.png' alt='Poster' width='30' height='30'></a></li>";
                            echo "<li class='nav-item'>
                                        <form class='nav-item' action='./profile.php?id=" . $_COOKIE['user_id'] . "' method='post' id='statistiquesForm'>
                                            <input type='hidden' name='statistiques' value='true'>
                                            <a href='#' onclick='submitForm()'>
                                                <img src='./icon/statistics.png' alt='Statistiques' width='30' height='30' title='Statistiques'>
                                            </a>
                                        </form>
                                    </li>";
                            echo "<li class='nav-item position-relative'><a class='nav-link' href='./notification.php?id=" . $_COOKIE['user_id'] . "' aria-current='page'>
                                    <img src='./icon/bell.png' alt='Notification' width='auto' height='30' class='ms-2'> 
                                    <span class='custom-badge position-absolute top-50 start-100 translate-middle badge rounded-pill bg-danger' id='notif'>
                                        $nbNotifications
                                        <span class='visually-hidden'>unread messages</span>
                                    </span>
                                </a>
                            </li>";
                            
                        }
                    } else {
                        // Si l'utilisateur n'est pas connecté, afficher les liens de navigation pour la page d'accueil, la connexion et l'inscription
                        echo "<li class='nav-item'><a class='nav-link' href='./index.php' aria-current='page'><img src='./icon/home.png' alt='Home' width='30' height='30'></a></li>";
                        echo "<li class='nav-item'>
                                    <a class='nav-link' aria-current='page'>
                                        <img src='./icon/account.png' alt='Login' width='30' height='30' onclick='toggleLoginForm()' aria-current='page'>
                                    </a>
                                    </li>
                                <li class='nav-item'>
                                    <img src='./icon/register.png' alt='Sign in' width='30' height='30' onclick='toggleNewLoginForm()' aria-current='page'>
                                </li>";

                    }
                    ?>
                </ul>
            
                <?php

                // Si l'utilisateur est connecté ou non banni, afficher la barre de recherche
                if ((isset($_COOKIE['user_id']) && !$ban) || !isset($_COOKIE['user_id'])) { ?>
                    <div class="d-flex navbar-nav">
                        <?php if (isset($_COOKIE['user_id'])) { ?>
                            <div id="logOut" class="nav-item">
                                <li class='nav-item'>
                                    <a href='./logout.php?redirect=<?php echo $currentURL; ?>' class='nav-link'>
                                        <img src='./icon/logout.png' alt='Déconnexion' width='auto' height='30' class='me-2'>
                                    </a>
                                </li>
                            </div>
                        <?php } ?>
                        <!-- Formulaire de recherche -->
                        <form class="d-flex nav-item position-relative" role="search">
                            <input id="suggestField2" class="form-control me-2 shadow" type="search" placeholder="Search" onkeyup="suggestFromInput_fetch(this.value)">
                            <!-- Zone pour afficher les suggestions de recherche -->
                            <div class="position-absolute top-100 start-50 translate-middle-x overflow-auto" style="max-height: 200px; background-color: black; left: 50%; transform: translateX(-50%); top: calc(100%); width: 100%;" id="suggestions2"></div>
                        </form>
                    </div>
                    <!-- Options de recherche -->
                    <div class="nav-item ">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="recherche" id="rechercheUser" checked>
                            <label class="form-check-label" for="rechercheUser">
                                Utilisateur
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="recherche" id="recherchePost">
                            <label class="form-check-label" for="recherchePost">
                                Post
                            </label>
                        </div>
                    </div>

                <?php }else{
                    ?>
                        <div class="d-flex navbar-nav">
                        <?php if (isset($_COOKIE['user_id'])) { ?>
                            <div id="logOut" class="nav-item">
                                <li class='nav-item'>
                                    <a href='./logout.php?redirect=<?php echo $currentURL; ?>' class='nav-link'>
                                        <img src='./icon/logout.png' alt='Déconnexion' width='auto' height='30' class='me-2'>
                                    </a>
                                </li>
                            </div>
                        <?php } ?>
                        </div>
                    <?php
                } ?>
                <div class="d-flex navbar-nav">
                    <li class='nav-item'>
                        <a class='nav-link active' aria-current='page' href='./index.php'> 
                            <img src='./images/Y_logo2.png' class='avatar avatar-lg'>
                        </a>
                    </li>
                </div>
            </div>
        </div>
    </nav>

    <main class="p-3 d-flex col-md-8 col-lg-8 mx-auto flex-column ">
        <!-- Overlay pour le formulaire de connexion -->
        <div class="overlay" id="overlay" onclick="hideLoginForm(event)">
            <?php include("./AJAX/login.php"); ?>
        </div>
        <!-- Overlay pour le formulaire de création de compte -->
        <div class="overlay" id="overlay1" onclick="hideNewLoginForm(event)">
            <?php include("./AJAX/newlogin.php"); ?>
        </div>
    </main>

    <!-- Inclusion du script JavaScript -->
    <script src="./JS/header.js"></script>
    <script>
        // Détection de l'état du formulaire de connexion et de création de compte
        var executeToggleLoginFormIfNeeded = <?php echo $executeToggleLoginFormIfNeeded ? 'true' : 'false'; ?>;
        var executeToggleNewLoginFormIfNeeded = <?php echo $executeToggleNewLoginFormIfNeeded ? 'true' : 'false'; ?>;
        // Mise à jour de l'affichage des notifications
        var nbNotifications = <?php echo $nbNotifications; ?>;
        updateNotificationDisplay(nbNotifications);
    </script>
    <!-- Inclusion de Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
