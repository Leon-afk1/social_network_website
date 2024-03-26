<?php
include ("BoutDePages/dataBaseFunctions.php");
ConnectToDataBase();
$newAccountStatus = register();

if ($newAccountStatus["Successful"]){
	  header("Location:http://".$rootpath."/login.php");
    exit();
}

include ("BoutDePages/header.php");

?>

<!DOCTYPE html>
<html>
  <head>
    <title>My Page</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  </head>
  <body class="text-bg-dark">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <main>
        <div class="container mt-5" id="sign_in">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                  <?php include ("BoutDePages/newlogin.php"); ?>
                </div>
            </div>
        </div>
    </main>
    <script src="script.js"></script>
    <?php include ("BoutDePages/footer.php"); ?>
  </body>
</html>