<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		  : header.php
// Omschrijving		  : Op deze pagina staat de header van de deshboard
// Naam ontwikkelaar  : Tejo Veldman, dominik, tijs
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/CSS/style.css">
</head>

<div class="header">
    <div class="logo">
        <img src="assets/images/electricity.svg" alt="Logo" draggable="false" />
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Search">
    </div>
    <div class="header-links">
        <button onclick="logoutOverlay()" class="header_logUit">Log uit</button>
        <button onclick="settingsOverlay()" class="header_settings">Settings</button>
    </div>
</div> 
<script src="assets/JS/script.js"></script>