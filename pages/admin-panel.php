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

  if (isset($_SESSION['user'])){
    // Volgende code haalt alle user informatie uit een class
    $storedUser = unserialize($_SESSION['user']);
    $userID = $storedUser->getId();
    $userName = $storedUser->getName();
    $userEmail = $storedUser->getEmail();
    $userAddressId = $storedUser->getAddressId();
    $userRol = $storedUser->getRoleId();

  if($userRol == 2){
    // exit();
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
<div class="header">
    <div class="logo">
        <img src="../assets/images/electricity.svg" alt="Logo" draggable="false" />
    </div>
    <div class="header-links">
        <button onclick="logoutOverlay()" class="header_logUit">Log uit</button>
    </div>
</div> 
<body>
    <div class="admin-welkom">
        <p>Welkom admin: <?php echo $userName; ?></p>
    </div>
    <div class="panel-grid">
        <div class="panel-field1">
          <h1>Users</h1>
        </div>
        <div class="panel-field2">
          <h1>Data</h1>
        </div>
        <div class="panel-field3">
          <h1>Website data</h1>
        </div>
    </div>



<!-- Overlay voor als je wilt uitloggen -->
     <div class="logout_overlay" id="logoutOverlay">
        <div class="overlay_popup">
        <h2>Weet je zeker dat je wilt uitloggen?</h2>
        <a href="components/login/logout.inc.php"><button>Log uit</button></a>
        <button onclick="cancelLogoutOverlay()">Annuleren</button>
    </div>
</div>

</body>
<script src="../assets/JS/script.js"></script>
</html>