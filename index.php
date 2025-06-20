<?php

//---------------------------------------------------------------------------------------------------//
// Naam script		  : index.php
// Omschrijving		  : Homepage van het project
// Naam ontwikkelaar  : 
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
session_start();
require_once 'pages/components/class.php';
include_once 'pages/dashboard.php';

// Volgende code haalt alle user informatie uit een class
if (isset($_SESSION['user'])){
    $storedUser = unserialize($_SESSION['user']);
    $userID = $storedUser->getId();
    $userName = $storedUser->getName();
    $userEmail = $storedUser->getEmail();
    $userAddressId = $storedUser->getAddressId();
    $userRol = $storedUser->getRoleId();

    $welcomeOverlay = "none_overlay";

} else {
    $welcomeOverlay = "overlay";
}

//popup
if(isset($_GET["error"])) {
    if ($_GET["error"] == "wrongWay") {
        echo "<div class='popup2'>
          <p> 🕵️‍♂️ Je probeert een geheime plek te bezoeken... maar je bent al ingelogd! </p>
          </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <div class="<?php echo $welcomeOverlay; ?>">
        <h1>Welkom bij onze applicatie!</h1>
        <p>Om toegang te krijgen tot het dashboard en gebruik te maken van alle functies, vragen we je om eerst in te loggen. Heb je nog geen account? Registreer je dan eenvoudig en snel.</p>
        <div class="overlay_popup">
            <h2>Login of Registreren</h2>
            <a href="pages/login.php"><button>Login</button></a>
            <a href="pages/register.php"><button>Register</button></a>
        </div>
    </div>
</body>
<script src="assets/JS/script.js"></script>
</html>