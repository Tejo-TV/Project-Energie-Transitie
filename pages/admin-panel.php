<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="../assets/CSS/style.css">
</head>
<body>
  <?php 
  session_start();
  require_once 'components/class.php';
  // Voeg de admin-header toe (logo, titel, evt. navigatie)
  include_once 'components/admin-header.php'; 

  if (isset($_SESSION['user'])){
    // Volgende code haalt alle user informatie uit een class
    $storedUser = unserialize($_SESSION['user']);
    $userID = $storedUser->getId();
    $userName = $storedUser->getName();
    $userEmail = $storedUser->getEmail();
    $userAddressId = $storedUser->getAddressId();
    $userRol = $storedUser->getRoleId();

  if($userRol == 2){
    exit();
  } else if($userRol == 3){
    echo "<script>window.location.href = '../index.php?error=unknownError';</script>";
    exit();
  } else {
    echo "<script>window.location.href = '../index.php?error=wrongWayAdmin';</script>";
    exit();
  }
}

if(isset($_GET["error"])) {
    if ($_GET["error"] == "wrongWay") {
        echo "<div class='popup2'>
          <p> 🕵️‍♂️ Je probeert een geheime plek te bezoeken... maar je bent al ingelogd! </p>
          </div>";
    } else if ($_GET["error"] == "unknownError") {
        echo "<div class='popup2'>
              <p> 🛠️ Oeps! Er ging iets fout. Rapporteer de foutmelding. </p>
              </div>";
    }
}
    ?>

</body>
</html>