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
    if ($userAddressId == 0){
        echo "<div class='popup3'>
          <p> ⚠️ Ik zie dat je <strong>geen</strong> adres hebt gekoppelt! </p> <button onclick='settingsOverlay()'>Vul nu in!</button>
          </div>";
          $HighlightRemove = false;
    } else {
      $HighlightRemove = true;
    }

} else {
    $welcomeOverlay = "overlay";
}

// popup wanneer iemand al is ingelogd en naar de inlog pagina gaat
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
    <!-- Overlay voor NIET ingelogde -->
    <div class="<?php echo $welcomeOverlay; ?>">
        <h1>Welkom bij onze applicatie!</h1>
        <p>Om toegang te krijgen tot het dashboard en gebruik te maken van alle functies, vragen we je om eerst in te loggen. Heb je nog geen account? Registreer je dan eenvoudig en snel.</p>
        <div class="overlay_popup">
            <h2>Login of Registreren</h2>
            <a href="pages/login.php"><button>Login</button></a>
            <a href="pages/register.php"><button>Register</button></a>
        </div>
    </div>

    <!-- Overlay voor als je wilt uitloggen -->
         <div class="logout_overlay" id="logoutOverlay">
            <div class="overlay_popup">
            <h2>Weet je zeker dat je wilt uitloggen?</h2>
            <a href="pages/components/login/logout.inc.php"><button>Log uit</button></a>
            <button onclick="cancelLogoutOverlay()">Annuleren</button>
        </div>
    </div>

    <!-- Overlay voor als je je settings wilt bewerken/bekijken  -->
<div class="settings_overlay" id="settingsOverlay">
  <div class="settings_popup">
    <h2>Instellingen aanpassen</h2>
    <form class="settings_form">

      <label class="form_label">Naam</label>
      <input type="text" class="form_input" placeholder="Nieuwe gebruikersnaam">

      <label class="form_label">E-mailadres</label>
      <input type="email" class="form_input" placeholder="Nieuw e-mailadres">

      <div class="adresForm_alert" id="removeHighlight">
      <div class="form_row">
        <div class="form_group">
          <label class="form_label">Straat</label>
          <input type="text" class="form_input" placeholder="Bijv. Langestraat">
        </div>
        <div class="form_group">
          <label class="form_label">Huisnummer</label>
          <input type="text" class="form_input" placeholder="12A">
        </div>
      </div>

      <label class="form_label">Postcode</label>
      <input type="text" class="form_input" placeholder="1234 AB">

      <label class="form_label">Stad</label>
      <input type="text" class="form_input" placeholder="Bijv. Utrecht">

      <label class="form_label">Land</label>
      <select class="form_input">
        <option value="">-- Kies een land --</option>
        <option value="NL">Nederland</option>
        <option value="BE">België</option>
        <option value="DE">Duitsland</option>
        <option value="FR">Frankrijk</option>
        <option value="UK">Verenigd Koninkrijk</option>
      </select>
      </div>
      <!-- verwijdert de highlight als het adres is ingevuld -->

      <?php 
       if($HighlightRemove == true){
        echo '<script>addressHighlightRemove();</script>';
       }      
      ?>

      <div class="settings_buttons">
        <button type="submit" class="btn">Opslaan</button>
        <button type="button" class="btn" onclick="cancelsettingsOverlay()">Annuleren</button>
      </div>

    </form>
  </div>
</div>


</body>
<script src="assets/JS/script.js"></script>
</html>