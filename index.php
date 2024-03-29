<?php
include ("BoutDePages/dataBaseFunctions.php");
ConnectToDataBase();

$AccountStatus = CheckLogin();

include ("BoutDePages/header.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Y - Accueil</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body class="text-bg-dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  
    <main class="p-3 d-flex w-100 h-75 mx-auto flex-column">
        <div class="px-3">
          <?php
            if (isset($_COOKIE['user_id'])) {
              echo "<h1>Welcome back, " . $_COOKIE['username'] . "!</h1>";
            } else {
              echo "<h1>Welcome to Y</h1>";
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