<?php

//---------------------------------------------------------------------------------------------------//
// Naam script		    : index.php
// Omschrijving		    : Homepage van het project
// Naam ontwikkelaar  : Tejo Veldman, dominik, Mateo, Tijs
// Project		        : Energie Transitie
// Datum		          : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
session_start();
require_once 'pages/components/class.php';
include_once 'pages/dashboard.php';
require_once 'config/DB_connect.php';

if (isset($_SESSION['user'])){
  // Volgende code haalt alle user informatie uit een class
    $storedUser = unserialize($_SESSION['user']);
    $userID = $storedUser->getId();
    $userName = $storedUser->getName();
    $userEmail = $storedUser->getEmail();
    $userAddressId = $storedUser->getAddressId();

if ($userAddressId == 0) {
    $userAddressStraat = "";
    $userAddressHuisnummer = "";
    $userAddressPostcode = "";
    $userAddressStad = "";
    $userAddressLand = "NONE";
  } else {
    $sql = "SELECT * FROM address WHERE ID = '$userAddressId';";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $userAddressStraat = $row['street'];
      $userAddressHuisnummer = $row['number'];
      $userAddressPostcode = $row['postcode'];
      $userAddressStad = $row['city'];
      $userAddressLand = $row['country'];
    } else {
      // Als het adres niet bestaat, zet alles leeg
      $userAddressStraat = "";
      $userAddressHuisnummer = "";
      $userAddressPostcode = "";
      $userAddressStad = "";
      $userAddressLand = "NONE";
    }
  }

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
    } else if ($_GET["error"] == "wrongWayAdmin") {
        echo "<div class='popup2'>
          <p> 🕵️‍♂️ Je probeert een geheime plek te bezoeken... maar je hebt geen toegang. </p>
          </div>";
    } else if ($_GET["error"] == "unknownError") {
        echo "<div class='popup2'>
              <p> 🛠️ Oeps! Er ging iets fout. Rapporteer de foutmelding. </p>
              </div>";
    }
}

if (isset($_POST["updateSettings"])) {
    $naam = $_POST['settingsNaam'];
    $email = $_POST['settingsEmail'];
    $straat = $_POST['settingsStraat'];
    $huisnr = $_POST['settingsHuisnummer'];
    $postcode = $_POST['settingsPostcode'];
    $stad = $_POST['settingsStad'];
    $land = $_POST['settingsLand'];

    // Update user info
    $queryUser = mysqli_query($conn, "UPDATE user SET name = '$naam', email = '$email' WHERE ID = '$userID'");
    if (!$queryUser) {
        echo "<script>window.location.href = 'index.php?error=oplaanError';</script>";
        exit();
    }

    // Check of user al een adres heeft
    $addressIdQuery = mysqli_query($conn, "SELECT address_id FROM user WHERE ID = '$userID'");
    if (!$addressIdQuery) {
        echo "<script>window.location.href = 'index.php?error=oplaanError';</script>";
        exit();
    }

    $addressRow = mysqli_fetch_assoc($addressIdQuery);
    $addressId = $addressRow['address_id'];

    if (empty($addressId) || $addressId == 0) {
        // Voeg nieuw adres toe
        $insertAddress = mysqli_query($conn, "INSERT INTO address (street, number, postcode, city, country) VALUES ('$straat', '$huisnr', '$postcode', '$stad', '$land')");
        if (!$insertAddress) {
            echo "<script>window.location.href = 'index.php?error=oplaanError';</script>";
            exit();
        }
        $newAddressId = mysqli_insert_id($conn);
        // Update user met nieuw adres ID
        $updateUserAddress = mysqli_query($conn, "UPDATE user SET address_id = '$newAddressId' WHERE ID = '$userID'");
        if (!$updateUserAddress) {
            echo "<script>window.location.href = 'index.php?error=oplaanError';</script>";
            exit();
        }
    } else {
        // Update bestaand adres
        $updateAddress = mysqli_query($conn, "UPDATE address SET street = '$straat', number = '$huisnr', postcode = '$postcode', city = '$stad', country = '$land' WHERE ID = '$addressId'");
        if (!$updateAddress) {
            echo "<script>window.location.href = 'index.php?error=oplaanError';</script>";
            exit();
        }
    }

    // Alles gelukt, toon een popup
    echo "<script>window.location.href = 'index.php?error=opgeslagen';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
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
    <form class="settings_form" method="POST">

      <label class="form_label">Naam</label>
      <input type="text" class="form_input" name="settingsNaam" placeholder="Nieuwe gebruikersnaam" value="<?php echo $userName; ?>"required>

      <label class="form_label">E-mailadres</label>
      <input type="email" class="form_input" name="settingsEmail" placeholder="Nieuw e-mailadres" value="<?php echo $userEmail; ?>" required>

      <div class="adresForm_alert" id="removeHighlight">
      <div class="form_row">
        <div class="form_group">
          <label class="form_label">Straat</label>
          <input type="text" class="form_input" name="settingsStraat" placeholder="Bijv. Langestraat" value="<?php echo $userAddressStraat; ?>" required>
        </div>
        <div class="form_group">
          <label class="form_label">Huisnummer</label>
          <input type="text" class="form_input" name="settingsHuisnummer" placeholder="12A" value="<?php echo $userAddressHuisnummer; ?>" required>
        </div>
      </div>

      <label class="form_label">Postcode</label>
      <input type="text" class="form_input" name="settingsPostcode" placeholder="1234 AB" value="<?php echo $userAddressPostcode; ?>" required>

      <label class="form_label">Stad</label>
      <input type="text" class="form_input" name="settingsStad" placeholder="Bijv. Utrecht" value="<?php echo $userAddressStad; ?>" required>

      <label class="form_label">Land</label>
      <select class="form_input" name="settingsLand"  required>
        <option value="" <?= $userAddressLand == "NONE" ? "selected" : "" ?>>-- Selecteer een land --</option>
        <option value="NL" <?= $userAddressLand == "NL" ? "selected" : "" ?>>Nederland</option>
        <option value="BE" <?= $userAddressLand == "BE" ? "selected" : "" ?>>België</option>
        <option value="DE" <?= $userAddressLand == "DE" ? "selected" : "" ?>>Duitsland</option>
        <option value="FR" <?= $userAddressLand == "FR" ? "selected" : "" ?>>Frankrijk</option>
        <option value="UK" <?= $userAddressLand == "UK" ? "selected" : "" ?>>Verenigd Koninkrijk</option>
      </select>
      </div>
      <!-- verwijdert de highlight als het adres is ingevuld -->

      <?php 
       if($HighlightRemove == true){
        echo '<script>addressHighlightRemove();</script>';
       }      
      ?>

      <div class="settings_buttons">
        <button type="submit" class="btn" name="updateSettings">Opslaan</button>
        <button type="button" class="btn" onclick="cancelsettingsOverlay()">Annuleren</button>
      </div>

    </form>
  </div>
</div>


</body>
<script src="assets/JS/script.js"></script>
</html>