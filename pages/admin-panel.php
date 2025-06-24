<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		    : index.php
// Omschrijving		    : Homepage van het project
// Naam ontwikkelaar  : Tejo Veldman, dominik, Mateo, Tijs
// Project		        : Energie Transitie
// Datum		          : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//

  session_start();
  require_once 'components/class.php';
  // Voeg de admin-header toe (logo, titel, evt. navigatie)
  include_once 'components/admin-header.php'; 

  if (isset($_SESSION['user'])){
    // Volgende code haalt alle user informatie uit een class
    $storedUser = unserialize($_SESSION['user']);
    $userID = $storedUser->getId();
    $userName = $storedUser->getName();
    $userEmail = $storedUser->getEmail();
    $userAddressId = $storedUser->getAddressId();
    $userRol = 2;

  if($userRol == 2){
    exit();
  } else if($userRol == 3){
    echo "<script>window.location.href = '../index.php?error=unknownError';</script>";
    exit();
  } else {
    echo "<script>window.location.href = '../index.php?error=wrongWayAdmin';</script>";
    exit();
  }
}

if(isset($_GET["error"])) {
    if ($_GET["error"] == "wrongWay") {
        echo "<div class='popup2'>
          <p> 🕵️‍♂️ Je probeert een geheime plek te bezoeken... maar je bent al ingelogd! </p>
          </div>";
    } else if ($_GET["error"] == "unknownError") {
        echo "<div class='popup2'>
              <p> 🛠️ Oeps! Er ging iets fout. Rapporteer de foutmelding. </p>
              </div>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<p class="admin-welkom">Welkom admin: <?php echo $userName; ?></p>
</body>
<script src="../assets/JS/script.js"></script>
</html>