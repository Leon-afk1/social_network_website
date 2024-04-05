<script>

previousText2 = "";
timer2 = 0;

function TimerIncrease_fetch() {
  timer2+=200;
  setTimeout('TimerIncrease_fetch()',200);
}
TimerIncrease_fetch();

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
    if (loginForm.style.display === "none") {
        loginForm.style.display = "block";
        overlay.style.display = "block";
    } else {
        loginForm.style.display = "none";
        overlay.style.display = "none";
    }
}

function hideLoginForm(event) {
    var loginForm = document.getElementById("loginForm");
    var overlay = document.getElementById("overlay");
    var card = document.querySelector(".card");
    if (!card.contains(event.target)) {
        loginForm.style.display = "none";
        overlay.style.display = "none";
    }
}

</script>

<?php
  
  $AccountStatus = CheckLogin();

  $tryLogin = false;
  if (isset($_POST["username"]) && isset($_POST["password"])) {
    $tryLogin = true;
    $AccountStatus = CheckLogin();
    if ($AccountStatus["loginSuccessful"]){
      header("Location:./index.php");
      exit();
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

<nav class="navbar navbar-expand-lg bg-dark-subtle px-3 mb-3 mt-3 mx-3 sticky-top rounded-3 shadow">
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
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="./index.php">Home</a></li>
        <?php
          if(isset($_COOKIE['user_id'])){
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./logout.php'>Logout</a></li>";
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./profile.php?id=".$_COOKIE['user_id']."'>Profile</a></li>";
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./poster.php'>Poster</a></li>";
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='#'>Statistique</a></li>";
          } else {
            echo "<li class='nav-item'><button class='nav-link btn btn-link' onclick='toggleLoginForm()' aria-current='page'>Login</button></li>";
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./sign_in.php'>Sign In</a></li>";
          }
        ?>
      </ul>
    </div>
    <div class="d-flex">
      <form class="d-flex nav-item" role="search">
        <input id="suggestField2" class="form-control me-2 shadow" type="search" placeholder="Search" onkeyup="suggestNamesFromInput_fetch(this.value)">
        <button class="btn btn-outline-secondary shadow" type="submit">Search</button>
      </form>
      <div id="suggestions2"></div>
    </div>
    
  </div>
</nav>  

<div class="overlay" id="overlay" onclick="hideLoginForm(event)">
  <main class="p-3 d-flex col-md-8 col-lg-8 mx-auto flex-column ">
      <div class="row justify-content-center ">
          <div class="card w-50 text-bg-dark border-secondary position-absolute top-50 start-50 translate-middle" id="loginForm" style="display: none;">
              <form action="index.php" method="post" class="mb-4">
                  <div class="card-header">
                      <h1 class="card-title">Se connecter</h1>
                  </div>
                  <div class="card-body">
                      <?php if (isset($erreur)) { ?>
                          <div class="alert alert-danger" role="alert">
                              <?php echo $erreur; ?>
                          </div>
                      <?php } ?>
                      <div class="form-group form-field">
                          <label for="username">Nom d'utilisateur:</label>
                          <input type="text" name="username" id="username" class="form-control" required>
                      </div>
                      <div class="form-group form-field">
                          <label for="password">Mot de passe:</label>
                          <input type="password" name="password" id="password" class="form-control" required>
                      </div>
                      <div class="form-group text-center">
                          <input type="submit" value="Se connecter" class="btn btn-primary">
                      </div>
                  </div>
              </form> 
          </div>
      </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
