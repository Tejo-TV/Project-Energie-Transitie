<?php 
//---------------------------------------------------------------------------------------------------//
// Naam script		  : login.php
// Omschrijving		  : Op deze pagina kan je inloggen
// Naam ontwikkelaar: Tejo Veldman
// Project		      : Energie Transitie
// Datum		        : projectweek - periode 4 - 2025
//---------------------------------------------------------------------------------------------------//
?>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="../assets/CSS/style.css" />
    <link rel="shortcut icon" type="x-icon" href="../assets/images/profile.svg">
  </head>
<body class="login">
<!-- account aangemaakt popup -->
 <?php
 
 if(isset($_GET["error"])) {
  if ($_GET["error"] == "none"){
    echo "<div class='popup'>
          <p> ✅ Account succesvol aangemaakt! Log nu in. </p>
          </div>";
  } else if ($_GET["error"] == "wrongWay") {
    echo "<div class='popup2'>
          <p> 🕵️‍♂️ Je probeert een geheime plek te bezoeken... maar je hebt geen toegang. </p>
          </div>";
  } else if ($_GET["error"] == "wrongLogin") {
    echo "<div class='popup2'>
          <p> 🚫 Verkeerde email of wachtwoord probeer opnieuw </p>
          </div>";
  } else if ($_GET["error"] == "uitgelogd") {
    echo "<div class='popup'>
          <p> ✅ Succesvol uitgelogd!</p>
          </div>";
  }
}

 ?>

<!-- main body -->
  <a href="../index.php"><img class="login_arrow" alt="login-logo" src="../assets/images/arrow.svg" draggable="false" /></a>
  <img class="login_logo" alt="login-logo" src="../assets/images/electricity.svg" draggable="false" />

  <div class="login_div">
    <form action="components/login/login.inc.php" method="POST">
      <input type="email" name="email" placeholder="Voer uw e-mail in" required />

      <div class="passwd-wrap">
        <input type="password" id="password" name="ww" placeholder="Voer uw wachtwoord in" required>
      </div>

      <button type="submit" name="login" class="login-button">Login</button>
    </form>
    <p class="signup">
      Heb je geen account?
      <a href="register.php"><span class="free">Registreer nu gratis</span>!</a>
    </p>
  </div>
</body>
</html>
