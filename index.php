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

// Volgende code haalt alle user informatie uit een class
if (isset($_SESSION['user'])){
    $storedUser = unserialize($_SESSION['user']);
    $userID = $storedUser->getId();
    $userName = $storedUser->getName();
    $userEmail = $storedUser->getEmail();
    $userAddressId = $storedUser->getAddressId();
    $userRol = $storedUser->getRoleId();

    echo $userID . "<br>" .
     $userName . "<br>" .
     $userEmail . "<br>" .
     $userAddressId . "<br>" .
     $userRol . "<br>";

} else {
    echo "no user found";
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
    <form method="post" action="pages/components/logout.inc.php">
        <button type="submit" name="logout">Log out</button>
    </form>
</body>
</html>