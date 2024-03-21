<?php
include ("BoutDePages/dataBaseFunctions.php");
DeleteLoginCookie();
header("Location: index.php");
exit();
?>