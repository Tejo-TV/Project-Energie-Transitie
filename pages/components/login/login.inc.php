<?php

//---------------------------------------------------------------------------------------------------//
// Naam script       : login.inc.php
// Omschrijving      : Op deze pagina wordt de login verwerkt
// Naam ontwikkelaar : Tejo Veldman
// Project           : Energie Transitie
// Datum             : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//

// Controleer of het login-formulier is verstuurd
if (isset($_POST["login"])) {

    // alle items die worden gepost worden in een apparte variable gezet
    $email = $_POST["email"];
    $ww = $_POST["ww"];

    // Laad de databaseconnectie en functies
    require_once '../../../config/DB_connect.php';
    require_once 'functions.inc.php';

    // Controleer of verplichte velden zijn ingevuld
    if (emptyInputLogin($email, $ww) !== false) {
        echo "<script>window.location.href = '../../login.php?error=emptyinput';</script>";
        exit();
    }

    // Probeer de gebruiker in te loggen
    loginUser($conn, $email, $ww);
} else {
    // Als deze pagina direct wordt bezocht, stuur terug naar login
    echo "<script>window.location.href = '../../login.php?error=wrongWay';</script>";
    exit();
}
?>
<title>Redirection</title>