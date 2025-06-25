<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		  : admin-header.php
// Omschrijving		  : Op deze pagina staat de header van de deshboard
// Naam ontwikkelaar  : Tejo Veldman
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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

<!-- Overlay voor als je wilt uitloggen -->
     <div class="logout_overlay" id="logoutOverlay">
        <div class="overlay_popup">
        <h2>Weet je zeker dat je wilt uitloggen?</h2>
        <a href="components/login/logout.inc.php"><button>Log uit</button></a>
        <button onclick="cancelLogoutOverlay()">Annuleren</button>
    </div>
</div>
<script src="../assets/JS/script.js"></script>