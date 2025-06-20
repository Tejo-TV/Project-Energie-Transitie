<?php

//---------------------------------------------------------------------------------------------------//
// Naam script       : register.inc.php
// Omschrijving      : Op deze pagina wordt de register verwerkt
// Naam ontwikkelaar : Tejo Veldman, dominik
// Project           : Energie Transitie
// Datum             : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//

// Controleer of het registratieformulier is verstuurd
if (isset($_POST["register"])) {

    // alle items die worden gepost worden in een apparte variable gezet
    $naam = $_POST["voornaam"];
    $email = $_POST["email"];
    $adress = "none";
    $ww = $_POST["ww"];
    $wwrepeat = $_POST["wwrepeat"];
    $rol = 1;

    // Laad de databaseconnectie en functies
    require_once '../../../config/DB_connect.php';
    require_once 'functions.inc.php';

    // Controleer of verplichte velden zijn ingevuld
    if (emptyInputRegister($naam, $email, $ww, $wwrepeat) !== false) {
        echo "<script>window.location.href = '../../register.php?error=emptyinput';</script>";
        exit();
    }

    // Controleer of het e-mailadres geldig is
    if (invalidEmail($email) !== false) {
        echo "<script>window.location.href = '../../register.php?error=invalidemail';</script>";
        exit();
    }

    // Controleer of de wachtwoorden overeenkomen
    if (wwMatch($ww, $wwrepeat) !== false) {
        echo "<script>window.location.href = '../../register.php?error=wwnietzelfde';</script>";
        exit();
    }

    // Controleer of het e-mailadres al bestaat
    if (emailExists($conn, $email) !== false) {
        echo "<script>window.location.href = '../../register.php?error=emailTaken';</script>";
        exit();
    }

    // Maak de gebruiker aan in de database
    createUser($conn, $naam, $email, $adress, $ww, $rol);

} else {
    // stuurt persoon terug als er niks te doen is op deze pagina.
    echo "<script>window.location.href = '../../register.php?error=wrongWay';</script>";
    exit();
}

?>
<title>Redirect</title>