<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		: DB_connect.php
// Omschrijving		: Deze file maakt een connectie met de database
// Naam ontwikkelaar: Tejo Veldman
// Project		    : Energie transitie
// Datum		    : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//

    $db_server = "localhost"; // server naam
    $db_user = "root"; // usernaam (default is de naam root)
    $db_pass = ""; // wachtwoord (default is er geen wachtwoord)
    $db_name = "Energie_db"; // naam database
    $conn = "";

//connectie met database
try{
    $conn = mysqli_connect($db_server,
                           $db_user,
                           $db_pass,
                           $db_name);
}
// -----------------------------------------------------------
// Berichten dat laat zien of de database wel of niet gekoppelt is
// Om connectie te testen haal de echo comando's uit de comment (select regel en dan: ctr + /)
// -----------------------------------------------------------
catch(mysqli_sql_exception){
    echo"<h1>Database '$db_name' could not connect!</h1>";
}

if($conn){
    echo"<h1>Database '$db_name' is connected!</h1>";
} 
?>


