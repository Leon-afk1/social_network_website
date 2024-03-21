<nav class="navbar navbar-expand-lg bg-dark-subtle px-3 mb-3 mt-3 mx-3 sticky-top rounded-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"></a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="./index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">About</a></li>
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Contact</a></li>
      </ul>
      <form class="d-flex" role="search">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
          if(isset($user)){
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./logout.php'>Logout</a></li>";
          } else {
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./logout.php'>Logout</a></li>";

            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./login.php'>Login</a></li>";
            echo "<li class='nav-item'><a class='nav-link active' aria-current='page' href='./sign_in.php'>Sign In</a></li>";
          }
        ?>
        
      </ul>
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>