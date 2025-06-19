<?php

//---------------------------------------------------------------------------------------------------//
// Naam script		  : login.inc.php
// Omschrijving		  : Op deze pagina wordt de login verwerkt
// Naam ontwikkelaar  : Tejo Veldman, dominik
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//

if (isset($_POST["login"])) {

    // alle items die worden gepost worden in een apparte variable gezet
    $email = $_POST["email"];
    $ww = $_POST["ww"];

    // include de database connectie en de functies file.
    require_once '../../config/DB_connect.php';
    require_once 'functions.inc.php';


    if (emptyInputLogin($email, $ww) !== false) {
        echo "<script>window.location.href = '../login.php?error=emptyinput';</script>";
        exit();
    }

    loginUser($conn, $email, $ww);
} else {
    echo "<script>window.location.href = '../login.php?error=wrongWay';</script>";
    exit();
}
?>
<title>Redirection</title>