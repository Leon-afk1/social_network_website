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



</script>

<?php
  if(isset($_COOKIE['user_id'])){
    $Infos = GetInfoProfile($_COOKIE['user_id']);
  }
?>

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
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./login.php'>Login</a></li>";
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