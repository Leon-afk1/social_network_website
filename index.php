<?php
include ("loc.php");

$AccountStatus = CheckLogin();

include ("BoutDePages/header.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <link href="https://cdn.jsdelivr.net/npm/fastbootstrap@2.2.0/dist/css/fastbootstrap.min.css" rel="stylesheet" integrity="sha256-V6lu+OdYNKTKTsVFBuQsyIlDiRWiOmtC8VQ8Lzdm2i4=" crossorigin="anonymous">
  </head>
  <body class="text-bg-dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  
    <main class="p-3 d-flex w-100 h-75 mx-auto flex-column">
        <div class="px-3">
          <?php
            if (isset($_COOKIE['user_id'])) {
              echo "<h1>Welcome back, " . $_COOKIE['username'] . "!</h1>";
            } else {
              echo "<h1>Welcome to My Page</h1>";
            }
          ?>
          <h2>For you</h2>
          <ul id="trending-posts"></ul>
      
          <h2>Following</h2>
          <ul id="following-posts"></ul>
        </div>
    </main>
    <?php include ("BoutDePages/footer.php"); ?>
    <script src="script.js"></script>
  </body>
</html>