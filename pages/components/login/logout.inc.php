
<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		  : logout.inc.php
// Omschrijving		  : Op deze pagina wordt de user uitgelogd
// Naam ontwikkelaar  : Tejo Veldman
// Project		      : Energie Transitie
// Datum		      : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
session_start();
session_unset();
session_destroy();

// Stuur de gebruiker terug naar de loginpagina met de uilog melding
header("Location: ../../login.php?error=uitgelogd");
exit();
