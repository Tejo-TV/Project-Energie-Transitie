<?php
//---------------------------------------------------------------------------------------------------//
// Naam script		  : register.php
// Omschrijving		  : Hier maak je een nieuw account aan
// Naam ontwikkelaar  : Tejo Veldman, Strahinja Zoranovic
// Project		      : Apothecare
// Datum		      : projectweek - periode 3 - 2025
//---------------------------------------------------------------------------------------------------// 
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="shortcut icon" type="x-icon" href="">
</head>
<body class="register">
    <?php
    // error berichten 
    if(isset($_GET["error"])) {
      if ($_GET["error"] == "emptyinput"){
        echo "<div class='popup2'>
              <p> ⚠️ Niet alle velden zijn ingevuld. Vul alles in of neem contact met ons op. </p>
              </div>";
      } else if ($_GET["error"] == "invaidemail") {
        echo "<div class='popup2'>
              <p> ⚠️ Ongeldig e-mailadres. Controleer of je het juist hebt ingevuld. </p>
              </div>";
      } else if ($_GET["error"] == "wwnietzelfde") {
        echo "<div class='popup2'>
              <p> 🔐 Wachtwoorden zijn niet hetzelfde. Controleer beide velden. </p>
              </div>";
      } else if ($_GET["error"] == "emailTaken") {
        echo "<div class='popup2'>
              <p> ⚠️ Dit e-mailadres is al in gebruik. Probeer een ander e-mailadres. </p>
              </div>";
      } else if ($_GET["error"] == "stmtfailed") {
        echo "<div class='popup2'>
              <p> 🛠️ Oeps! Er ging iets fout bij het verwerken. Probeer het nog eens. </p>
              </div>";
      } else if ($_GET["error"] == "wrongWay") {
        echo "<div class='popup2'>
              <p> 🕵️‍♂️ Je probeert een geheime plek te bezoeken... maar je hebt geen toegang. </p>
              </div>";
      }
    }
  
    ?>

            <img class="register_logo" alt="register-logo" src="../assets/images/electricity.svg" />
        <div class="register_div">
            <form action="components/register.inc.php" method="POST">  

                <input type="text" class="register_input" name="voornaam" placeholder="Voer uw naam in" required>

                <input type="email" class="register_input" name="email" placeholder="Voer uw e-mail in" required>

                <ul class="wachtwoordregels"> <li>Wachtwoord moet minimaal 8 karakters bevatten.</li><li>Met minstens 1 Speciaal teken.</li><li>Met minstens 1 letter en cijfer.</li></ul> 
                <input type="password" class="register_inputww" name="ww" placeholder="Voer uw wachtwoord in" minlength="8" pattern=".*[\d].*" pattern=".*[\W_].*" required>

                <input type="password" class="register_inputww" name="wwrepeat" placeholder="Voer uw wachtwoord opnieuw in" minlength="8"  pattern="^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).{8,}$" required>
                
                <button type="submit" name="register" class="register-button" >Registreer nu</button>
            </form>
            <p class="signup">
              Heb je al een account?
            <a href="login.php"><span class="free">Log in</span>!</a>
            </p>
    </div>
</body>
</html>
