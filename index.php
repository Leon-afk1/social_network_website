<?php
include ("BoutDePages/dataBaseFunctions.php");
ConnectToDataBase();

$AccountStatus = CheckLogin();
if (!$AccountStatus["loginSuccessful"]){
    // echo "connected";
}

include ("BoutDePages/header.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
  </head>
  <body>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->

    <main>
        <div>
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
    </main>
    <?php include ("BoutDePages/footer.php"); ?>
    <script src="script.js"></script>
  </body>
</html>